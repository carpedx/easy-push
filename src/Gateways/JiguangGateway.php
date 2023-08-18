<?php


namespace Carpedx\EasyPush\Gateways;


use Carpedx\EasyPush\Exceptions\GatewayErrorException;
use Carpedx\EasyPush\Support\Config;
use Carpedx\EasyPush\Traits\HasHttpRequest;
use GuzzleHttp\Exception\ClientException;

/**
 * Class JiguangGateway.
 */
class JiguangGateway extends Gateway
{
    use HasHttpRequest;

    const ENDPOINT_TEMPLATE = 'https://%s.jpush.cn/%s/%s';

    const ENDPOINT_VERSION = 'v3';

    const SUCCESS_CODE = '0';

    /**
     * Push a short message.
     *
     * @param array $pushload
     * @param Config $config
     *
     * @return array
     */
    public function push(array $pushload, Config $config)
    {
        try {
            $response = $this->postJson(
                $this->buildEndpoint('api', 'push'),
                $pushload,
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json;charset=utf-8',
                    'Authorization' => 'Basic ' . base64_encode($config->get('app_key') . ':' . $config->get('master_secret')),
                ]
            );
            if (isset($response['error']) && $error = $response['error']) {
                throw new GatewayErrorException($error['code'] . ':' . $error['message'], $error['code'], $response);
            }
            return $response;
        } catch (ClientException $exception) {
            $responseContent = $exception->getResponse()->getBody()->getContents();
            $response = json_decode($responseContent, true);
            if (isset($response['error']) && $error = $response['error']) {
                throw new GatewayErrorException($error['code'] . ':' . $error['message'], $error['code'], $response);
            }
            throw new GatewayErrorException($responseContent, $exception->getCode(), ['content' => $responseContent]);
        }
    }

    /**
     * Build endpoint url.
     *
     * @param string $type
     * @param string $function
     *
     * @return string
     */
    protected function buildEndpoint($type, $function)
    {
        return sprintf(self::ENDPOINT_TEMPLATE, $type, self::ENDPOINT_VERSION, $function);
    }
}
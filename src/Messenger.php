<?php


namespace Carpedx\EasyPush;


use Carpedx\EasyPush\Exceptions\NoGatewayAvailableException;

/**
 * Class Messenger.
 */
class Messenger
{
    const STATUS_SUCCESS = 'success';

    const STATUS_FAILURE = 'failure';

    /**
     * @var EasyPush
     */
    protected $easyPush;

    /**
     * constructor.
     *
     * @param EasyPush $easyPush
     */
    public function __construct(EasyPush $easyPush)
    {
        $this->easyPush = $easyPush;
    }

    /**
     * Push a message.
     *
     * @param array $pushload
     * @param array $gateways
     *
     * @return array
     *
     * @throws NoGatewayAvailableException
     */
    public function push(array $pushload, array $gateways = [])
    {
        $results = [];
        $isSuccessful = false;

        foreach ($gateways as $gateway => $config) {
            try {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_SUCCESS,
                    'result' => $this->easyPush->gateway($gateway)->push($pushload, $config),
                ];
                $isSuccessful = true;

                break;
            } catch (Exception $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            } catch (Throwable $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            }
        }

        if (!$isSuccessful) {
            throw new NoGatewayAvailableException($results);
        }

        return $results;
    }
}
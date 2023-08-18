<?php


namespace Carpedx\EasyPush\Tests\Gateways;


use Carpedx\EasyPush\EasyPush;
use Carpedx\EasyPush\Exceptions\GatewayErrorException;
use Carpedx\EasyPush\Gateways\JiguangGateway;
use Carpedx\EasyPush\Support\Config;
use Carpedx\EasyPush\Tests\TestCase;

class JiguangGatewayTest extends TestCase
{
    public function test()
    {
        $config = [
            // HTTP 请求的超时时间（秒）
//            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
//                'strategy' => \Carpedx\EasyPush\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'jiguang'
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'jiguang' => [
                    'access_key_id' => '',
                    'access_key_secret' => '',
                    'sign_name' => '',
                ],
            ],
        ];
        $easyPush = new EasyPush($config);
        $easyPush->push([]);
    }

    public function testPush()
    {
        $config = [
            'app_key' => 'eb21672cb619c8a0b247d612',
            'master_secret' => '061a11c27346aa72a2956e05',
        ];
        $gateway = \Mockery::mock(JiguangGateway::class . '[postJson]', [$config])->shouldAllowMockingProtectedMethods();

        $gateway->shouldReceive('postJson')
            ->with(
                'https://api.jpush.cn/v3/push',
                \Mockery::on(function ($params) {
                    if (
                        (!isset($params['platform']) || empty($params['platform'])) ||
                        (!isset($params['audience']) || empty($params['audience'])) ||
                        ((!isset($params['message']) || empty($params['message'])) && (!isset($params['notification']) || empty($params['notification'])))
                    ) {
                        return false;
                    }

                    return true;
                }),
                \Mockery::on(function ($params) use($config) {
                    if (!isset($params['Authorization']) || empty($params['Authorization'])) {
                        return false;
                    }

                    $authorization = 'Basic ' . base64_encode($config['app_key'] . ':' . $config['master_secret']);

                    return $authorization == $params['Authorization'];
               })
            )
            ->andReturn([
                'sendno' => JiguangGateway::SUCCESS_CODE,
                'msg_id' => '18100925337025339',
            ], [
                'error' => [
                    'code' => 1004,
                    'message' => 'Authen failed',
                ]
            ])->twice();

        $config = new Config($config);

        $pushload = [
            'platform' => 'all',
            'audience' => [
                'registration_id' => ['141fe1da9fba5ef9e1b']
            ],
            'message' => [
                'msg_content' => 'Hi,JPush',
                'content_type' => 'text',
                'title' => 'msg',
                'extras' => [
                    'key' => 'value'
                ]
            ]
        ];
        $this->assertSame([
            'sendno' => JiguangGateway::SUCCESS_CODE,
            'msg_id' => '18100925337025339',
        ], $gateway->push($pushload, $config));

        $this->expectException(GatewayErrorException::class);
        $this->expectExceptionCode(1004);
        $this->expectExceptionMessage('1004:Authen failed');

        $gateway->push($pushload, $config);
    }
}
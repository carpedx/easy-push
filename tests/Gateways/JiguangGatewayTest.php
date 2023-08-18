<?php


namespace Carpedx\EasyPush\Tests\Gateways;


use Carpedx\EasyPush\EasyPush;
use Carpedx\EasyPush\Exceptions\GatewayErrorException;
use Carpedx\EasyPush\Gateways\JiguangGateway;
use Carpedx\EasyPush\Support\Config;
use Carpedx\EasyPush\Tests\TestCase;

class JiguangGatewayTest extends TestCase
{
    public function test(){
        $config = [
            'default' => [
                'gateways' => [
                    'jiguang'
                ],
            ],
            'gateways' => [
                'jiguang' => [
                    'app_key' => 'eb21672cb619c8a0b247d612',
                    'master_secret' => '061a11c27346aa72a2956e05s',
                ],
            ],
        ];
        $easyPush = new EasyPush($config);
        $result = $easyPush->push([
            'platform' => 'all',
            'audience' => [
                'registration_id' => ['141fe1da9fba5ef9e1b']
            ],
            'notification' => [
              'alert' => 'Hi,JPush'
            ],
            'message' => [
                'msg_content' => 'Hi,JPush',
                'content_type' => 'text',
                'title' => 'msg',
                'extras' => [
                    'key' => 'value'
                ]
            ]
        ]);


        /*"message": {
            "msg_content": "Hi,JPush",
            "content_type": "text",
            "title": "msg",
            "extras": {
                    "key": "value"
            }
        },*/
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
                'sendno' => '0',
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
            'sendno' => '0',
            'msg_id' => '18100925337025339',
        ], $gateway->push($pushload, $config));

        $this->expectException(GatewayErrorException::class);
        $this->expectExceptionCode(1004);
        $this->expectExceptionMessage('1004:Authen failed');

        $gateway->push($pushload, $config);
    }
}
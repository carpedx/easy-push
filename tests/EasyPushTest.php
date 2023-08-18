<?php


namespace Carpedx\EasyPush\Tests;


use Carpedx\EasyPush\EasyPush;

class EasyPushTest extends TestCase
{
    public function testConfig()
    {
        $config = [
            'default' => [
                'gateways' => [
                    'jiguang'
                ],
            ],
            'gateways' => [
                'jiguang' => [
                    'api_key' => '7d431e42dfa6a6d693ac2d04',
                    'master_secret' =>'5e987ac6d2e04d95a9d8f0d1',
                ],
            ],
        ];

        $easyPush = new EasyPush($config);

        $easyPush->push([
            'platform'  => 'all',
            'audience' => 'all',
        ]);
    }

    public function testGateway()
    {
        $easyPush = new EasyPush([]);
    }

    public function test()
    {
        var_dump(empty([]));
    }
}
<?php


namespace Carpedx\EasyPush\Tests;


use Carpedx\EasyPush\EasyPush;
use Carpedx\EasyPush\Support\Config;

class EasyPushTest extends TestCase
{
    public function testGateway()
    {
        $easyPush = new EasyPush([]);
        $easyPush->push([
            'foo'  => 'bar'
        ], ['yunpian', 'juhe']);
    }
}
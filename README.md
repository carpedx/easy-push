<h1 align="center">Easy Push</h1>

<p align="center">:calling: 整合了各大平台厂商的推送消息组件</p>


## 特点

......

## 平台支持

- [极光推送](https://docs.jiguang.cn/jpush)

## 运行环境

- PHP >= 5.6

## 详细文档

[https://push.carpedx.com](https://push.carpedx.com)

## 安装

```shell
$ composer require "carpedx/easy-push"
```

## 使用

```php
use Carpedx\EasyPush\EasyPush;

$config = [
    // 默认发送配置
    'default' => [
        // 默认可用的发送网关
        'gateways' => [
            'jiguang'
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'jiguang' => [
            'api_key' => '7d431e42dfa6a6d693ac2d04',
            'master_secret' =>'5e987ac6d2e04d95a9d8f0d1',
        ],
        //...
    ],
];

$easyPush = new EasyPush($config);

$easyPush->push([
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
]);
```

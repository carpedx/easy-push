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
    // HTTP 请求的超时时间（秒），默认5.0（秒）
    'timeout' => 5.0,
    
    // 默认发送配置
    'default' => [
        // 网关调用策略：
        //  \Carpedx\EasyPush\Strategies\OrderStrategy::class 顺序调用（默认）
        //  \Carpedx\EasyPush\Strategies\RandomStrategy::class 随机调用
        'strategy' => \Carpedx\EasyPush\Strategies\OrderStrategy::class,
        
        // 默认可用的发送网关
        'gateways' => [
            'jiguang'
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        // 极光推送 
        'jiguang' => [
            'app_key' => '',
            'master_secret' =>'',
        ],
        // 友盟推送
        'umeng' => [
            'app_key' => '',
            'app_master_secret' =>'',
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

## 发送网关

默认使用 `default` 中的设置来发送，如果你想要覆盖默认的设置。在 `send` 方法中使用第三个参数即可：

```php
$easyPush->send(13188888888, [
    'foo'  => 'bar',
 ], ['jiguang', 'umeng']); // 这里的网关配置将会覆盖全局默认值
```
## 特点

......

## 平台支持

......

## 运行环境

- PHP >= 5.6
- composer < 2.3.0

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
    'platform'  => 'all',
    'audience' => 'all',
]);
```





```
$config = [
    // 默认发送配置
    'default' => [
        // 默认可用的发送网关
        'gateways' => [
            'jiguang' => [
                'api_key' => '7d431e42dfa6a6d693ac2d04',
                'master_secret' =>'5e987ac6d2e04d95a9d8f0d1',
            ]
        ],
    ],
];
```


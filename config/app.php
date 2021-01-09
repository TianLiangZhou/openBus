<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang01
 * Date: 2018/7/3
 * Time: 13:08
 */

return [
    'name' => env('APP_NAME'),
    'env' => env('ENV'),
    'domain' => env('DOMAIN'),
    'debug' => env('DEBUG'),
    'verify_mode' => env('VERIFY_MODE', false),
    'weixin' => [
        'appid' => env('WECHAT_APPID'),
        'secret'=> env('WECHAT_SERECT'),
        'token' => env('WECHAT_TOKEN'),
    ],
    'miniapp' => [
        'appid' => env('MINIAPP_APPID')
    ],
    'baidu' => [
        'appid' => env('BAIDU_APPID'),
        'secret' => env('BAIDU_SERECT'),
        'sk' => env('BAIDU_SK'),
    ],
    'cache' => [
        'driver' => 'redis',
        'redis' => [
            'host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1
        ],
        'path' => realpath(__DIR__ . '/../storage/caches'),
    ],
];

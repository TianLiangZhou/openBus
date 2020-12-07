<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang01
 * Date: 2018/7/3
 * Time: 13:08
 */

return [
    'env' => env('ENV'),
    'domain' => env('DOMAIN'),
    'debug' => env('DEBUG'),
    'verify_mode' => env('VERIFY_MODE', false),
    'weixin' => [
        'appid' => env('WECHAT_APPID'),
        'secret'=> env('WECHAT_SERECT'),
        'token' => env('WECHAT_TOKEN'),
    ],
    'baidu' => [
        'appid' => env('BAIDU_APPID'),
        'secret' => env('BAIDU_SERECT'),
        'sk' => env('BAIDU_SK'),
    ],
    'cache' => [
        'driver' => 'file',
        'memcached' => [
            'host' => '127.0.0.1', 'port' => 11211, 'weight' => 1
        ],
        'path' => realpath(__DIR__ . '/../storage/cache'),
    ],
    'plugins' => include __DIR__  . '/plugins.php'
];

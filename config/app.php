<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang01
 * Date: 2018/7/3
 * Time: 13:08
 */

return [
    'debug' => !!getenv('DEBUG'),
    'weixin' => [
        'appid' => getenv('WECHAT_APPID'),
        'secret'=> getenv('WECHAT_SERECT'),
        'token' => getenv('WECHAT_TOKEN'),
    ],
    'baidu' => [
        'appid' => getenv('BAIDU_APPID'),
        'secret' => getenv('BAIDU_SERECT'),
        'sk' => getenv('BAIDU_SK'),
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

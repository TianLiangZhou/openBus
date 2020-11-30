<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang01
 * Date: 2018/7/3
 * Time: 13:08
 */

return [
    'debug' => $_ENV['DEBUG'] ?? false,
    'weixin' => [
        'appid' => $_ENV['WECHAT_APPID'] ?? '',
        'secret'=> $_ENV['WECHAT_SERECT'] ?? '',
        'token' => $_ENV['WECHAT_TOKEN'] ?? '',
    ],
    'baidu' => [
        'appid' => $_ENV['BAIDU_APPID'] ?? '',
        'secret' => $_ENV['BAIDU_SERECT'] ?? '',
        'sk' => $_ENV['BAIDU_SK'] ?? '',
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

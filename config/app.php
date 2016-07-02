<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/6/30
 * Time: 17:50
 */

return [
    'debug' => true,
    'weixin' => [
        'appid' => 'wx51abd28aef9dc1d8',
        'secret'=> '50d32e66c8b29a2c97eb2db8d99ab119',
        'token' => '5e13a7286238398f05b05cbf81142b61',
        'url' => 'https://api.weixin.qq.com/cgi-bin/',
    ],
    'cache' => [
        'driver' => 'file',
        'memcached' => [
            'host' => '127.0.0.1', 'port' => 11211, 'weight' => 1
        ],
        'path' => APP_STORAGE . '/cache',
    ]
];

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
    ],
    'baidu' => [
        'appid' => '2147384609',
        'secret' => '8377E1ab1af3582362d0b75e99bdea7c',
        'sk' => '7144730360030E93FD1b3eb2e4ff4901',
    ],
    'aibang' => [
        'secret' => 'f41c8afccc586de03a99c86097e98ccb',
    ],
    'cache' => [
        'driver' => 'file',
        'memcached' => [
            'host' => '127.0.0.1', 'port' => 11211, 'weight' => 1
        ],
        'path' => APP_STORAGE . '/cache',
    ]
];

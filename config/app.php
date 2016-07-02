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

    ],
    'cache' => [
        'driver' => 'file',
        'memcached' => [
            'host' => '127.0.0.1', 'port' => 11211, 'weight' => 1
        ],
        'path' => APP_STORAGE . '/cache',
    ]
];

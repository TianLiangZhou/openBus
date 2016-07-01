<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/7/1
 * Time: 17:30
 */
$controller = 'App\\Http\\Controllers\\BusController';
$app->get('/bus', $controller . ':receive');
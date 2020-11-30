<?php
/**
 * Created by PhpStorm.
 * User: zhoutianliang
 * Date: 2016/6/30
 * Time: 9:01
 */

/**
 * 应用入口文件
 */

use Laminas\Diactoros\ServerRequestFactory;

$app = include __DIR__ . '/../bootstrap/bootstrap.php';

$app->run(ServerRequestFactory::fromGlobals());

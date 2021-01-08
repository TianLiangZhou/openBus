<?php

use App\Support\Env;

include __DIR__ . "/vendor/autoload.php";



$dotenv = Dotenv\Dotenv::create(Env::getRepository(), __DIR__);
$dotenv->safeLoad();

$config = include __DIR__ . "/config/app.php";

$cacheDir = __DIR__ . "/storage/caches";
$options = [
    'cacheDir' => $cacheDir,
];
$sdk = new Shrimp\ShrimpWechat($config['weixin']['appid'], $config['weixin']['secret'], $options);

$news = $sdk->material->batchGet();

print_r($news);

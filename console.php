<?php

use App\Services\AMapService;
use App\Support\Env;

include __DIR__ . "/vendor/autoload.php";



$dotenv = Dotenv\Dotenv::create(Env::getRepository(), __DIR__);
$dotenv->safeLoad();

$config = include __DIR__ . "/config/app.php";


$arguments = $argv;
if (count($arguments) < 2) {

    print <<<EOF
news 打印微信图文列表
city 缓存城市列表
EOF;
    exit(0);
}
switch ($arguments[1]) {
    case "news":
        news($config);
        break;
    case "city":
        city($config);
        break;
    default:
        print <<<EOF
未实现的'${arguments[1]}'命令.
EOF;

}


/**
 * @param array $config
 * @throws \Psr\Http\Client\ClientExceptionInterface
 */
function news(array $config)
{
    $cacheDir = __DIR__ . "/storage/caches";
    $options = [
        'cacheDir' => $cacheDir,
    ];
    $sdk = new Shrimp\ShrimpWechat($config['weixin']['appid'], $config['weixin']['secret'], $options);
    $news = $sdk->material->batchGet();
    print_r($news);
}

/**
 * @param array $config
 */
function city(array $config)
{
    $map = new AMapService(null);
    $response = $map->city();
    if ($response->getStatusCode() !== 200) {
        print "获取城市列表出错:" . $response->getStatusCode();
        return ;
    }
    $c = json_decode($response->getBody()->getContents(), true);
    $cities = [];
    foreach ($c['data']['cityByLetter'] as $key => $list) {
        echo $key, "\n";
        $cities = array_merge($cities, $list);
    }
    $redis = new \Redis();
    $redis->connect($config['cache']['redis']['host'], $config['cache']['redis']['port'], $config['cache']['redis']['timeout']);
    if (!$redis->isConnected()) {
        print "连接redis失败:" . $config['cache']['redis']['host'] . ':' . $config['cache']['redis']['port'];
        return ;
    }
    $redis->set('cities', json_encode($cities));
}

<?php

use App\Services\AMapService;
use App\Services\IFlytekService;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientExceptionInterface;

include __DIR__ . "/vendor/autoload.php";

$container = include __DIR__ . '/bootstrap/container.php';

$arguments = $argv;
if (count($arguments) < 2) {
    print <<<EOF
news 打印微信图文列表
city 缓存城市列表
EOF;
    exit(0);
}
$options = array_splice($arguments, 2);
switch ($arguments[1]) {
    case "news":
        news($container, $options);
        break;
    case "city":
        city($container, $options);
        break;
    case "line":
        line($container, $options);
        break;
    case "cws":
        cws($container, $options);
    default:
        print <<<EOF
未实现的'${arguments[1]}'命令.
EOF;

}


/**
 * @param ContainerInterface $container
 * @param array $options
 * @throws ClientExceptionInterface
 */
function news(ContainerInterface $container, array $options = [])
{
    $config = $container->get('config');
    $cacheDir = __DIR__ . "/storage/caches";
    $options = [
        'cacheDir' => $cacheDir,
    ];
    $sdk = new Shrimp\ShrimpWechat($config['weixin']['appid'], $config['weixin']['secret'], $options);
    $news = $sdk->material->batchGet();
    print_r($news);
}

/**
 * @param ContainerInterface $container
 * @param array $options
 */
function city(ContainerInterface $container, array $options = [])
{
    $config = $container->get('config');
    $map = new AMapService($container);
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

/**
 * @param ContainerInterface $container
 * @param array $options
 */
function line(ContainerInterface $container, array $options = [])
{
    $parameters = [
        'keywords' => $options[0],
        'city' => $options[1] ?? '330100',
        'citylimit' => "true",
        'extensions' => "all",
        "output" => "json",
        "offset" => 4,
    ];
    $response = (new AMapService($container))->lineNameSearch($parameters);
    echo $response->getBody()->getContents();
}

/**
 * @param ContainerInterface $container
 * @param array $options
 * @throws GuzzleException
 */
function cws(ContainerInterface $container, array $options = [])
{
    $response = (new IFlytekService($container))->participle($options[0]);

    print_r($response);
}

<?php

use App\Http\Controllers\AMapController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\BusController;
use App\Support\Env;
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

$dir = __DIR__ . '/../storage/caches';
$containerClass = 'CompiledContainer';
$builder= new ContainerBuilder();
$builder->enableDefinitionCache();
$builder->enableCompilation($dir, $containerClass);
$builder->useAutowiring(false);
$builder->useAnnotations(false);
if (!file_exists($dir . '/' . $containerClass . '.php')) {
    $dotenv = Dotenv\Dotenv::create(Env::getRepository(), __DIR__ . '/../');
    $dotenv->safeLoad();
    $builder->addDefinitions([
        'config' => require __DIR__ . '/../config/app.php',
        'logger' => function (ContainerInterface $container) {
            $name = $container->get('config')['name'];
            $logger = new Logger($name);
            $logger->pushHandler(new StreamHandler(
                __DIR__ . '/../storage/logs/' . PHP_SAPI . '-' . date('Ymd') . '.log',
                Monolog\Logger::DEBUG,
            ));
            return $logger;
        },
        'amap.controller' => function (ContainerInterface $container) {
            return new AMapController($container);
        },
        'bus.controller' => function (ContainerInterface $container) {
            return new BusController($container);
        },
        'api.controller' => function (ContainerInterface $container) {
            return new ApiController($container);
        },
        'cache' => function (ContainerInterface $container) {
            $config = $container->get('config');
            if (!class_exists("\Redis")) {
                throw new RuntimeException("'redis' extension no load");
            }
            $redis = new \Redis();
            $redis->connect(
                $config['cache']['redis']['host'],
                $config['cache']['redis']['port'],
                $config['cache']['redis']['timeout']
            );
            return $redis;
        },
        'client' => function (ContainerInterface $container) {
            return new Client();
        },
    ]);
}
try {
    return $builder->build();
} catch (\Exception $e) {
    echo $e->getMessage();
    exit(254);
}

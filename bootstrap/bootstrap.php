<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Http\Controllers\AMapController;
use App\Http\Controllers\BusController;
use App\Http\Middleware\Cross;
use App\Support\Env;
use DI\ContainerBuilder;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\StreamFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;


$dir = __DIR__ . '/../storage/caches';


$containerClass = 'CompiledContainer';

$builder= new ContainerBuilder();
$builder->enableDefinitionCache();
$builder->enableCompilation($dir, $containerClass);
$builder->useAutowiring(false);
$builder->useAnnotations(false);
try {
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
            }
        ]);
    }
    $container = $builder->build();
} catch (\Exception $e) {
    echo $e->getMessage();
    exit(254);
}
AppFactory::setResponseFactory(new ResponseFactory());
AppFactory::setStreamFactory(new StreamFactory());
AppFactory::setContainer($container);
$app = AppFactory::create();
$config = $container->get('config');
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', "1");
}
if ($config['env'] === 'production') {
    $app->getRouteCollector()->setCacheFile(__DIR__ . '/../storage/caches/route.cache.php');
}
$errorHandler = $app->addErrorMiddleware(true, true, true);
$errorHandler->setErrorHandler(
    [
        Exception::class,
    ],
    function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
        return new TextResponse($exception->getMessage());
    },
    true
);
if ($config['env'] !== 'production') {
    $app->addMiddleware(new Cross());
}
$app->any('/message', 'bus.controller:receive');
$app->post('/amap/poi_info_lite', 'amap.controller:poi');
$app->post('/amap/station_line', 'amap.controller:stationLine');
$app->post('/amap/line_station', 'amap.controller:lineStation');
$app->post('/amap/line', 'amap.controller:line');
$app->post('/amap/near_line', 'amap.controller:nearLine');
$app->get('/amap/poi_tips_lite', 'amap.controller:poiLite');
$app->get('/amap/address_to_location', 'amap.controller:locationByAddress');
$app->get('/amap/ip_to_location', 'amap.controller:locationByIp');
$app->get('/amap/cities', 'amap.controller:city');
return $app;

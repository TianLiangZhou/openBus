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


$dotenv = Dotenv\Dotenv::create(Env::getRepository(), __DIR__ . '/../');
$dotenv->safeLoad();

$config = require __DIR__ . '/../config/app.php';

$builder= new ContainerBuilder();
$builder->addDefinitions([
    'config' => $config,
    'logger' => function (ContainerInterface $container) {
        $logger = new Logger($_ENV['APP_NAME']);
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
    }
]);
$builder->enableDefinitionCache();
$builder->enableCompilation(__DIR__ . '/../storage/caches');
$builder->useAutowiring(false);
$builder->useAnnotations(false);

try {
    $container = $builder->build();
} catch (Exception $e) {
    echo $e->getMessage();
    exit(254);
}

AppFactory::setResponseFactory(new ResponseFactory());
AppFactory::setStreamFactory(new StreamFactory());
AppFactory::setContainer($container);


$app = AppFactory::create();
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

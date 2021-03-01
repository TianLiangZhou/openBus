<?php
declare(strict_types=1);

use App\Http\Middleware\Cross;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = include __DIR__  . '/container.php';

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
$app->get('/api/apps', 'api.controller:apps');
return $app;

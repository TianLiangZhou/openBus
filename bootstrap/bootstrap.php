<?php
define('APP_PATH', dirname(__DIR__));
define('APP_CONFIG', APP_PATH . '/config');
define('APP_STORAGE', APP_PATH . '/storage');
define('TODAY_TIME', strtotime('today'));
define('CURRENT_TIME', time());
require APP_PATH . '/vendor/autoload.php';
$container = new Slim\Container([
    'settings' => [
        'displayErrorDetails' => false,
        'routerCacheDisabled' => true, //开启路由缓存
        'routerCacheFile' => APP_STORAGE . '/route.json',
        'logger' => [
            'name' => 'gj-app',
            'level' => Monolog\Logger::DEBUG,
            'path' => APP_STORAGE . '/logs/' . PHP_SAPI . '-' . date('Ymd') . '.log',
        ],
        'errorHandler' => function($c) {
            return function(Slim\Http\Request $request, Slim\Http\Response $response, \Exception $e) use ($c) {
                $c->get('logger')->info($e->getMessage());
                $response->getBody()->write('success');
                return $response;
            };
        },
    ],
    'config' => require APP_CONFIG . '/app.php'
]);
if ($container->get('config')['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
$container->register(new App\Providers\MonologProvider());
$container->register(new App\Providers\SubscriberProvider());
$app = new Slim\App($container);
require APP_PATH . '/app/Http/routes.php';
return $app;
<?php
define('APP_PATH', dirname(__DIR__));
define('APP_CONFIG', APP_PATH . '/config');
define('APP_STORAGE', APP_PATH . '/storage');
define('TODAY_TIME', strtotime('today'));
define('CURRENT_TIME', time());
require APP_PATH . '/vendor/autoload.php';

use App\Providers\DispatcherProvider;
use App\Providers\MonologProvider;
use Dotenv\Dotenv;
use Interop\Container\Exception\ContainerException;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

(new Dotenv(APP_PATH))->load();

$config = require __DIR__ . '/../config/app.php';

$setting = [
    'settings' => [
        'displayErrorDetails' => $config['debug'] ?? true,
        'routerCacheDisabled' => !$config['debug'] ?? false, //开启路由缓存
        'routerCacheFile' => __DIR__ . '/../storage/route.json',
        'logger' => [
            'name' => getenv('APP_NAME'),
            'level' => Monolog\Logger::DEBUG,
            'path' => __DIR__ . '/../storage/logs/' . PHP_SAPI . '-' . date('Ymd') . '.log',
        ],
        'errorHandler' => function(ContainerInterface $c) {
            return function(Request $request, Response $response, \Exception $e) use ($c) {
                $c->get('logger')->info($e->getMessage());
                $response->getBody()->write('success');
                return $response;
            };
        },
    ],
    'config' => $config
];
$container = new Container($setting);

try {
    if ($config['debug']) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
} catch (ContainerException $e) {
}
$container->register(new MonologProvider());
$container->register(new DispatcherProvider());
$app = new App($container);
require __DIR__ . '/../routes/api.php';
return $app;

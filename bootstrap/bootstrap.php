<?php
define('APP_PATH', dirname(__DIR__));
define('APP_CONFIG', APP_PATH . '/config');
define('APP_STORAGE', APP_PATH . '/storage');
define('TODAY_TIME', strtotime('today'));
define('CURRENT_TIME', time());
require APP_PATH . '/vendor/autoload.php';

use App\Http\Controllers\BusController;
use DI\ContainerBuilder;
use DI\Definition\ValueDefinition;
use Dotenv\Dotenv;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\StreamFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

(new Dotenv(APP_PATH))->load();

$config = require __DIR__ . '/../config/app.php';

$builder= new ContainerBuilder();
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
$container->set('config', $config);
$container->set('logger', new ValueDefinition(function () {
    $logger = new Logger(getenv('APP_NAME'));
    $logger->pushHandler(new StreamHandler(
        __DIR__ . '/../storage/logs/' . PHP_SAPI . '-' . date('Ymd') . '.log',
        Monolog\Logger::DEBUG,
    ));
    return $logger;
}));


AppFactory::setResponseFactory(new ResponseFactory());
AppFactory::setStreamFactory(new StreamFactory());
AppFactory::setContainer($container);


$app = AppFactory::create();
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
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
$app->any('/message', BusController::class . ':receive');
return $app;

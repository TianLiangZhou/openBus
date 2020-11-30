<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Http\Controllers\BusController;
use App\Support\Env;
use DI\ContainerBuilder;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\StreamFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;


$dotenv = Dotenv\Dotenv::create(Env::getRepository(), __DIR__ . '/../');
$dotenv->safeLoad();

$config = require __DIR__ . '/../config/app.php';

$builder= new ContainerBuilder();
$builder->addDefinitions([
    'config' => $config,
    'logger' => function (\Psr\Container\ContainerInterface $container) {
        $logger = new Logger($_ENV['APP_NAME']);
        $logger->pushHandler(new StreamHandler(
            __DIR__ . '/../storage/logs/' . PHP_SAPI . '-' . date('Ymd') . '.log',
            Monolog\Logger::DEBUG,
        ));
        return $logger;
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

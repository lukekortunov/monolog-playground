<?php

use App\Controller\TestController;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Strategy\JsonStrategy;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\BufferHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use NewRelic\Monolog\Enricher\Handler as NewRelicHandler;
use NewRelic\Monolog\Enricher\Processor as NewRelicProcessor;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotEnv = new Dotenv();
$dotEnv->load(__DIR__ . '/../.env', __DIR__ . '/../.env.local');

$request = ServerRequestFactory::fromGlobals();

$container = new League\Container\Container();

$container
    ->add(TestController::class)
    ->addArgument(LoggerInterface::class);

$newRelicHandler = new NewRelicHandler(Logger::INFO);
$newRelicHandler->setLicenseKey($_ENV['NEWRELIC_LICENSE_KEY']);

$container
    ->add(LoggerInterface::class, Logger::class)
    ->addArgument('app')
    ->addMethodCall('pushHandler', [new StreamHandler(__DIR__ . '/../var/log/app.log')])
    ->addMethodCall('pushHandler', [new BufferHandler($newRelicHandler)])
    ->addMethodCall('pushProcessor', [new NewRelicProcessor()])
;

$responseFactory = new ResponseFactory();

$strategy = (new JsonStrategy($responseFactory));
$strategy->setContainer($container);

$router = (new League\Route\Router)->setStrategy($strategy);

$router->map('GET', '/', App\Controller\TestController::class);

$response = $router->dispatch($request);

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

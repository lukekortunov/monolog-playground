<?php

use App\Controller\TestController;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Strategy\JsonStrategy;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$request = ServerRequestFactory::fromGlobals();

$container = new League\Container\Container();

$container
    ->add(TestController::class)
    ->addArgument(LoggerInterface::class);

$container
    ->add(LoggerInterface::class, Logger::class)
    ->addArgument('app')
    ->addMethodCall('pushHandler', [new StreamHandler(__DIR__ . '/../var/log/app.log')]);

$responseFactory = new ResponseFactory();

$strategy = (new JsonStrategy($responseFactory));
$strategy->setContainer($container);

$router = (new League\Route\Router)->setStrategy($strategy);

$router->map('GET', '/', App\Controller\TestController::class);

$response = $router->dispatch($request);

(new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);

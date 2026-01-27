<?php

define('ROOT_PATH', dirname(__DIR__));
require ROOT_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;
use DI\ContainerBuilder;
use App\Infrastructure\Routing\Router;
use App\Infrastructure\Http\WelcomeController;
use App\Infrastructure\Http\ArticleController;
use App\Infrastructure\Http\CategoryController;

$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(require ROOT_PATH . '/config/dependencies.php');
$container = $containerBuilder->build();

$router = new Router($container);

$router->addRoute('GET', '/', [WelcomeController::class, 'index']);
$router->addRoute('GET', '/category/{id}', [CategoryController::class, 'show']);
$router->addRoute('GET', '/article/{id}', [ArticleController::class, 'show']);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    http_response_code((int)$e->getCode());
    echo $e->getMessage();
}
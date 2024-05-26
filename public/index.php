<?php

require '../vendor/autoload.php';

date_default_timezone_set('America/Sao_Paulo');

use App\Middleware\AuthMiddleware;
use App\Router\Route\Route;
use App\Router\Router;
use App\Utils\DotEnvUtil;

$path = dirname(__FILE__, 2);

DotEnvUtil::loadEnv($path."/.env");

$routeFactory = new Route();
$router = new Router($routeFactory);

include '../routes/web.php';


$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

//entry point da aplicaçao
$router->route($method, $path);


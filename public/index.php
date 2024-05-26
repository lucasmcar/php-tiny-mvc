<?php

require '../vendor/autoload.php';

ini_set('display_errors',1);
  ini_set('display_startup_erros',1);
  error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');

use App\Router\Route;
use App\Router\Router;
use App\Utils\DotEnvUtil;



$path = dirname(__FILE__, 2);

DotEnvUtil::loadEnv($path."/.env");

//entry point da aplicaçao
$router = new Router();

include '../routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

//entry point da aplicaçao
$router->route($method, $path);

//Routes::get('/', 'testeController', 'teste');
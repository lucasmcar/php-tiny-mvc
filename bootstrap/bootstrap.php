<?php
define('PROJECT_VIEW_PATH', dirname(__DIR__) . '/resources/views');
require_once __DIR__ . '/../vendor/autoload.php';


use App\Router\Route\Route;
use App\Router\Router;
use App\Utils\DotEnvUtil;

date_default_timezone_set('America/Sao_Paulo');

// Carregar variáveis de ambiente
DotEnvUtil::loadEnv(dirname(__DIR__) . '/.env');

// Instanciar as rotas e o roteador
$routeFactory = new Route();
$router = new Router($routeFactory);

// Incluir as definições de rotas
require_once __DIR__ . '/../routes/web.php';
require_once __DIR__ . '/../routes/api.php';

return $router;
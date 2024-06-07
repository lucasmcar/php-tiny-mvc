<?php

use App\Middleware\AuthMiddleware;

$router->use(AuthMiddleware::class);

$router->group('/api', function($router){
    $router->get('/v1', 'ApiController', 'api');
},[AuthMiddleware::class]);
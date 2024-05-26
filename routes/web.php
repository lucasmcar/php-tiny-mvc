<?php

use App\Middleware\AuthMiddleware;

$router->use(AuthMiddleware::class);
//básicas
//$router->get('/', 'HomeController@index');
$router->get('/', 'HomeController', 'welcome');
//$router->get('/list', 'HomeController@list');
//$router->post('/submit', 'HomeController@submit');


$router->notFound(function(){
    include '../App/views/not-found/not-found.tpl';
});

$router->group('/admin', function($router) {
    $router->get('/list', 'HomeController', 'list', [AuthMiddleware::class]);
    $router->get('/settings', 'HomeController','settings');
    $router->get('/teste/{id}', 'HomeController','teste');
    $router->get('/teste/{id}/p/{postId?}/{c?}/{commentId?}', 'HomeController','showPost');
});


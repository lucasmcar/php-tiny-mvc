<?php

//básicas
//$router->get('/', 'HomeController@index');
$router->get('/', 'HomeController@welcome');
//$router->get('/list', 'HomeController@list');
//$router->post('/submit', 'HomeController@submit');


$router->notFound(function(){
    include '../App/Views/not-found/not-found.php';
});


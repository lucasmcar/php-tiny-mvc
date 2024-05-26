<?php 

namespace App\Router\Interface;

interface IRoute
{
    public function add($method, $path, $controller, $action = '');
    public function match($method, $path);
} 
<?php

namespace App\Router\Interface;

interface IRouter
{
    //public function group($attributes, $callback);
    public function get(string $path, mixed $controller, string $action = '');
    public function post(string $path, mixed $controller, string $action);
    public function put(string $path, mixed $controller, string $action);
    public function delete(string $path, mixed $controller, string $action);
}
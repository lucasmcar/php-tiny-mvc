<?php 

namespace App\Router\Route;

use App\Router\Interface\IRoute;

class Route implements IRoute
{
    private $method;
    private $path;
    private $controller;
    private $action;

    public function __construct($method = '', $path = '', $controller = '', $action = '')
    {
        $this->method = $method;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
    }

    public function add($method, $path, $controller, $action = '')
    {
        return new self($method, $path, $controller, $action);
    }

    public function match($method, $path)
    {
        $routePattern = preg_replace('/\{(\w+)\?\}/', '(\w+)?', str_replace('/', '\/', $this->path));
        $routePattern = preg_replace('/\{(\w+)\}/', '(\w+)', $routePattern);
        $routePattern = '/^' . $routePattern . '$/';

        if ($this->method === $method && preg_match($routePattern, $path, $matches)) {
            array_shift($matches); // Remove the first item which is the full URL
            return [
                'controller' => $this->controller,
                'action' => $this->action,
                'params' => $matches
            ];
        }
        return false;
    }
}
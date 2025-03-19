<?php

namespace App\Router;


use App\Router\Interface\IRouter;
use App\Router\Interface\IRoute;

class Router  implements IRouter 
{
    private $routes = [];
    private $notFoundCallback;
    private $prefix = '';
    private $route;
    private $globalMiddleware = [];
    private $routeMiddleware = [];
    private $groupMiddleware = [];
    private $path;
    private $method;

    public function __construct(IRoute $route)
    {
        $this->route = $route;
    }

    public function get($path, $controller, $action = '' , $middleware = [])
    {
        $this->addRoute('GET', $path, $controller, $action, $middleware);
    }

    public function post($path, $controller, $action = '', $middleware = [])
    {
        $this->addRoute('POST', $path, $controller, $action, $middleware);
    }

    public function put($path, $controller, $action = '', $middleware = [])
    {
        $this->addRoute('PUT', $path, $controller, $action, $middleware);
    }

    public function delete($path, $controller, $action = '', $middleware = [])
    {
        $this->addRoute('DELETE', $path, $controller, $action, $middleware);
    }

    public function group($prefix, $callback, $middleware = [])
    {
        $previousPrefix = $this->prefix;
        $previousGroupMiddleware = $this->groupMiddleware;
        
        $this->prefix .= $prefix;
        $this->groupMiddleware = array_merge($this->groupMiddleware, $middleware);

        call_user_func($callback, $this);

        $this->prefix = $previousPrefix;
        $this->groupMiddleware = $previousGroupMiddleware;
        /*$previousPrefix = $this->prefix;
        $this->prefix .= $prefix;

        call_user_func($callback, $this);

        $this->prefix = $previousPrefix;*/
    }

    private function addRoute($method, $path, $controller, $action = '', $middleware = [])
    {
        $route = $this->route->add($method, $this->prefix . urldecode($path), $controller, $action);
        /*$this->routes[] = $route;*/
        $this->routes[] = [
            'route' => $route,
            'middleware' => array_merge($this->globalMiddleware, $this->groupMiddleware, $middleware)
        ];

    }

     /**
      * Method to define the global middleware
      */
     public function use($middleware)
     {
         $this->globalMiddleware[] = $middleware;
     }

    public function route($method, $path)
    {
        $path = urldecode($path);

        // Logar todas as rotas registradas
        foreach ($this->routes as $idx => $route) {
            $routePath = $route['route']->getPath(); // Adicione este método em Route se necessário
            error_log("Rota registrada [{$idx}]: Método: {$route['route']->getMethod()} | Caminho: {$routePath}");
        }


        foreach ($this->routes as $route) {
            $match = $route['route']->match($method, $path);
            
            if ($match) {
                $this->executeMiddleware($route['middleware'], function () use ($match) {
                    $controllerClass = "App\\Controller\\" . ucfirst($match['controller']);
                    $controller = new $controllerClass();
                    call_user_func([$controller, $match['action']], $match['params']);
                });
                return;
            }
        }

        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        } else {
            http_response_code(404);
        }
    }

    public function run()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];

        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = dirname($scriptName);

        if ($basePath === '/' || $basePath === '\\') {
            $basePath = ''; // Raiz do servidor
        }
    
        // Remover o caminho base do REQUEST_URI
        if ($basePath && strpos($this->path, $basePath) === 0) {
            $this->path = substr($this->path, strlen($basePath));
        }
    
        // Garantir que o caminho comece com /
        if (empty($this->path) || $this->path[0] !== '/') {
            $this->path = '/' . ltrim($this->path, '/');
        }
        $this->route($this->method, $this->path);
    }


    private function executeMiddleware($middleware, $next)
    {
        $stack = array_reverse($middleware);
        $nextMiddleware = $next;

        foreach ($stack as $layer) {
            $currentMiddleware = new $layer();
            $nextMiddleware = function ($request) use ($currentMiddleware, $nextMiddleware) {
                return $currentMiddleware->handle($request, $nextMiddleware);
            };
        }

        return $nextMiddleware([]);
    }



    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }
}
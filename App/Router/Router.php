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

    public function group($prefix, $callback)
    {
        $previousPrefix = $this->prefix;
        $this->prefix .= $prefix;

        call_user_func($callback, $this);

        $this->prefix = $previousPrefix;
    }

    private function addRoute($method, $path, $controller, $action = '', $middleware = [])
    {
        $route = $this->route->add($method, $this->prefix . urldecode($path), $controller, $action);
        /*$this->routes[] = $route;*/
        $this->routes[] = [
            'route' => $route,
            'middleware' => array_merge($this->globalMiddleware, $middleware)
        ];

    }

     // Método para definir middleware global
     public function use($middleware)
     {
         $this->globalMiddleware[] = $middleware;
     }

    public function route($method, $path)
    {
        $path = urldecode($path);
        foreach ($this->routes as $route) {
            $match = $route['route']->match($method, $path);
            if ($match) {
                $this->executeMiddleware($route['middleware'], function () use ($match) {
                    $controllerClass = "App\\Controller\\" . ucfirst($match['controller']);
                    $controller = new $controllerClass();
                    call_user_func_array([$controller, $match['action']], $match['params']);
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






/*class Router implements IRouter
{

    private $routes = [];
    private $notFoundCallback;
    private $prefix = '';
   
    
    // Adiciona uma rota para o método GET
    public function get($path, $controller, $action = '') 
    {
        $this->addRoute('GET', urldecode($path), $controller, $action);
    }
    
    // Adiciona uma rota para o método POST
    public function post($path, $controller, $action = '') 
    {
        $this->addRoute('POST', $path, $controller, $action);
    }
    
        // Adiciona uma rota para o método PUT
    public function put($path, $controller, $action = '') 
    {
        $this->addRoute('PUT', $path, $controller, $action);
    }
    
    // Adiciona uma rota para o método DELETE
    public function delete($path, $controller, $action = '') 
    {
        $this->addRoute('DELETE', $path, $controller, $action);
    }

     // Método para agrupar rotas
     public function group($prefix, $callback) 
     {
         $previousPrefix = $this->prefix;
         $this->prefix .= $prefix;
 
         // Chama o callback para definir as rotas dentro do grupo
         call_user_func($callback, $this);
 
         // Restaura o prefixo anterior
         $this->prefix = $previousPrefix;
     }

    // Método interno para adicionar uma rota
    private function addRoute(string $method, string $path, string $controller, string $action = '') {
        if($action == ''){
            $controller = explode("@", $controller);
            $this->routes[] = [
                'method' => $method,
                'path' => $this->prefix. urldecode($path),
                'controller' => $controller[0],
                'action' => $controller[1]
            ];
            return;
        } 
        $this->routes[] = [
            'method' => $method,
            'path' =>  urldecode($path),
            'controller' => $controller,
            'action' => $action
        ];
        return;
    }
    
    // Encontra e chama a ação correta para a rota especificada
    public function route($method, $path) 
    {

        $path = urldecode($path);
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $routePath = $route['path'];

                $routePattern = preg_replace('/\{(\w+)\?\}/', '(\w+)?', str_replace('/', '\/', $routePath));
                $routePattern = preg_replace('/\{(\w+)\}/', '(\w+)', $routePattern);
                $routePattern = '/^' . $routePattern . '$/';

                if (preg_match($routePattern, $path, $matches)) {
                    array_shift($matches); // Remove o primeiro item, que é a URL completa

                    $params = [];
                    preg_match_all('/\{(\w+)\?\}|\{(\w+)\}/', $routePath, $paramNames);
                    if (isset($paramNames[0])) {
                        foreach ($paramNames[0] as $index => $paramName) {
                            $params[] = $matches[$index] ?? null;
                        }
                    }

                    $class = "App\\Controller\\" . ucfirst($route['controller']);
                    $action = $route['action'];
                    $controller = new $class();
                    call_user_func_array([$controller, $action], $params);
                    return;
                }
            }
        }

        if($this->notFoundCallback){
            call_user_func($this->notFoundCallback);
        } else {
            http_response_code(404);
        }
    }

    private function convertPathToRegex($path)
    {
        // Convert route path to regex
        return '/^' . preg_replace('/\{(\w+)\}/', '(\w+)', str_replace('/', '\/', $path)) . '$/';
    }

    public function notFound($callBack)
    {
        return $this->notFoundCallback = $callBack;
    }
}*/

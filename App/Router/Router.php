<?php

namespace App\Router;


use App\Router\Interface\IRouter;

class Router implements IRouter
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

        $filtro = null;
        $path = urldecode($path);
        foreach ($this->routes as $route) {

            
            //faz contagem das barras na url
            if(substr_count($path, "/")> 2 && substr_count($route['path'], "/")> 2){

                $part = explode("/", $path);
                $pathRequest = explode("/", $route['path']);
                if($part[1] == $pathRequest[1]){
                    $pathRequest[3] = $part[3];
                    $filtro = $pathRequest[3];
                    $path = $route['path'] = implode("/", $pathRequest);
                }
                
            }
           
            if ($route['method'] === $method && $route['path'] === $path) {
                $class = "App\\Controller\\".ucfirst($route['controller']);
                $action = $route['action'];
                // Instancia o controlador e chama a ação
                $controller = new $class();
                $controller->$action($filtro);
                return;
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
}

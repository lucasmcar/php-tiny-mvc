<?php 

namespace App\Router\Route;

use App\Router\Interface\IRoute;

class Route implements IRoute
{
    private $method;
    private $path;
    private $controller;
    private $action;
    private $domain;

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
        $routePattern = preg_replace('/\{(\w+)\}/', '([\w-]+)', $routePattern);
        $routePattern = '/^' . $routePattern . '$/';

        // Remove query string da comparação do path
        $cleanPath = parse_url($path, PHP_URL_PATH);

        if ($this->method === $method && preg_match($routePattern, $cleanPath, $matches)) {
            array_shift($matches); // Remove the first item which is the full URL
            
            $params = $matches;
            if (!empty($_GET)) {
                $params = array_merge($params, $_GET);
            }

            return [
                'controller' => $this->controller,
                'action' => $this->action,
                'params' => $params
            ];
        }
        return false;
    }

        // Adicione este método em Route.php se não existir
        public function getPath()
        {
            return $this->path;
        }

        public function getMethod()
        {
            return $this->method;
        }

        public function setDomain($domain)
        {
            $this->domain = $domain;
        }

        public function getDomain()
        {
            return $this->domain;
        }
}
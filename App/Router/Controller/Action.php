<?php

namespace App\Router\Controller;

abstract class Action
{
    protected $view;
    
    public function __construct() 
    {
        $this->view = new \stdClass();
    }
    
    /*Method that renders a view dinamically*/
    protected function render($view, $layout = 'layout')
    {
        
        $this->view->attr = $view;
        
        if(file_exists('../App/Views/'.$layout.'.php'))
        {
            require_once '../App/Views/'.$layout.'.php';
        }
        else
        {
            $this->content();
        }
    }
    
    protected function content()
    {
        //Pega o nome da classe com seu caminho completo
        $currentClass = get_class($this);
        
        //Extrai a string inicial, retornando apenas o nome da classe
        $currentClass = str_replace('App\\Controller\\', '', $currentClass);
        
        $currentClass = strtolower(str_replace('Controller', '', $currentClass));

        
        require_once '../App/Views/'.$currentClass.'/'.$this->view->attr.'.php';

        
 
    }
} 
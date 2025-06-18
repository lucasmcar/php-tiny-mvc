<?php

namespace App\Controller;

use Core\View\View;


class ExampleController
{

    /**
     * Este metodo retorna uma view
     */

    public function index()
    {
        //Dados que vão ser renderizados na view
        $data = [
            'titulo' => 'Bem vindo ao Tiny MVC',
            'descricao' => 'Um pequeno framework baseado em Laravel'
        ];

        //Estilos que serão chamados na própria view, não é estilo global
        $styles = [
            '/assets/css/estilo.css'
        ];

        //Scripts da view
        $scripts = [
            'assets/js/functions.js'
        ];

        /**/
        return new View(view:'welcome-tiny', vars: $data, styles: $styles, scripts: $scripts);
    }

    public function admin()
    {
        //Dados que vão ser renderizados na view
        $data = [
            'titulo' => 'Admin Bem vindo ao Tiny MVC',
            'descricao' => 'Um pequeno framework baseado em Laravel'
        ];

        //Estilos que serão chamados na própria view, não é estilo global
        $styles = [
            '/assets/css/estilo.css'
        ];

        //Scripts da view
        $scripts = [
            'assets/js/functions.js'
        ];

        /**/
        return new View(view:'admin/index', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin/admin');
    }

}
<?php


namespace App\Controller;

use Core\View\View;


class ClienteController
{
    public function index()
    {
        $data = [
            'title' => 'Gerenciar Clientes',
        ];

        $styles = [
            '/assets/css/admin/clientes.min.css'
        ];
        $scripts = [
            '/assets/js/clientes.min.js'
        ];

        return new View(view: 'admin/clientes', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }
}
<?php


namespace App\Controller;

use App\Core\View\View;


class ClienteController
{
    public function index()
    {
        $data = [
            'title' => 'Gerenciar Clientes',
        ];

        $styles = [
            '/assets/css/admin/clientes.css'
        ];
        $scripts = [
            '/assets/js/clientes.js'
        ];

        return new View(view: 'admin/clientes', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }
}
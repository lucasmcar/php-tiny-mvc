<?php

namespace App\Controller;

use App\Core\View\View;

class PerfilController
{
    public function perfil()
    {
        // Dados simulados do administrador (substitua por banco de dados real)
        $adminData = [
            'nome_exibicao' => 'João Silva',
            'email' => 'joao@empresa.com',
            'foto_perfil' => '/assets/images/default-profile.jpg',
            'admins' => [
                ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao@empresa.com'],
                ['id' => 2, 'nome' => 'Maria Oliveira', 'email' => 'maria@empresa.com']
            ],
            'emails_empresa' => [
                ['id' => 1, 'email' => 'contato@empresa.com'],
                ['id' => 2, 'email' => 'suporte@empresa.com']
            ]
        ];

        $styles = ['/assets/css/perfil.css'];
        $scripts = ['/assets/js/perfil.js'];

        return new View('admin/perfil', [
            'adminData' => $adminData
        ], $styles, $scripts, 'admin-layout');
    }
}
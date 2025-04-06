<?php

namespace App\Controller;

use App\Core\View\View;

class ParceiroController
{
    public function todosParceiros($pagina = 1)
    {

        $parceiros = [
            ['id' => 1, 'nome' => 'João Silva', 'tipo' => 'Pessoa', 'email' => 'joao@exemplo.com', 'telefone' => '(11) 99999-9999'],
            ['id' => 2, 'nome' => 'TechCorp', 'tipo' => 'Empresa', 'email' => 'contato@techcorp.com', 'telefone' => '(21) 88888-8888'],
            ['id' => 3, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 4, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 5, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 6, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 7, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 8, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 9, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 10, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 11, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 12, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
            ['id' => 13, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777'],
        ];



        $totalParceiros = count($parceiros);
        $itensPorPagina = 10;

        // Pegar página via $_GET ou usar o padrão (1)
        $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : (int)$pagina;
        $totalPaginas = ceil($totalParceiros / $itensPorPagina);

        // Garantir que a página atual esteja dentro dos limites
        $paginaAtual = max(1, min($paginaAtual, $totalPaginas));

        // Calcular offset e pegar depoimentos da página atual
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $parceirosPagina = array_slice($parceiros, $offset, $itensPorPagina);
        // Simulação de dados (substitua pelo seu banco de dados)


        $styles = ['/assets/css/parceiros.css'];
        $scripts = ['/assets/js/main-admin.js', '/assets/js/parceiros.js'];

        $data = [
            'title' => 'Gerenciar Parceiros',
            'parceiros' => $parceirosPagina,
            'paginaAtual' => $paginaAtual,
            'totalPaginas' => $totalPaginas,
            'totalParceiros' => $totalParceiros
        ];

        return new View('admin/parceiros', $data, $styles, $scripts, 'admin-layout');
    }
}

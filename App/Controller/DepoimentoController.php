<?php

namespace App\Controller;

use App\Helper\InputFilterHelper;
use App\Model\Depoimento;
use App\Repository\DepoimentoRepository;
use Core\View\View;

class DepoimentoController
{
    public function criar()
    {
        $data = InputFilterHelper::filterInputs(INPUT_POST, [
            'nome',
            'profissao',
            'titulo',
            'depoimento'
        ]);

        $depoimento = new Depoimento();

        $depoimento->create([
            'nome' => $data['nome'],
            'profissao' => $data['profissao'],
            'titulo' => $data['titulo'],
            'depoimento' => $data['depoimento']
        ]);

        


        header('location: /depoimentos/todos');
    }

    public function todosDepoimentos($pagina = 1 )
    {

        // Simulação de dados (substitua por dados reais do banco, se houver)
        $depoimentos = [];
        for ($i = 1; $i <= 15; $i++) { // 15 depoimentos para testar paginação
            $depoimentos[] = [
                'id' => $i,
                'foto' => '/assets/imgs/user' . ($i % 3 + 1) . '.jpg', // Cicla entre 3 imagens
                'nome' => 'Usuário ' . $i,
                'titulo' => 'Título ' . $i,
                'depoimento' => 'Este é o depoimento número ' . $i . ' da HD Arte.'
            ];
        }


        $totalDepoimentos = count($depoimentos);
        $itensPorPagina = 10;

        // Pegar página via $_GET ou usar o padrão (1)
        $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : (int)$pagina;
        $totalPaginas = ceil($totalDepoimentos / $itensPorPagina);

        // Garantir que a página atual esteja dentro dos limites
        $paginaAtual = max(1, min($paginaAtual, $totalPaginas));

        // Calcular offset e pegar depoimentos da página atual
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $depoimentosPagina = array_slice($depoimentos, $offset, $itensPorPagina);

        $data = [
            'title' => 'Todos os Depoimentos',
            'depoimentos' => $depoimentosPagina,
            'paginaAtual' => $paginaAtual,
            'totalPaginas' => $totalPaginas,
            'totalDepoimentos' => $totalDepoimentos
        ];
        
        $styles = ['/assets/css/depoimentos.min.css'];
        $scripts = [
            '/assets/js/depoimento.min.js',
            'assets/js/main-admin.min.js'
        ];

        return new View('admin/depoimentos', $data, $styles, $scripts, 'admin-layout');
    
    }

    public function gerenciarDepoimentos($pagina = 1)
    {
        // Simulação de dados
        $depoimentosPendentes = [
            ['id' => 1, 'nome' => 'João Silva', 'titulo' => 'Cliente', 'depoimento' => 'Evento incrível!', 'data' => '2025-03-20', 'status' => 'Pendente'],
            ['id' => 2, 'nome' => 'Maria Oliveira', 'titulo' => 'Parceira', 'depoimento' => 'Muito profissional!', 'data' => '2025-03-21', 'status' => 'Pendente']
        ];
        $projetos = [
            ['id' => 1, 'nome' => 'Evento Cultural', 'status' => 'Em andamento', 'data_fim' => null],
            ['id' => 2, 'nome' => 'Festival de Arte', 'status' => 'Finalizado', 'data_fim' => '2025-03-15']
        ];

        $totalPendentes = count($depoimentosPendentes);
        $itensPorPagina = 10;
        $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : (int)$pagina;
        $totalPaginas = ceil($totalPendentes / $itensPorPagina);
        $paginaAtual = max(1, min($paginaAtual, $totalPaginas));
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $depoimentosPagina = array_slice($depoimentosPendentes, $offset, $itensPorPagina);

        $notificarAdmin = false; // Simulação (poderia vir de BD)
        $emailAdmin = ''; // Simulação (poderia vir de BD)

        $styles = ['/assets/css/gerenciar-depoimentos.min.css'];
        $scripts = ['/assets/js/gerenciar-depoimentos.min.js', '/assets/js/main-admin.min.js'];

     
        return new View('admin/gerenciar-depoimentos', [
            'title' => 'Gerenciar Depoimentos',
            'depoimentos' => $depoimentosPagina,
            'totalPendentes' => $totalPendentes,
            'paginaAtual' => $paginaAtual,
            'totalPaginas' => $totalPaginas,
            'projetos' => $projetos,
            'notificarAdmin' => $notificarAdmin,
            'emailAdmin' => $emailAdmin
        ], $styles, $scripts, 'admin-layout');
    
    }

    public function listarDepoimentos($pagina = 1)
    {

        ob_start(); // Inicia buffer para capturar saídas indesejadas
        header('Content-Type: application/json');
        session_start();
        $depoimentos = [];
        for ($i = 1; $i <= 15; $i++) { // 15 depoimentos para testar paginação
            $depoimentos[] = [
                'id' => $i,
                'foto' => '/assets/imgs/user' . ($i % 3 + 1) . '.jpg', // Cicla entre 3 imagens
                'nome' => 'Usuário ' . $i,
                'titulo' => 'Título ' . $i,
                'depoimento' => 'Este é o depoimento número ' . $i . ' da HD Arte.'
            ];
        }


        $totalDepoimentos = count($depoimentos);
        $itensPorPagina = 10;

        // Pegar página via $_GET ou usar o padrão (1)
        $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : (int)$pagina;
        $totalPaginas = ceil($totalDepoimentos / $itensPorPagina);

        // Garantir que a página atual esteja dentro dos limites
        $paginaAtual = max(1, min($paginaAtual, $totalPaginas));

        // Calcular offset e pegar depoimentos da página atual
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $depoimentosPagina = array_slice($depoimentos, $offset, $itensPorPagina);

        $response = [
            
            'depoimentos' => $depoimentosPagina,
            'paginaAtual' => $paginaAtual,
            'totalPaginas' => $totalPaginas,
            'totalDepoimentos' => $totalDepoimentos
        ];
        ob_end_clean();
        echo json_encode($response);
        exit;
    }
}
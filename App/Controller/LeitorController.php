<?php

namespace App\Controller;

use Core\View\View;
use Core\Security\Jwt\JwtHandler;
use App\Model\Usuario;

class LeitorController
{
    public function verHistorico()
    {
        // Simulação de dados fictícios para o histórico de aluguéis
        $historico = [
            [
                'livro_titulo' => 'O Senhor dos Anéis',
                'data_retirada' => '2025-05-01',
                'data_entrega' => '2025-05-10',
                'tempo_leitura' => 9,
                'vezes_alugado' => 3,
                'devolvido_prazo' => true
            ],
            [
                'livro_titulo' => 'A Arte de Viver',
                'data_retirada' => '2025-04-15',
                'data_entrega' => '2025-05-25',
                'tempo_leitura' => 10,
                'vezes_alugado' => 2,
                'devolvido_prazo' => false
            ],
            [
                'livro_titulo' => 'História do Brasil',
                'data_retirada' => '2025-03-20',
                'data_entrega' => '2025-03-30',
                'tempo_leitura' => 10,
                'vezes_alugado' => 1,
                'devolvido_prazo' => true
            ]
        ];

        // Dados do usuário autenticado (simulado via JWT)
        $dados = [];
        if (session_id()) {
            $dados = JwtHandler::validateToken($_SESSION['jwt']);
            $usuario = new Usuario();
        }
        $usuarioDado = $usuario->findForSign($dados['email']);

        $data = [
            'titulo' => "Histórico de Aluguéis, " . $usuarioDado[0]['usuario'],
            'subtitulo' => 'Veja o histórico completo dos seus aluguéis.',
            'historico' => $historico
        ];

        $styles = [
            '/assets/css/admin/alugueis/leitor-historico.min.css'
        ];

        return new View(view: 'admin/alugueis/leitor-historico', vars: $data, styles: $styles, layout: 'admin-layout');
    }

    public function verAtivos()
    {
        // Simulação de dados fictícios para aluguéis ativos
        $alugueis = [
            [
                'id' => 1,
                'livro_titulo' => 'O Senhor dos Anéis',
                'biblioteca_nome' => 'Biblioteca do João',
                'valor_pago' => 15.00,
                'data_retirada' => '2025-06-01',
                'tempo_leitura' => 5 // Dias desde a retirada até hoje (06/06/2025)
            ],
            [
                'id' => 2,
                'livro_titulo' => 'A Arte de Viver',
                'biblioteca_nome' => 'Estante da Maria',
                'valor_pago' => 10.00,
                'data_retirada' => date('d/m/Y', strtotime('2025-06-03')),
                'tempo_leitura' => 3
            ],
            [
                'id' => 3,
                'livro_titulo' => 'História do Brasil',
                'biblioteca_nome' => 'Acervo do Pedro',
                'valor_pago' => 12.50,
                'data_retirada' => '2025-06-05',
                'tempo_leitura' => 1
            ]
        ];

        // Dados do usuário autenticado (simulado via JWT)
        $dados = [];
        if (session_id()) {
            $dados = JwtHandler::validateToken($_SESSION['jwt']);
            $usuario = new Usuario();
        }
        $usuarioDado = $usuario->findForSign($dados['email']);

        $data = [
            'titulo' => "Seus Aluguéis Ativos, " . $usuarioDado[0]['usuario'],
            'subtitulo' => 'Veja os livros que você está alugando atualmente.',
            'alugueis' => $alugueis
        ];

        $styles = [
            '/assets/css/admin/alugueis/leitor-ativos.min.css'
        ];

        $scripts = [
            '/assets/js/leitor-ativos.min.js'
        ];

        return new View(view: 'admin/alugueis/leitor-ativos', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }
}

<?php

namespace App\Controller;

use Core\View\View;
use App\Helper\InputFilterHelper;
use App\Helper\JsonHelper;
use Core\Security\Jwt\JwtHandler;
use Core\Security\Csrf;
use App\Model\Usuario;
use App\Model\Biblioteca;

class BibliotecaController
{

    public function telaCadastroBiblioteca()
    {
        $data = [
            'titulo' => 'Cadastrar Nova Biblioteca',
            'subtitulo' => 'Registre sua coleção de livros com a BiblioGo',
        ];


        $styles = [
            '/assets/css/admin/cadastro-biblioteca.min.css'
        ];
        $scripts = [
            'assets/js/cadastro-biblioteca.min.js'
        ];

        return new View(
            view: 'admin/biblioteca/cadastro-biblioteca',
            vars: $data,
            styles: $styles,
            scripts: $scripts,
            layout: 'admin-layout-dono'
        );
    }

    public function minhaBiblioteca() {}

    public function registrarBiblioteca()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];
            $csrfToken = $data['_csrf_token'] ?? '';

            if (!Csrf::verifyToken($csrfToken)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Token CSRF inválido.'
                ], 403);
            }

            // Verifica se o usuário já tem uma biblioteca
            //$donoRepository = new DonoRepository();
            $donoId = $_SESSION['id'] ?? null; // Supondo que o ID do dono está na sessão
            if (!$donoId) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Usuário não autenticado.'
                ], 401);
            }

            /*$bibliotecaExistente = $donoRepository->getBibliotecaByDonoId($donoId);
            if ($bibliotecaExistente) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Você já possui uma biblioteca cadastrada.'
                ], 400);
            }*/

            // Validação dos dados
            $nome = trim($data['nome'] ?? '');
            $descricao = trim($data['descricao'] ?? '');

            if (empty($nome) || strlen($nome) < 3) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'O nome da biblioteca deve ter pelo menos 3 caracteres.'
                ], 400);
            }

            if (empty($descricao) || strlen($descricao) > 500) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'A descrição deve ter no máximo 500 caracteres.'
                ], 400);
            }

            // Cria a biblioteca
            /*$bibliotecaRepository = new BibliotecaRepository();
            $bibliotecaRepository->create([
                'nome' => $nome,
                'descricao' => $descricao,
                'dono_id' => $donoId,
                'criado_em' => date('Y-m-d H:i:s')
            ]);*/

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Biblioteca cadastrada com sucesso!'
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro ao cadastrar a biblioteca: ' . $e->getMessage()
            ], 500);
        }
    }

    public function leitor()
    {
        $dados = [];
        if (session_id()) {
            $dados = JwtHandler::validateToken($_SESSION['jwt']);
            $usuario = new Usuario();
        }

        $usuarioDado = $usuario->findForSign($dados['email']);



        $data = [
            'titulo' => "Bem-vindo ao seu Dashboard, " . $usuarioDado[0]['usuario'],
            'subtitulo' => 'Gerencie seus aluguéis e acesse seu cartão virtual.'
        ];

        $styles = [
            '/assets/css/admin/dashboards/leitor.min.css'
        ];

        $scripts = [
            '/assets/js/busca-livro-leitor.min.js'
        ];

        return new View(view: 'admin/dashboard/leitor', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }

    public function buscaLivrosOuBiblioteca($params)
    {
        $slug = $params['q'] ?? '';

        // Dados fictícios para teste
        $dados = [
            'livros' => [],
            'bibliotecas' => []
        ];

        // Se houver termo de busca, filtrar dados fictícios
        if (!empty($slug)) {
            // Dados fictícios de bibliotecas
            $bibliotecas = [
                ['id' => 1, 'nome' => 'Biblioteca do João', 'descricao' => 'Coleção de livros de ficção e aventura'],
                ['id' => 2, 'nome' => 'Estante da Maria', 'descricao' => 'Livros de autoajuda e desenvolvimento pessoal'],
                ['id' => 3, 'nome' => 'Acervo do Pedro', 'descricao' => 'Focado em história e biografias']
            ];

            // Dados fictícios de livros
            $livros = [
                ['id' => 1, 'titulo' => 'O Senhor dos Anéis', 'autor' => 'J.R.R. Tolkien', 'biblioteca_nome' => 'Biblioteca do João'],
                ['id' => 2, 'titulo' => 'A Arte de Viver', 'autor' => 'Dalai Lama', 'biblioteca_nome' => 'Estante da Maria'],
                ['id' => 3, 'titulo' => 'História do Brasil', 'autor' => 'Laurentino Gomes', 'biblioteca_nome' => 'Acervo do Pedro']
            ];

            // Filtrar com base no termo de busca (simulação simples)
            $dados['bibliotecas'] = array_filter($bibliotecas, function ($item) use ($slug) {
                return stripos($item['nome'], $slug) !== false || stripos($item['descricao'], $slug) !== false;
            });

            $dados['livros'] = array_filter($livros, function ($item) use ($slug) {
                return stripos($item['titulo'], $slug) !== false || stripos($item['autor'], $slug) !== false || stripos($item['biblioteca_nome'], $slug) !== false;
            });

            // Converter para array novamente após filtragem
            $dados['bibliotecas'] = array_values($dados['bibliotecas']);
            $dados['livros'] = array_values($dados['livros']);
        }

        // Retornar como JSON
        header('Content-Type: application/json');
        echo json_encode($dados);
        exit;
    }

    public function verLivro() {}

    public function verBiblioteca() {}

    public function dono()
    {
        $dados = [];
        if (session_id()) {
            $dados = JwtHandler::validateToken($_SESSION['jwt']);
            $usuario = new Usuario();
        }

        $usuarioDado = $usuario->findForSign($dados['email']);
        // Simulação de dados fictícios para o dashboard do dono
        $data = [
            'titulo' => "Dashboard do Dono, " . $usuarioDado[0]['nome'] ?? 'Dono',
            'subtitulo' => 'Gerencie suas bibliotecas, livros e rendimentos.',
            'alugueis_ativos' => 3,
            'historico_alugueis' => 8,
            'rendimentos' => 145.50
        ];

        $styles = [
            '/assets/css/admin/dashboards/dono.min.css'
        ];

        return new View(view: 'admin/dashboard/dono', vars: $data, styles: $styles, layout: 'admin-layout-dono');
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo  JsonHelper::toJson($data);
        exit;
    }

    public function verificarNome()
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        $nome = trim($data['nome'] ?? '');

        $biblioteca = new Biblioteca();
        $existe = $biblioteca->checarNomeDisponivel($nome);

        return $this->jsonResponse([
            'success' => !$existe,
            'message' => $existe ? 'Nome já em uso.' : 'Nome disponível.'
        ]);
    }
}

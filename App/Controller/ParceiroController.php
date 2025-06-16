<?php

namespace App\Controller;

use Core\View\View;

class ParceiroController
{
    public function todosParceiros($pagina = 1)
    {
        $parceiros = [
            ['id' => 1, 'nome' => 'João Silva', 'tipo' => 'Pessoa', 'email' => 'joao@exemplo.com', 'telefone' => '(11) 99999-9999', 'redes_sociais' => json_encode(['tiktok' => 'https://tiktok.com/@joao', 'linkedin' => 'https://linkedin.com/in/joao']), 'descricao' => 'Produtor cultural experiente.'],
            ['id' => 2, 'nome' => 'TechCorp', 'tipo' => 'Empresa', 'email' => 'contato@techcorp.com', 'telefone' => '(21) 88888-8888', 'redes_sociais' => json_encode(['instagram' => 'https://instagram.com/techcorp']), 'descricao' => 'Empresa de tecnologia inovadora.'],
            ['id' => 3, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => json_encode(['facebook' => 'https://facebook.com/maria']), 'descricao' => 'Artista multifacetada.'],
            ['id' => 4, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 5, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 6, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 7, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 8, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 9, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 10, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 11, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 12, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
            ['id' => 13, 'nome' => 'Maria Oliveira', 'tipo' => 'Pessoa', 'email' => 'maria@exemplo.com', 'telefone' => '(31) 77777-7777', 'redes_sociais' => null, 'descricao' => null],
        ];

        $totalParceiros = count($parceiros);
        $itensPorPagina = 10;

        // Pegar página via $_GET ou usar o padrão (1)
        $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : (int)$pagina;
        $totalPaginas = ceil($totalParceiros / $itensPorPagina);

        // Garantir que a página atual esteja dentro dos limites
        $paginaAtual = max(1, min($paginaAtual, $totalPaginas));

        // Calcular offset e pegar parceiros da página atual
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $parceirosPagina = array_slice($parceiros, $offset, $itensPorPagina);

        $styles = ['/assets/css/parceiros.min.css'];
        $scripts = ['/assets/js/main-admin.min.js', '/assets/js/parceiros.min.js'];

        $data = [
            'title' => 'Gerenciar Parceiros',
            'parceiros' => $parceirosPagina,
            'paginaAtual' => $paginaAtual,
            'totalPaginas' => $totalPaginas,
            'totalParceiros' => $totalParceiros
        ];

        return new View('admin/parceiros', $data, $styles, $scripts, 'admin-layout');
    }

    public function addParceiro()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];

            // Simulação de CSRF (substitua por sua lógica real)
            if (!isset($data['_csrf_token']) || $data['_csrf_token'] !== 'token-simulado') {
                return $this->jsonResponse(['success' => false, 'message' => 'Token CSRF inválido.'], 403);
            }

            if (empty($data['nome']) || empty($data['tipo']) || empty($data['email']) || empty($data['telefone'])) {
                return $this->jsonResponse(['success' => false, 'message' => 'Os campos Nome, Tipo, E-mail e Telefone são obrigatórios.'], 400);
            }

            // Simulação de dados salvos (substitua por banco de dados)
            global $parceiros;
            $newId = end($parceiros)['id'] + 1;
            $redesSociais = array_filter($data['redes_sociais'] ?? [], function($url) {
                return !empty($url);
            });
            $parceiro = [
                'id' => $newId,
                'nome' => $data['nome'],
                'tipo' => $data['tipo'],
                'email' => $data['email'],
                'telefone' => $data['telefone'],
                'redes_sociais' => json_encode($redesSociais),
                'descricao' => $data['descricao'] ?? null
            ];
            $parceiros[] = $parceiro;

            return $this->jsonResponse(['success' => true, 'message' => 'Parceiro adicionado com sucesso!', 'id' => $newId]);
        } catch (\Exception $e) {
            error_log("Erro ao adicionar parceiro: " . $e->getMessage());
            return $this->jsonResponse(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function updateParceiro($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];

            // Simulação de CSRF (substitua por sua lógica real)
            if (!isset($data['_csrf_token']) || $data['_csrf_token'] !== 'token-simulado') {
                return $this->jsonResponse(['success' => false, 'message' => 'Token CSRF inválido.'], 403);
            }

            if (empty($data['nome']) || empty($data['tipo']) || empty($data['email']) || empty($data['telefone'])) {
                return $this->jsonResponse(['success' => false, 'message' => 'Os campos Nome, Tipo, E-mail e Telefone são obrigatórios.'], 400);
            }

            // Simulação de atualização (substitua por banco de dados)
            global $parceiros;
            $redesSociais = array_filter($data['redes_sociais'] ?? [], function($url) {
                return !empty($url);
            });
            foreach ($parceiros as &$parceiro) {
                if ($parceiro['id'] == $id) {
                    $parceiro['nome'] = $data['nome'];
                    $parceiro['tipo'] = $data['tipo'];
                    $parceiro['email'] = $data['email'];
                    $parceiro['telefone'] = $data['telefone'];
                    $parceiro['redes_sociais'] = json_encode($redesSociais);
                    $parceiro['descricao'] = $data['descricao'] ?? null;
                    break;
                }
            }
            unset($parceiro); // Quebra a referência

            return $this->jsonResponse(['success' => true, 'message' => 'Parceiro atualizado com sucesso!']);
        } catch (\Exception $e) {
            error_log("Erro ao atualizar parceiro: " . $e->getMessage());
            return $this->jsonResponse(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function deleteParceiro($id)
    {
        try {
            // Simulação de CSRF (substitua por sua lógica real)
            $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!$csrfToken || $csrfToken !== 'token-simulado') {
                return $this->jsonResponse(['success' => false, 'message' => 'Token CSRF inválido.'], 403);
            }

            // Simulação de exclusão (substitua por banco de dados)
            global $parceiros;
            $parceiros = array_filter($parceiros, function($parceiro) use ($id) {
                return $parceiro['id'] != $id;
            });

            return $this->jsonResponse(['success' => true, 'message' => 'Parceiro excluído com sucesso!']);
        } catch (\Exception $e) {
            error_log("Erro ao excluir parceiro: " . $e->getMessage());
            return $this->jsonResponse(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
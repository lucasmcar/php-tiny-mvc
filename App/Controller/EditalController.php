<?php

namespace App\Controller;

use Core\View\View;
use App\Model\Edital;
use App\Model\Log;

use Core\Security\Jwt\JwtHandler;
use App\Helper\InputFilterHelper;
use Core\Security\Csrf;

class EditalController
{
    public function editais($pagina = 1)
    {
        $editaisData = new Edital();


        $totalEdital = count($editaisData->all());
        $itensPorPagina = 10;

        // Pegar página via $_GET ou usar o padrão (1)
        $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : (int)$pagina;
        $totalPaginas = ceil($totalEdital / $itensPorPagina);

        // Garantir que a página atual esteja dentro dos limites
        $paginaAtual = max(1, min($paginaAtual, $totalPaginas));

        // Calcular offset e pegar depoimentos da página atual
        $offset = ($paginaAtual - 1) * $itensPorPagina;
        $editaisPagina = array_slice($editaisData->all(), $offset, $itensPorPagina);



        $data = [
            'title' => 'Gerenciar Editais',
            'editais' => $editaisPagina,
            'paginaAtual' => $paginaAtual,
            'totalPaginas' => $totalPaginas,
            'totalEditais' => $totalEdital,
        ];

        $styles = [

            '/assets/css/admin/edital.min.css'
        ];
        $scripts = [
            '/assets/js/main-admin.min.js',
            '/assets/js/edital.min.js'
        ];
        return new View(view: 'admin/edital', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }

    public function uploadEdital()
    {
        if (!isset($_FILES['edital_file'])) {
            echo json_encode(['success' => false, 'message' => 'Nenhum arquivo enviado']);
            return;
        }

        $file = $_FILES['edital_file'];
        $filename = uniqid() . '-' . basename($file['name']);
        $uploadDir = __DIR__ . '/../../public/uploads/editais/';
        $uploadPath = $uploadDir . $filename;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $edital = new Edital();
            $jwt = $_SESSION['jwt'] ?? null;
            $usuarioId = null;
            if ($jwt) {
                $decoded = JwtHandler::validateToken($jwt);
                $usuarioId = (int)$decoded['sub']; // Extrai o 'sub' do token decodificado
            }

            $success = $edital->create([
                'usuario_id' => $usuarioId,
                'name' => $file['name'],
                'filename' => $filename,
                'url' => '/uploads/editais/' . $filename,
                'status' => 'Em Análise'
            ]);

            if ($success) {
                echo json_encode(['success' => true, 'filename' => $file['name']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar o arquivo']);
        }
    }

    public function listarEditais()
    {
        ob_start(); // Inicia buffer para capturar saídas indesejadas
        header('Content-Type: application/json');
        session_start();

        // Verificar autenticação
        if (!isset($_SESSION['jwt']) || !JwtHandler::validateToken($_SESSION['jwt'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Não autenticado']);
            ob_end_flush();
            exit;
        }

        try {
            $edital = new Edital();
            $todosEditais = $edital->all();
            $totalEditais = count($todosEditais);
            $itensPorPagina = 10;

            $paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $totalPaginas = ceil($totalEditais / $itensPorPagina);
            $paginaAtual = max(1, min($paginaAtual, $totalPaginas));

            $offset = ($paginaAtual - 1) * $itensPorPagina;
            $editaisPagina = array_slice($todosEditais, $offset, $itensPorPagina);

            $response = [
                'editais' => $editaisPagina,
                'totalEditais' => $totalEditais,
                'paginaAtual' => $paginaAtual,
                'totalPaginas' => $totalPaginas
            ];

            ob_end_clean(); // Limpa qualquer saída indesejada
            echo json_encode($response);
            exit;
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao listar editais: ' . $e->getMessage()]);
            ob_end_flush();
            exit;
        }
    }

    /*public function alterarStatusEdital()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? null;


        if (!$id || !$status) {
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            return;
        }

        $edital = new Edital();
        $editalData = $edital->find($id);
        if ($editalData) {
            $success = $edital->update($id, ['status' => $status]);
            if ($success) {
                echo json_encode(['success' => true, 'filename' => $editalData['filename']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Edital não encontrado']);
        }
    }*/

    public function alterarStatusEdital()
    {
        ob_start();
        header('Content-Type: application/json');
        session_start();

        // Verificar autenticação
        if (!isset($_SESSION['jwt']) || !JwtHandler::validateToken($_SESSION['jwt'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Não autenticado']);
            ob_end_flush();
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        $status = $data['status'] ?? null;
        $csrfToken = $data['_csrf_token'] ?? null;

        if (!Csrf::verifyToken($csrfToken)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF inválido']);
            ob_end_flush();
            return;
        }

        if (!$id || !in_array($status, ['em_analise', 'aprovado', 'reprovado'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
            ob_end_flush();
            return;
        }

        try {
            $editalModel = new Edital();
            $edital = $editalModel->find($id);
            if (!$edital) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Edital não encontrado']);
                ob_end_flush();
                return;
            }

            // Verifica se $edital é um array e ajusta o acesso
            $editalData = is_array($edital) && isset($edital[0]) ? $edital[0] : $edital;
            $oldStatus = $editalData['status'];

            // Corrige o nome do método e passa o novo status
            $editalModel->updateEditalStatus($id, $status);

            $logModel = new Log();
            $logModel->create([
                'acao' => 'alteracao_status_edital',
                'tabela_afetada' => 'editais',
                'id_registro' => $id,
                'detalhes' => json_encode(['status_antigo' => $oldStatus, 'status_novo' => $status]),
                'data_acao' => date('Y-m-d H:i:s')
            ]);

            ob_end_clean();
            echo json_encode([
                'id' => $id,
                'status' => ucfirst($status),
                'name' => $editalData['name'],
                'data_upload' => $editalData['data_upload'],
                'filename' => $editalData['filename'],
                'url' => $editalData['url'],
                'success' => true,
                'message' => 'Status atualizado com sucesso'
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar status: ' . $e->getMessage()]);
            ob_end_flush();
        }
    }
}

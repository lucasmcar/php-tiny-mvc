<?php
namespace App\Controller;

use Core\View\View;
use Dompdf\Dompdf;
use App\Model\Log;

class LogController
{
    public function index()
    {
        $filter = $_GET['filter'] ?? '';
        $logModel = new Log();
        $logs = $this->fetchLogs($filter);

        $data = [
            'title' => 'Logs de Alterações',
            'logs' => $logs,
            'filter' => $filter
        ];

        $styles = [
            '/assets/css/admin/logs.min.css'
        ];

        $scripts = [
            '/assets/js/logs.min.js'
        ];

        return new View(view: 'admin/logs', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }

    // Método auxiliar para buscar logs com filtro
    private function fetchLogs($filter = '')
    {
        $logModel = new Log();
        if ($filter) {
            return $logModel
                ->join('usuarios', 'logs_atividades.usuario_id = usuarios.id')
                ->where("acao", "LIKE", "%$filter%")
                ->orWhere("tabela_afetada", "LIKE", "%$filter%")
                ->orWhere("detalhes", "LIKE", "%$filter%")
                ->orWhere("JSON_UNQUOTE(JSON_EXTRACT(detalhes, '$.name'))", "LIKE", "%$filter%")
                ->orWhere("JSON_UNQUOTE(JSON_EXTRACT(detalhes, '$.filename'))", "LIKE", "%$filter%")
                ->get();
        }
        return $logModel
        ->join('usuarios', 'logs_atividades.usuario_id = usuarios.id')
        ->get();
    }

    // Endpoint para listar logs via AJAX
    public function listLogs($params = [])
    {
        // Forçar cabeçalho JSON
        header('Content-Type: application/json');

        // Verificar autenticação (se aplicável)
        if (!isset($_SESSION['jwt']) || !\Core\Security\Jwt\JwtHandler::validateToken($_SESSION['jwt'])) {
            echo json_encode(['error' => 'Não autenticado']);
            http_response_code(401);
            exit;
        }

        $filter = $_GET['filter'] ?? '';
        $logs = $this->fetchLogs($filter);
        echo json_encode(['logs' => $logs]);
    }

    // Endpoint para limpar logs selecionados
    public function clearLogs()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $ids = $data['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Nenhum log selecionado']);
            return;
        }

        $logModel = new Log();
        foreach ($ids as $id) {
            $logModel->delete($id);
        }
        echo json_encode(['success' => true]);
    }
}
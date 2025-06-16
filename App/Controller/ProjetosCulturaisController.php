<?php

namespace App\Controller;

use Core\View\View;

class ProjetosCulturaisController
{
    // Simulação de armazenamento de projetos (substitua por um banco de dados)
    private static $projetos = [];

    public function index()
    {
        $data = [
            'title' => 'Gestão de Projetos Culturais - Cadastro'
        ];

        $styles = [
            '/assets/css/admin/projetos-culturais.min.css'
        ];

        $scripts = [
            '/assets/js/projetos-culturais.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/projetos-culturais/index', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/projetos-culturais/index', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function listaProjetos()
    {
        $data = [
            'title' => 'Lista de Projetos Culturais',
            'projetos' => self::$projetos
        ];

        $styles = [
            '/assets/css/admin/projetos-culturais.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/projetos-culturais/lista', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/projetos-culturais/lista', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function detalhesProjeto()
    {
        $projectId = $_GET['id'] ?? null;
        if ($projectId === null || !isset(self::$projetos[$projectId])) {
            header('Location: /admin/projetos-culturais/listaProjetos');
            exit;
        }

        $data = [
            'title' => 'Detalhes do Projeto',
            'projeto' => self::$projetos[$projectId]
        ];

        $styles = [
            '/assets/css/admin/projetos-culturais.min.css'
        ];

        $scripts = [
            '/assets/js/projetos-culturais-detalhes.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/projetos-culturais/detalhes', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/projetos-culturais/detailhes', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function salvarProjeto()
    {
        // Recebe os dados do formulário
        $nome = $_POST['nome'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $objetivos = $_POST['objetivos'] ?? '';
        $publicoAlvo = $_POST['publico_alvo'] ?? '';
        $orcamentoEstimado = $_POST['orcamento_estimado'] ?? '';
        $cronograma = $_POST['cronograma'] ?? '';

        // Gera um ID único para o projeto (simulação)
        $projectId = count(self::$projetos);

        // Salva os dados no array (substitua por banco de dados)
        self::$projetos[$projectId] = [
            'id' => $projectId,
            'nome' => $nome,
            'descricao' => $descricao,
            'objetivos' => $objetivos,
            'publico_alvo' => $publicoAlvo,
            'orcamento_estimado' => $orcamentoEstimado,
            'cronograma' => $cronograma,
            'status' => 'em_elaboracao', // Status inicial
            'planejamento' => [],
            'incentivo' => null,
            'resultados' => null
        ];

        $response = [
            'success' => 'Projeto registrado com sucesso!',
            'project_id' => $projectId
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function atualizaProjetoStatus()
    {
        $projectId = $_POST['project_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($projectId === null || !isset(self::$projetos[$projectId]) || $status === null) {
            $response = ['error' => 'Projeto ou status inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        self::$projetos[$projectId]['status'] = $status;
        $response = ['success' => 'Status atualizado com sucesso!'];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function salvarPlanejamento()
    {
        $projectId = $_POST['project_id'] ?? null;
        $etapas = $_POST['etapas'] ?? [];
        $responsaveis = $_POST['responsaveis'] ?? [];
        $prazos = $_POST['prazos'] ?? [];

        if ($projectId === null || !isset(self::$projetos[$projectId])) {
            $response = ['error' => 'Projeto inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $planejamento = [];
        for ($i = 0; $i < count($etapas); $i++) {
            $planejamento[] = [
                'etapa' => $etapas[$i],
                'responsavel' => $responsaveis[$i],
                'prazo' => $prazos[$i]
            ];
        }

        self::$projetos[$projectId]['planejamento'] = $planejamento;
        $response = ['success' => 'Planejamento estratégico salvo com sucesso!'];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function salvarIncentivo()
    {
        $projectId = $_POST['project_id'] ?? null;
        $justificativa = $_POST['justificativa'] ?? '';
        $metas = $_POST['metas'] ?? '';
        $planoRecursos = $_POST['plano_recursos'] ?? '';

        if ($projectId === null || !isset(self::$projetos[$projectId])) {
            $response = ['error' => 'Projeto inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        self::$projetos[$projectId]['incentivo'] = [
            'justificativa' => $justificativa,
            'metas' => $metas,
            'plano_recursos' => $planoRecursos
        ];

        $response = ['success' => 'Dados de incentivo salvos com sucesso!'];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function salvarResultados()
    {
        $projectId = $_POST['project_id'] ?? null;
        $alcance = $_POST['alcance'] ?? '';
        $impactoCultural = $_POST['impacto_cultural'] ?? '';
        $retornoFinanceiro = $_POST['retorno_financeiro'] ?? '';
        $observacoes = $_POST['observacoes'] ?? '';

        if ($projectId === null || !isset(self::$projetos[$projectId])) {
            $response = ['error' => 'Projeto inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        self::$projetos[$projectId]['resultados'] = [
            'alcance' => $alcance,
            'impacto_cultural' => $impactoCultural,
            'retorno_financeiro' => $retornoFinanceiro,
            'observacoes' => $observacoes
        ];

        $response = ['success' => 'Resultados salvos com sucesso!'];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
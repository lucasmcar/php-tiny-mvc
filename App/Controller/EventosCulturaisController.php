<?php

namespace App\Controller;

use Core\View\View;


class EventosCulturaisController
{
    // Simulação de armazenamento de eventos (substitua por um banco de dados)
    private static $eventos = [];

    public function index()
    {
        $data = [
            'title' => 'Produção de Eventos Culturais - Cadastro'
        ];

        $styles = [
            '/assets/css/admin/eventos-culturais.min.css'
        ];

        $scripts = [
            '/assets/js/eventos-culturais.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/eventos-culturais/index', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/eventos-culturais/index', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function listaEventos()
    {
        $data = [
            'title' => 'Lista de Eventos Culturais',
            'eventos' => self::$eventos
        ];

        $styles = [
            '/assets/css/admin/eventos-culturais.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/eventos-culturais/lista', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/eventos-culturais/lista', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function detalhesEvento()
    {
        $eventId = $_GET['id'] ?? null;
        if ($eventId === null || !isset(self::$eventos[$eventId])) {
            header('Location: /admin/eventos-culturais/listaEventos');
            exit;
        }

        $data = [
            'title' => 'Detalhes do Evento',
            'evento' => self::$eventos[$eventId]
        ];

        $styles = [
            '/assets/css/admin/eventos-culturais.min.css'
        ];

        $scripts = [
            '/assets/js/eventos-culturais-detalhes.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/eventos-culturais/detalhes', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/eventos-culturais/detalhes', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function salvarEvento()
    {
        // Recebe os dados do formulário
        $nome = $_POST['nome'] ?? '';
        $data = $_POST['data'] ?? '';
        $local = $_POST['local'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $artistas = $_POST['artistas'] ?? '';
        $orcamento = $_POST['orcamento'] ?? '';

        // Gerencia o upload de editais (3.1.1)
        $editais = [];
        if (!empty($_FILES['editais']['name'][0])) {
            $uploadDir = 'uploads/eventos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($_FILES['editais']['name'] as $key => $name) {
                $tmpName = $_FILES['editais']['tmp_name'][$key];
                $filePath = $uploadDir . basename($name);
                if (move_uploaded_file($tmpName, $filePath)) {
                    $editais[] = $filePath;
                }
            }
        }

        // Gera um ID único para o evento (simulação)
        $eventId = count(self::$eventos);

        // Salva os dados no array (substitua por banco de dados)
        self::$eventos[$eventId] = [
            'id' => $eventId,
            'nome' => $nome,
            'data' => $data,
            'local' => $local,
            'descricao' => $descricao,
            'artistas' => $artistas,
            'orcamento' => $orcamento,
            'editais' => $editais, // 3.1.1
            'tarefas' => [],
            'checklist' => [],
            'financas' => ['custos' => 0, 'receitas' => 0]
        ];

        $response = [
            'success' => 'Evento registrado com sucesso!',
            'event_id' => $eventId
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function salvarTarefa()
    {
        $eventId = $_POST['event_id'] ?? null;
        $tipo = $_POST['tipo'] ?? '';
        $detalhes = $_POST['detalhes'] ?? '';
        $responsavel = $_POST['responsavel'] ?? '';
        $prazo = $_POST['prazo'] ?? '';

        if ($eventId === null || !isset(self::$eventos[$eventId])) {
            $response = ['error' => 'Evento inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        self::$eventos[$eventId]['tarefas'][] = [
            'tipo' => $tipo,
            'detalhes' => $detalhes,
            'responsavel' => $responsavel,
            'prazo' => $prazo
        ];

        $response = ['success' => 'Tarefa salva com sucesso!'];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function salvarChecklist()
    {
        $eventId = $_POST['event_id'] ?? null;
        $itens = $_POST['itens'] ?? [];
        $status = $_POST['status'] ?? [];

        if ($eventId === null || !isset(self::$eventos[$eventId])) {
            $response = ['error' => 'Evento inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $checklist = [];
        for ($i = 0; $i < count($itens); $i++) {
            $checklist[] = [
                'item' => $itens[$i],
                'concluido' => isset($status[$i]) && $status[$i] === 'on'
            ];
        }

        self::$eventos[$eventId]['checklist'] = $checklist;
        $response = ['success' => 'Checklist salvo com sucesso!'];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function salvarFinancas()
    {
        $eventId = $_POST['event_id'] ?? null;
        $custos = $_POST['custos'] ?? 0;
        $receitas = $_POST['receitas'] ?? 0;

        if ($eventId === null || !isset(self::$eventos[$eventId])) {
            $response = ['error' => 'Evento inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        self::$eventos[$eventId]['financas'] = [
            'custos' => floatval($custos),
            'receitas' => floatval($receitas)
        ];

        $response = ['success' => 'Finanças salvas com sucesso!'];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
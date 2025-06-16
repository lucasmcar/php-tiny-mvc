<?php

namespace App\Controller;

use Core\View\View;

class ConsultoriaController
{

    private static $clientes = [];
    public function index()
    {
        $data = [
            'title' => 'Assessoria e Consultoria Cultural'
        ];

        $styles = [
            '/assets/css/admin/consultoria.min.css'
        ];

        $scripts = [
            '/assets/js/consultoria.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/consultoria', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/consultoria', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function saveClient()
    {
        // Recebe os dados do formulário
        $nome = $_POST['nome'] ?? '';
        $tipoCliente = $_POST['tipo_cliente'] ?? '';
        $contato = $_POST['contato'] ?? '';
        $objetivos = $_POST['objetivos'] ?? '';
        $demandas = $_POST['demandas'] ?? '';
        
        // Processa o upload de documentos
        $documentos = [];
        if (!empty($_FILES['documentos']['name'][0])) {
            $uploadDir = 'uploads/consultoria/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($_FILES['documentos']['name'] as $key => $name) {
                $tmpName = $_FILES['documentos']['tmp_name'][$key];
                $filePath = $uploadDir . basename($name);
                if (move_uploaded_file($tmpName, $filePath)) {
                    $documentos[] = $filePath;
                }
            }
        }

        // Aqui você salvaria os dados no banco de dados
        // Exemplo: $this->model->saveClient($nome, $tipoCliente, $contato, $objetivos, $demandas, $documentos);

        // Simulação de salvamento bem-sucedido
        $response = [
            'success' => 'Cliente e demanda registrados com sucesso!',
            'documentos' => $documentos
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function generateReport()
    {
        // Recebe os dados para o relatório
        $nome = $_POST['nome'] ?? '';
        $tipoCliente = $_POST['tipo_cliente'] ?? '';
        $objetivos = $_POST['objetivos'] ?? '';
        $demandas = $_POST['demandas'] ?? '';

        // Gera recomendações estratégicas (simulação)
        $recomendacoes = "Com base nas informações fornecidas:\n";
        $recomendacoes .= "- Nome: $nome\n";
        $recomendacoes .= "- Tipo de Cliente: $tipoCliente\n";
        $recomendacoes .= "- Objetivos Culturais: $objetivos\n";
        $recomendacoes .= "- Demandas: $demandas\n\n";
        $recomendacoes .= "Recomendações Estratégicas:\n";
        $recomendacoes .= "1. Desenvolver uma campanha cultural alinhada aos objetivos de $objetivos.\n";
        $recomendacoes .= "2. Para $demandas, sugerimos a criação de parcerias com instituições culturais locais.\n";
        $recomendacoes .= "3. Realizar eventos ou workshops para engajar o público-alvo.\n";

        $response = [
            'success' => 'Relatório gerado com sucesso!',
            'recomendacoes' => $recomendacoes
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function listaClientes()
    {
        $data = [
            'title' => 'Lista de Clientes',
            'clientes' => self::$clientes
        ];

        $styles = [
            '/assets/css/admin/consultoria.min.css'
        ];

        $scripts = [
            '/assets/js/consultoria-list.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/consultoria-list', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/consultoria-list', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function perfilCliente()
    {
        $clientId = $_GET['id'] ?? null;
        if ($clientId === null || !isset(self::$clientes[$clientId])) {
            // Redireciona ou exibe erro se o cliente não for encontrado
            header('Location: /admin/consultoria/listClients');
            exit;
        }

        $data = [
            'title' => 'Perfil do Cliente',
            'cliente' => self::$clientes[$clientId]
        ];

        $styles = [
            '/assets/css/admin/consultoria.min.css'
        ];

        $scripts = [
            'assets/js/cliente-consultoria.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/cliente-consultoria', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/cliente-consultoria', $data, $styles, $scripts, 'admin-layout');
        }
    }
}
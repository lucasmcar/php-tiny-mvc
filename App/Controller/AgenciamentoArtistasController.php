<?php

namespace App\Controller;

use Core\View\View;

class AgenciamentoArtistasController
{
    // Simulação de armazenamento (substitua por um banco de dados)
    private static $artistas = [];
    private static $contratos = [];
    private static $agenda = [];
    private static $historicoEventos = [];

    // --- Tela de Cadastro de Artistas (Regra 4.1) ---
    public function index()
    {
        $data = [
            'title' => 'Agenciamento de Artistas - Cadastro'
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [
            '/assets/js/admin-agenciamento-artistas.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/index', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/index', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function listaArtistas()
    {
        $data = [
            'title' => 'Lista de Artistas',
            'artists' => []//[self::$artistas]
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/lista', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/lista', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function detalhesArtista()
    {
        $artistId = $_GET['id'] ?? null;
        if ($artistId === null || !isset(self::$artistas[$artistId])) {
            header('Location: /admin/agenciamento-artistas/listaArtistas');
            exit;
        }

        $data = [
            'title' => 'Detalhes do Artista - ' . htmlspecialchars(self::$artistas[$artistId]['nome']),
            'artista' => self::$artistas[$artistId]
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/detalhes', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/detalhes', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function salvarArtista()
    {
        $nome = $_POST['nome'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $historico = $_POST['historico'] ?? '';
        $cache = $_POST['cache'] ?? '';

        $portfolio = [
            'fotos' => [],
            'videos' => [],
            'textos' => $_POST['textos'] ?? ''
        ];

        // Upload de fotos
        if (!empty($_FILES['fotos']['name'][0])) {
            $uploadDir = 'uploads/artistas/fotos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($_FILES['fotos']['name'] as $key => $name) {
                $tmpName = $_FILES['fotos']['tmp_name'][$key];
                $filePath = $uploadDir . basename($name);
                if (move_uploaded_file($tmpName, $filePath)) {
                    $portfolio['fotos'][] = $filePath;
                }
            }
        }

        // Upload de vídeos
        if (!empty($_FILES['videos']['name'][0])) {
            $uploadDir = 'uploads/artistas/videos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($_FILES['videos']['name'] as $key => $name) {
                $tmpName = $_FILES['videos']['tmp_name'][$key];
                $filePath = $uploadDir . basename($name);
                if (move_uploaded_file($tmpName, $filePath)) {
                    $portfolio['videos'][] = $filePath;
                }
            }
        }

        $artistId = count(self::$artistas);

        self::$artistas[$artistId] = [
            'id' => $artistId,
            'nome' => $nome,
            'genero' => $genero,
            'portfolio' => $portfolio,
            'historico' => $historico,
            'cache' => floatval($cache)
        ];

        $response = [
            'success' => 'Artista registrado com sucesso!',
            'artist_id' => $artistId
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // --- Tela de Cadastro de Contratos (Regra 4.2) ---
    public function contratos()
    {
        $data = [
            'title' => 'Gerenciar Contratos',
            'artists' => self::$artistas
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [
            '/assets/js/admin-agenciamento-contratos.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/contratos', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/contratos', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function listaContratos()
    {
        $data = [
            'title' => 'Lista de Contratos',
            'contratos' => self::$contratos,
            'artists' => self::$artistas
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/lista-contratos', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/lista-contratos', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function detalhesContrato()
    {
        $contractId = $_GET['id'] ?? null;
        if ($contractId === null || !isset(self::$contratos[$contractId])) {
            header('Location: /admin/agenciamento-artistas/listaContratos');
            exit;
        }

        $data = [
            'title' => 'Detalhes do Contrato - ' . htmlspecialchars(self::$artistas[self::$contratos[$contractId]['artist_id']]['nome']),
            'contrato' => self::$contratos[$contractId],
            'artista' => self::$artistas[self::$contratos[$contractId]['artist_id']]
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/detalhes-contrato', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/detalhes-contrato', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function salvarContrato()
    {
        $artistId = $_POST['artist_id'] ?? null;
        $valor = $_POST['valor'] ?? '';
        $duracao = $_POST['duracao'] ?? '';
        $clausulas = $_POST['clausulas'] ?? '';

        if ($artistId === null || !isset(self::$artistas[$artistId])) {
            $response = ['error' => 'Artista inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $assinaturas = [];
        if (!empty($_FILES['assinaturas']['name'][0])) {
            $uploadDir = 'uploads/contratos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($_FILES['assinaturas']['name'] as $key => $name) {
                $tmpName = $_FILES['assinaturas']['tmp_name'][$key];
                $filePath = $uploadDir . basename($name);
                if (move_uploaded_file($tmpName, $filePath)) {
                    $assinaturas[] = $filePath;
                }
            }
        }

        $contractId = count(self::$contratos);

        self::$contratos[$contractId] = [
            'id' => $contractId,
            'artist_id' => $artistId,
            'valor' => floatval($valor),
            'duracao' => $duracao,
            'clausulas' => $clausulas,
            'assinaturas' => $assinaturas
        ];

        $response = [
            'success' => 'Contrato registrado com sucesso!',
            'contract_id' => $contractId
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // --- Tela de Gerenciamento de Agenda (Regra 4.3) ---
    public function agenda()
    {
        $data = [
            'title' => 'Gerenciar Agenda de Shows e Espetáculos',
            'artistas' => self::$artistas
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [
            '/assets/js/admin-agenciamento-agenda.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/agenda', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/agenda', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function listaAgenda()
    {
        $data = [
            'title' => 'Agenda de Shows e Espetáculos',
            'agenda' => self::$agenda,
            'artistas' => self::$artistas
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/lista-agenda', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/lista-agenda', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function detalhesEvento()
    {
        $eventId = $_GET['id'] ?? null;
        if ($eventId === null || !isset(self::$agenda[$eventId])) {
            header('Location: /admin/agenciamento-artistas/listaAgenda');
            exit;
        }

        $data = [
            'title' => 'Detalhes do Evento - ' . htmlspecialchars(self::$agenda[$eventId]['titulo']),
            'evento' => self::$agenda[$eventId],
            'artista' => self::$artistas[self::$agenda[$eventId]['artist_id']]
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/detalhes-evento', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/detalhes-evento', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function salvarEvento()
    {
        $artistId = $_POST['artist_id'] ?? null;
        $titulo = $_POST['titulo'] ?? '';
        $data = $_POST['data'] ?? '';
        $local = $_POST['local'] ?? '';
        $detalhes = $_POST['detalhes'] ?? '';

        if ($artistId === null || !isset(self::$artistas[$artistId])) {
            $response = ['error' => 'Artista inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Verificar conflito de datas
        foreach (self::$agenda as $event) {
            if ($event['artist_id'] == $artistId && $event['data'] == $data) {
                $response = ['error' => 'Conflito de data! O artista já tem um evento marcado para este dia.'];
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        }

        $eventId = count(self::$agenda);

        self::$agenda[$eventId] = [
            'id' => $eventId,
            'artist_id' => $artistId,
            'titulo' => $titulo,
            'data' => $data,
            'local' => $local,
            'detalhes' => $detalhes
        ];

        $response = [
            'success' => 'Evento adicionado à agenda com sucesso!',
            'event_id' => $eventId
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // --- Tela de Histórico de Eventos (Regra 4.4) ---
    public function historico()
    {
        $data = [
            'title' => 'Histórico de Eventos Realizados',
            'agenda' => self::$agenda
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [
            '/assets/js/admin-agenciamento-historico.min.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/historico', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/historico', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function listaHistorico()
    {
        $data = [
            'title' => 'Histórico de Eventos Realizados',
            'historico' => self::$historicoEventos,
            'artists' => self::$artistas
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/lista-historico', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/lista-historico', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function detalhesHistorico()
    {
        $historicoId = $_GET['id'] ?? null;
        if ($historicoId === null || !isset(self::$historicoEventos[$historicoId])) {
            header('Location: /admin/agenciamento-artistas/listaHistorico');
            exit;
        }

        $data = [
            'title' => 'Detalhes do Evento Realizado - ' . htmlspecialchars(self::$historicoEventos[$historicoId]['titulo']),
            'evento' => self::$historicoEventos[$historicoId],
            'artists' => self::$artistas[self::$historicoEventos[$historicoId]['artist_id']]
        ];

        $styles = [
            '/assets/css/admin/agenciamento-artistas.min.css'
        ];

        $scripts = [];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return new View('admin/agenciamento-artistas/detalhes-historico', $data, $styles, $scripts, 'admin-layout');
        } else {
            return new View('admin/agenciamento-artistas/detalhes-historico', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function salvarAvaliacaoEvento()
    {
        $eventId = $_POST['event_id'] ?? null;
        $publico = $_POST['publico'] ?? '';
        $feedback = $_POST['feedback'] ?? '';

        if ($eventId === null || !isset(self::$agenda[$eventId])) {
            $response = ['error' => 'Evento inválido.'];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        $historicoId = count(self::$historicoEventos);

        self::$historicoEventos[$historicoId] = [
            'id' => $historicoId,
            'event_id' => $eventId,
            'artist_id' => self::$agenda[$eventId]['artist_id'],
            'titulo' => self::$agenda[$eventId]['titulo'],
            'data' => self::$agenda[$eventId]['data'],
            'local' => self::$agenda[$eventId]['local'],
            'publico' => intval($publico),
            'feedback' => $feedback
        ];

        $response = [
            'success' => 'Avaliação do evento salva com sucesso!',
            'historico_id' => $historicoId
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
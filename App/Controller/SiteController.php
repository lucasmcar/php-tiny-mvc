<?php

namespace App\Controller;

use Core\View\View;
use App\Helper\InputFilterHelper;
use App\Model\User;
use Core\Security\Jwt\JwtHandler;
use Core\Security\Csrf;
use App\Model\ConfigSite;
use App\Model\DestaquesSite;
use App\Model\DepoimentosSite;
use App\Model\Depoimento;

class SiteController
{
    public function site()
    {
        $data = [
            'title' => 'Configurar página'
        ];

        $scripts = ['/assets/js/site.js'];
        $styles = ['/assets/css/admin/site.css'];

        return new View('admin/site', $data, $styles, $scripts, 'admin-layout');
    }

    public function getApprovedTestimonials()
    {
        try {
            if (!session_id()) {
                return $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
            }

            $depoimento = new Depoimento();
            $testimonials = $depoimento->where('status', 'aprovado')->get(['id', 'titulo', 'depoimento']);

            return $this->jsonResponse([
                'success' => true,
                'testimonials' => $testimonials
            ]);
        } catch (\Exception $e) {
            error_log("Erro no getApprovedTestimonials: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->jsonResponse(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function getHighlights()
    {
        try {
            if (!session_id()) {
                return $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
            }

            $siteConfig = new ConfigSite();
            $siteHighlight = new DestaquesSite();
            $config = $siteConfig->findLatest();
            $highlights = [];

            if ($config) {
                $highlights = $siteHighlight->where('site_config_id', $config['id'])->get(['id', 'url_imagem', 'texto_overlay', 'display_order']);
            }

            return $this->jsonResponse([
                'success' => true,
                'highlights' => $highlights
            ]);
        } catch (\Exception $e) {
            error_log("Erro no getHighlights: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->jsonResponse(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()], 500);
        }
    }

    public function updateSite()
    {
        try {
            if (!session_id()) {
                return $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
            }

            $input = InputFilterHelper::filterInputs(
                INPUT_POST,
                ['_csrf_token', 'highlightTitle', 'overlayColor', 'showTestimonials']
            );
            parse_str($input, $data); // Para extrair _csrf_token
            $data = json_decode($input, true) ?? [];

            if (!isset($data['_csrf_token']) || !Csrf::verifyToken($data['_csrf_token'])) {
                return $this->jsonResponse(['success' => false, 'message' => 'Token CSRF inválido.'], 403);
            }

            $siteConfig = new ConfigSite();
            $siteHighlight = new DestaquesSite();
            $siteTestimonial = new DepoimentosSite();

            // Obter ou criar configuração atual
            $config = $siteConfig->findLatest();
            if (!$config) {
                $configId = $siteConfig->create([
                    'titulo' => $data['highlightTitle'],
                    'cor_overlay' => $data['overlayColor'],
                    'mostrar_depoimentos' => $data['showTestimonials'] ? 1 : 0
                ]);
            } else {
                $configId = $config['id'];
                $siteConfig->update($configId, [
                    'titulo' => $data['highlightTitle'],
                    'cor_overlay' => $data['overlayColor'],
                    'mostrar_depoimentos' => $data['showTestimonials'] ? 1 : 0
                ]);
            }

            // Limpar highlights e testimonials existentes
            $siteHighlight->deleteBy('site_config_id', $configId);
            $siteTestimonial->deleteBy('site_config_id', $configId);

            // Salvar novos highlights com upload de imagens
            if (isset($_FILES)) {
                foreach ($_FILES['highlights']['name'] as $index => $name) {
                    if (!empty($_FILES['highlights']['tmp_name'][$index])) {
                        $file = $_FILES['highlights']['tmp_name'][$index];
                        $filename = uniqid() . '_' . basename($name);
                        $uploadDir = __DIR__ . '/../../public/assets/imgs/uploads/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }
                        $destination = $uploadDir . $filename;
                        if (move_uploaded_file($file, $destination)) {
                            $siteHighlight->create([
                                'site_config_id' => $configId,
                                'url_imagem' => $filename,
                                'texto_overlay' => $data['highlights'][$index]['overlayText'],
                                'display_order' => $data['highlights'][$index]['order']
                            ]);
                        }
                    }
                }
            }

            // Salvar novos testimonials (se ativados)
            if ($data['showTestimonials'] && isset($data['testimonials']) && is_array($data['testimonials'])) {
                $testimonials = json_decode($data['testimonials'], true);
                foreach ($testimonials as $testimonial) {
                    $siteTestimonial->create([
                        'site_config_id' => $configId,
                        'depoimento_id' => $testimonial['id']
                    ]);
                }
            }

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Configurações salvas com sucesso!'
            ]);
        } catch (\Exception $e) {
            error_log("Erro no updateSite: " . $e->getMessage() . "\n" . $e->getTraceAsString());
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
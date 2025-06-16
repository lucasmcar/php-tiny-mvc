<?php

namespace App\Controller;

use Core\View\View;
use App\Model\User;
use App\Model\Administrador;
use Core\Security\Jwt\JwtHandler;
use App\Helper\InputFilterHelper;
use Core\Security\Csrf;
use App\Model\EmailsEmpresa;
use App\Helper\JsonHelper;

class PerfilController
{
    public function perfil()
    {
        $data = [];
        if (session_id()) {
            $data = JwtHandler::validateToken($_SESSION['jwt']);
        }

        $user = new User();
        $emailsEmpresa = new EmailsEmpresa();

        // Obter dados do usuário logado
        $userResult = $user->findForSign($data['email']);
        if (!$userResult || empty($userResult)) {
            return new View('admin/perfil', ['title' => 'Perfil Administrativo'], [], [], 'admin-layout');
        }
        $currentUserId = $userResult[0]['id'];

        // Obter administradores, excluindo o administrador logado
        $admins = $user->where('funcao', '=', 'admin')
                      ->where('id', '<>', $currentUserId)
                      ->andWhere('criado_por', '=', $currentUserId)
                      ->get(['id', 'nome', 'email', 'criado_por', 'nome_exibicao']);
        $emails = $emailsEmpresa->join('usuarios as u', 'emp.usuario_id = u.id')
            ->get(['emp.id', 'emp.email']);

        $adminData = [
            'title' => 'Perfil Administrativo',
            'adminData' => $userResult[0] ?? [],
            'admins' => $admins,
            'emails_empresa' => $emails
        ];

        $styles = ['/assets/css/perfil.min.css'];
        $scripts = ['/assets/js/perfil.min.js'];

        return new View('admin/perfil', $adminData, $styles, $scripts, 'admin-layout');
    }

    public function updateProfile()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];

            // Verificar CSRF
            if (!isset($data['_csrf_token']) || !Csrf::verifyToken($data['_csrf_token'])) {
                return $this->jsonResponse(['success' => false, 'message' => 'Token CSRF inválido.'], 403);
            }

            // Verificar sessão
            if (!session_id()) {
                return $this->jsonResponse(['success' => false, 'message' => 'Usuário não autenticado.'], 401);
            }

            $userData = new User();
            $emailEmpresaData = new EmailsEmpresa();
            $dados = JwtHandler::validateToken($_SESSION['jwt']);
            $userResult = $userData->findForSign($dados['email']);
            if (!$userResult || empty($userResult)) {
                return $this->jsonResponse(['success' => false, 'message' => 'Usuário não encontrado.'], 404);
            }

            $userId = $userResult[0]['id'];

            // Atualizar dados do usuário logado
            if (isset($data['nome_exibicao']) && $data['nome_exibicao'] !== '') {
                $userData->update($userId, ['nome_exibicao' => $data['nome_exibicao']]); // Ajuste 'name' conforme o campo real
            }

            // Gerenciar administradores (apenas novos admins, sem afetar o logado)
            $admins = [];
            if (isset($data['admins']) && is_array($data['admins'])) {
                foreach ($data['admins'] as $admin) {
                    if (!empty($admin['nome']) && !empty($admin['email']) && $admin['email'] !== $userResult[0]['email']) {
                        $password = !empty($admin['senha']) ? password_hash($admin['senha'], PASSWORD_DEFAULT) : null;
                        $lastId = $userData->create([
                            'nome' => $admin['nome'],
                            'usuario' => $admin['nome'],
                            'email' => $admin['email'],
                            'senha' => $password,
                            'funcao' => 'admin',
                            'nome_exibicao' => '',
                            'criado_por' => $userId
                        ]);
                        if ($lastId > 0) {
                            $admins[] = ['id' => $lastId, 'nome' => $admin['nome'], 'email' => $admin['email'], 'criado_por' => $userId];
                        }
                    }
                }
            }

            // Atualizar e-mails da empresa
            $emailResult = $emailEmpresaData->join('usuarios as u', 'emp.usuario_id = u.id')->get(['emp.id', 'emp.email', 'emp.usuario_id']);
            if($emailResult  != null){
                foreach($emailResult as $result){
                    $emailEmpresaData->deleteByUserId($result['usuario_id']);
                }
            }
            
            if (isset($data['emails_empresa']) && is_array($data['emails_empresa'])) {
                $emails = [];
                foreach ($data['emails_empresa'] as $email) {
                    $lastId = $emailEmpresaData->create([
                        'usuario_id' => $emailResult['id'],
                        'email' => $email['email']
                    ]);
                    if ($lastId > 0) {
                        $emails[] = ['id' => $lastId, 'email' => $email['email']];
                    }
                }
            }

            // Retornar resposta JSON
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Perfil atualizado com sucesso!',
                'data' => [
                    'admins' => $admins ?? [],
                    'emails_empresa' => $emails ?? []
                ]
            ]);
        } catch (\Exception $e) {
            error_log("Erro no updateProfile: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo  JsonHelper::toJson($data);
        exit;
    }
}
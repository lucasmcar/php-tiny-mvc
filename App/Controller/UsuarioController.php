<?php

namespace App\Controller;

use Core\View\View;
use App\Helper\InputFilterHelper;
use Core\Security\Jwt\JwtHandler;
use Core\Security\Csrf;
use App\Helper\JsonHelper;
use App\Repository\UsuarioRepository;

class UsuarioController
{
    public function login()
    {
        $data = [
            'title' => 'Login'
        ];

        $styles = [
            '/assets/css/admin/login.min.css'
        ];
        $scripts = [
            '/assets/js/main-admin.min.js'
        ];


        return new View(view: 'admin/login', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }

    public function registrar()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?? [];

            if (!Csrf::verifyToken($data['_csrf_token'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Token CSRF inválido.',
                    'redirect' => '/login' // Sugere redirecionamento ao frontend
                ], 403);
            }

            $usuarioRepository = new UsuarioRepository();

            $usuarioRepository->create([
                'nome' => $data['nome'],
                'usuario' => '@' . $data['usuario'],
                'email' => $data['email'],
                'senha' => password_hash($data['senha'], PASSWORD_BCRYPT),
                'tipo' => $data['tipo'],
            ]);

            return $this->jsonResponse([
                'success' => true,
                'message' => 'Usuário cadastrado sucesso!',
                'redirect' => '/cadastro'
            ]);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Não foi possível cadastrar!',
                'data' => [
                    'erro' => $e->getMessage()
                ]
            ], 400);
        }
    }


    /*public function signIn()
    {
        $data = InputFilterHelper::filterInputs(INPUT_POST, [
            'email',
            'senha',
            '_csrf_token'
        ]);

        if (!Csrf::verifyToken($data['_csrf_token'])) {
            header('location: /admin/login');
            return;
        }

        $userRepository = new UserRepository();
        $email = $userRepository->findForSign($data['email']);
        


        if ($email && password_verify($data['senha'], $email[0]['senha'])) {

            $payload = [
                'iat' => time(),              // Issued at
                'exp' => time() + (60 * 60),  // Expira em 1 hora
                'sub' => $email[0]['id'],         // Subject (ID do usuário)
                'name' => $email[0]['nome'],      // Nome do usuário
                'email' => $email[0]['email']     // E-mail do usuário
            ];

            // Gera o token com JwtHandler
            $jwt = JwtHandler::generateToken($payload);

            // Inicia a sessão se não estiver ativa
            if (!session_id()) {
                session_start();
            }
            
            $_SESSION['jwt'] = $jwt; // Armazena o token na sessão
            $_SESSION['jwt_exp'] = $payload['exp']; // Armazena a expiração do token na sessão
            $_SESSION['foto'] = $email[0]['foto'];
            $userRepository->updateLastLogin($email[0]['id'], date('Y-m-d H:i:s'));

            // Redireciona para /admin/home
            header('Location: /admin/home');
        } else {
            header('location: /admin/login');
        }
    }*/

    public function logout()
    {
        ob_start();
        header('Content-Type: application/json');

        if (!session_id()) {
            session_start();
        }

        // Verifica se é uma requisição AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Obtém o CSRF token dependendo do método
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $csrfToken = null;
        if ($requestMethod === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $csrfToken = $data['_csrf_token'] ?? null;
        } else {
            $csrfToken = $_GET['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        }

        // Valida o CSRF token
        if (!$csrfToken || !Csrf::verifyToken($csrfToken)) {
            $response = ['success' => false, 'message' => 'Token CSRF inválido'];
            if ($isAjax) {
                http_response_code(403);
                echo json_encode($response);
            } else {
                header('Location: /admin/login?error=csrf_invalid');
            }
            ob_end_flush();
            exit;
        }

        // Limpa todas as variáveis de sessão relacionadas
        if (isset($_SESSION['jwt'])) {
            unset($_SESSION['jwt']);
        }
        if (isset($_SESSION['jwt_exp'])) {
            unset($_SESSION['jwt_exp']);
        }

        // Destrói a sessão completamente
        session_unset();
        session_destroy();

        // Limpa cookies de sessão
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Responde de acordo com o método e tipo de requisição
        $response = ['success' => true, 'message' => 'Logout realizado com sucesso'];
        if ($isAjax) {
            ob_end_clean();
            echo json_encode($response);
        } else {
            ob_end_clean();
            header('Location: /admin/login');
        }
        ob_end_flush();
        exit;
    }

    public function logoutBySidebar()
    {

        if (!session_id()) {
            session_start();
        }

        // Limpa o JWT da sessão
        unset($_SESSION['jwt']);

        // Opcional: Destroi completamente a sessão
        session_destroy();

        // Redireciona para a página de login

        header('Location: /admin/login');
        exit;
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo  JsonHelper::toJson($data);
        exit;
    }
}

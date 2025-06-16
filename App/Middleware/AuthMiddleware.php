<?php
namespace App\Middleware;

use Core\Security\Jwt\JwtHandler;
use Core\Security\Csrf;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        // Inicia a sessão se não estiver ativa
        if (!session_id()) {
            session_start();
        }

        // Pega o token da sessão
        $jwt = $_SESSION['jwt'] ?? null;

        if (!$jwt) {
            http_response_code(401);
            header('Location: /');
            exit;
        }

        // Valida o token usando JwtHandler
        $decoded = JwtHandler::validateToken($jwt);
        if ($decoded === null) {
            http_response_code(401);
            echo "Unauthorized - Invalid or expired token";
            // Opcional: Limpa a sessão se o token expirar
            unset($_SESSION['jwt']);
            header('Location: /');
        }

        // Adiciona os dados do usuário ao request
        $request['user'] = $decoded;

        // Verifica CSRF para métodos POST, PUT, DELETE
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
            $headers = getallheaders();
    $csrfToken = $headers['X-CSRF-TOKEN'] ?? '';
            if (!Csrf::verifyToken($csrfToken)) {
                http_response_code(403);
                echo "Forbidden - Invalid CSRF token";
                return;
            }
        }

        return $next($request);
    }
}
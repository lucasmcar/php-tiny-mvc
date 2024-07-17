<?php

namespace App\Middleware;

use App\Router\Interface\IMiddleware;
use App\Core\Security\Csrf;

class AuthMiddleware implements IMiddleware
{
    public function handle($request, $next)
    {

        if(!session_id()){
            session_start();

        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo "Unauthorized";
            return;
        }


        // Verifica o token CSRF para requisições POST, PUT e DELETE
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
            $csrfToken = $_POST['_csrf_token'] ?? '';
            if (!Csrf::verifyToken($csrfToken)) {
                http_response_code(403);
                echo "Forbidden - Invalid CSRF token";
                return;
            }
        }

        return $next($request);
    }
}

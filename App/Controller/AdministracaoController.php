<?php

namespace App\Controller;

use Core\View\View;
use App\Helper\InputFilterHelper;
use App\Helper\JsonHelper;
use App\Repository\UsuarioRepository;
use Core\Security\Csrf;
use App\Model\Usuario;
use Core\Security\Jwt\JwtHandler;

class AdministracaoController
{
    public function login()
    {
        $data = [
            'titulo' => 'Acesse sua conta',
            'subtitulo' => 'Acesse sua biblioteca ou explore novos livros com o Bibliogo.',
        ];

        $styles = [
            '/assets/css/login.min.css',
        ];
        $scripts = [
            '/assets/js/login.min.js'
        ];

        return new View(view:'admin/login', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }

    public function acessar()
    {
        $data = InputFilterHelper::filterInputs(INPUT_POST, ['email', 'senha', '_csrf_token']);

        if (!Csrf::verifyToken($data['_csrf_token'])) {
            header('location: /biblioteca/login');
            return;
        }

        $usuario = new Usuario();
        $usuarioData = $usuario->findForSign($data['email']);
        
        if ($usuarioData && password_verify($data['senha'], $usuarioData[0]['senha'])) {

            $payload = [
                'iat' => time(),              // Issued at
                'exp' => time() + (60 * 60),  // Expira em 1 hora
                'sub' => $usuarioData[0]['id'],         // Subject (ID do usuário)
                'name' => $usuarioData[0]['nome'],      // Nome do usuário
                'email' => $usuarioData[0]['email']     // E-mail do usuário
            ];

            // Gera o token com JwtHandler
            $jwt = JwtHandler::generateToken($payload);

            // Inicia a sessão se não estiver ativa
            if (!session_id()) {
                session_start();
            }
            
            $_SESSION['jwt'] = $jwt; // Armazena o token na sessão
            $_SESSION['jwt_exp'] = $payload['exp']; // Armazena a expiração do token na sessão
            //$usuarioRepository->updateLastLogin($email[0]['id'], date('Y-m-d H:i:s'));

            if($usuarioData[0]['tipo'] == 'leitor'){
                header('location: /biblioteca/leitor');
                
            }

            if($usuarioData[0]['tipo'] == 'dono'){
                header('location: /biblioteca/dono');
            }

            if($usuarioData[0]['tipo'] == 'ambos'){
                header('location: /biblioteca/dashboard');
            }
            
        } else {
            header('location: /biblioteca/login');
        }
    }
}
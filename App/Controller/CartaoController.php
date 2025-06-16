<?php 

namespace App\Controller;

use Core\View\View;
use App\Repository\UsuarioRepository;
use Core\Security\Jwt\JwtHandler;
use App\Model\Usuario;


class CartaoController
{
    public function gerarCartao()
    {

    }

    public function meuCartao()
    {
        $data = [];
        if (session_id()) {
            $data = JwtHandler::validateToken($_SESSION['jwt']);
            $usuario = new Usuario();
        }

        // Obter dados do usuário logado
        $usuarioResultado = $usuario->findForSign($data['email']);
        if (!$usuarioResultado || empty($usuarioResultado)) {
            header('location: /biblioteca/login');
        }
        
        $usuarioResultado = $usuario->join('cartoes_virtuais as cv', 'usuarios.id = cv.usuario_id')->get(['usuarios.nome', 'cv.codigo', 'cv.criado_em']);


        $data = [
            'titulo' => 'Seu Cartão Virtual Bibliogo',
            'subtitulo' => 'Apresente este cartão ao dono do livro para registrar seu aluguel.',
            'usuario' => [
                'nome' => $usuarioResultado[0]['nome'],
                'data_cadastro' => date('d/m/Y', strtotime($usuarioResultado[0]['criado_em'])),
                'codigo' => $usuarioResultado[0]['codigo']
            ]
        ];

        $scripts = [
            '/assets/js/meu-cartao.min.js'
        ];

        $styles = [
            '/assets/css/admin/meu-cartao.min.css'
        ];

        return new View(
            view: 'admin/cartao-virtual/cartao-virtual', 
            vars: $data, 
            styles: $styles, 
            scripts: $scripts, 
            layout: 'admin-layout' 
        );
    }
}
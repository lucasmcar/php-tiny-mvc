<?php 

namespace App\Controller;

use Core\View\View;
use App\Model\User;
use App\Model\Administrador;
use App\Repository\DepoimentoRepository;
use Core\Security\Jwt\JwtHandler;

class HomeAdminController
{
    public function home()
    {
        if (!empty($_GET)) {
            // Redirecionar para /admin/home sem parâmetros
            header('Location: /admin/home');
            exit;
        }

        $data = [];
        if (session_id()) {
            $data = JwtHandler::validateToken($_SESSION['jwt']);
        }

        $user = new User();
        
        $userResult = $user->findForSign($data['email']);

        $user= new USer();

        $result = $user->where('criado_por','=', $userResult[0]['id'])->get();


        $data = [
            'title' => 'Administração',
            'totalUsers' => (count($result) > 0 ? count($result) : 0),
            'totalDepoimentos' => 3,
            'totalPosts' => 4,
            'totalEventos' => 10,
            'totalProjetos' => 14,
            'totalServicos' => 8,
        ];

        $styles = [
            '/assets/css/main-admin.min.css'
        ];
        $scripts =[
            'assets/js/main-admin.min.js'
        ];

        return new View('admin/home', $data, $styles, $scripts, 'admin-layout');
    }

    public function main()
    {
        $totalDepoimentos = new DepoimentoRepository();
        $totalDepoimentos = $totalDepoimentos->totalDepoimentos();

        $data = [
            'subtitulo' => 'Painel de Controle',
            'totaldepoimentos' => $totalDepoimentos,
        ];

        $styles = [
            '/assets/css/main.min.css'
        ];
        $scripts =[];

        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            return new View('admin/main', $data, $styles, $scripts, 'admin-layout');
        }

        
    }

    public function todosServicos()
    {
        
        $styles = [
            '/assets/css/servicos.min.css'
        ];
        $scripts =[
            '/assets/js/servicos.min.js'
        ];
        $data = [
            'title' => 'Todos os Serviços',
            'servicos' => [
                ["id" => 1, "icone" => "bi-file-earmark-text", "titulo" => "Análise de Propostas para Editais", "descricao" => "Revisão e análise detalhada de propostas para editais públicos e privados."],
                ["id" => 2, "icone" => "bi-lightbulb", "titulo" => "Elaboração de Projetos", "descricao" => "Criação de projetos estratégicos e personalizados para seu negócio."],
                ["id" => 3, "icone" => "bi-calendar-check", "titulo" => "Planejamento e Gerenciamento", "descricao" => "Organização e supervisão de projetos para garantir sua execução eficiente."],
                ["id" => 4, "icone" => "bi-cash-coin", "titulo" => "Prestação de Contas", "descricao" => "Gestão financeira e transparência na prestação de contas."],
                ["id" => 5, "icone" => "bi-mic", "titulo" => "Direção Artística e Produção Executiva", "descricao" => "Supervisão artística e logística para produções culturais e eventos."],
                ["id" => 6, "icone" => "bi-megaphone", "titulo" => "Consultoria de Mídias e Redes Sociais", "descricao" => "Estratégias para crescimento orgânico e campanhas pagas eficientes."],
                ["id" => 7, "icone" => "bi-newspaper", "titulo" => "Assessoria de Imprensa", "descricao" => "Divulgação estratégica para fortalecimento da marca na mídia."],
                ["id" => 8, "icone" => "bi-instagram", "titulo" => "Gestão de Mídias (Instagram, etc.)", "descricao" => "Gerenciamento profissional de redes sociais para engajamento e conversão."]
            ]
            ];

       
        return new View('admin/servicos', $data, $styles, $scripts, 'admin-layout');
            
        

    }

    public function createServico()
    {
        $data = [
            'title' => 'Criar Serviço',
        ];

        
    }


}
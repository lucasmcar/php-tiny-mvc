<?php

namespace App\Controller;

use App\Core\View\View;

use App\Repository\DepoimentoRepository;

class HomeController extends Controller
{

    public function welcome()
    {
        $data = [
            'title' => 'Welcome to Tiny!',
        ];

        return new View('welcome', $data);
    }

    public function index()
    {

        /*$depoimentos = new DepoimentoRepository();
        $depoimentosData = $depoimentos->verDepoimentos();*/

        $styles = [
            'assets/css/home.css',
        ];

        $scripts = [
            '/assets/js/main.js'
        ];

        $data = [
            'title' => 'HD Arte Produtora',
            'depoimentos' => [],
            'artigos' => [
                ['title' => 'Como Planejar um Evento de Sucesso', 'created_at' => '2025-03-22', 'slug' => 'como-planejar-evento-sucesso'],
                ['title' => 'Dicas de Produção Cultural', 'created_at' => '2025-03-20', 'slug' => 'dicas-producao-cultural'],
                ['title' => 'A Arte de Engajar o Público', 'created_at' => '2025-03-18', 'slug' => 'arte-engajar-publico'],
            ]
        ];


        return new View('site/home',  $data, $styles, $scripts);
    }


    public function sobre()
    {
        $data = [
            'title' => 'Sobre a HD Arte',
        ];

        return new View('site/sobre', $data);
    }

    public function servico()
    {
        $data = [
            'title' => 'Serviços',
        ];

        return new View('site/servicos', $data);
    }

    public function equipe()
    {

        $equipe = [
            ['nome' => 'Heloisa Peres', 'cargo' => 'Direção Artística e Produção Executiva', 'resumo' => 'Lidera a visão criativa da HD Arte, com anos de experiência em produção de eventos e direção artística.', 'foto' => '/assets/imgs/heloisa.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => true],
            ['nome' => 'Michelle Rodrigues', 'cargo' => 'Produção Cultural, Elaboração de Projetos', 'resumo' => 'Especialista em projetos culturais, Michelle traz inovação e paixão para a HD Arte.', 'foto' => '/assets/imgs/michelle.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => true],
            ['nome' => 'Lucas Carvalho', 'cargo' => 'CEO da Code Experts', 'resumo' => 'Desenvolvimento de sites, consultoria e gestão de redes.', 'foto' => 'lucas.jpg', 'redes' => ['instagram' => 'https://instagram.com/hej.lucasmcar', 'facebook' => 'https://www.facebook.com/lmcarvalho90', 'linkedin' => 'https://linkedin.com/in/lucas-m-carvalho'], 'destaque' => false],
            ['nome' => 'Carla Castro', 'cargo' => 'Assessoria de Imprensa', 'resumo' => 'Jornalista com mais de 20 anos de experiência, foca em comunicação estratégica para a cultura.', 'foto' => '/assets/imgs/carla-castro.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => false],
            ['nome' => 'Eduardo Raupp', 'cargo' => 'Comercial e Consultoria SC', 'resumo' => 'Responsável por estratégias comerciais e consultoria em SC.', 'foto' => '/assets/imgs/eduardo-raupp.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => false],
            ['nome' => 'Regina Retzel', 'cargo' => 'Assessoria e Produção Cultural', 'resumo' => 'Foco em projetos culturais para o 3º setor.', 'foto' => '/assets/imgs/regina-ritzel.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => false],
            
        ];



        $data = [
            'title' => 'Conheça nossa Equipe',
            'equipe' => $equipe
        ];

        return new View('site/equipe', $data);
    }

    public function depoimentos()
    {

        $depoimentos = new DepoimentoRepository();
        $depoimentosData = $depoimentos->verDepoimentos();




        $data = [
            'title' => 'Depoimentos',
            //Retornar depoimentos do banco de dados
            'depoimentos' =>  $depoimentosData,

        ];



        return new View('site/depoimento', $data);
    }

    public function contato()
    {
        $data = [
            'title' => 'Contato',
        ];

        return new View('site/contato', $data);
    }

    public function criaDepoimento()
    {
        $data = [
            'title' => 'Cria Depoimento',
        ];

        return new View('site/cria-depoimento', $data);
    }
}

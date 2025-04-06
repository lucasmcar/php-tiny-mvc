<?php

namespace App\Controller;

use App\Core\View\View;
use App\Helper\InputFilterHelper;
use App\Helper\JsonHelper;
use App\Helper\MailerHelper;
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

        $depoimentos = new DepoimentoRepository();
        $depoimentosData = $depoimentos->verDepoimentos();

        $styles = [
            'assets/css/home.css',
        ];

        $scripts = [
            '/assets/js/main.js'
        ];

        $data = [
            'title' => 'HD Arte Produtora',
            'depoimentos' => $depoimentosData,
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

        $styles = [
            'assets/css/sobre.css',
        ];

        $scripts = [
            '/assets/js/sobre.js'
        ];

        return new View(view: 'site/sobre', vars: $data, styles: $styles, scripts: $scripts);
    }

    public function servico()
{
    $data = [
        'title' => 'Serviços',
        'servicos' => [
            [
                "icone" => "bi-briefcase",
                "titulo" => "Consultoria e Assessoria de Projetos",
                "descricao" => "Atuamos no planejamento e desenvolvimento de projetos culturais, artísticos e esportivos, com foco em soluções criativas e estratégicas."
            ],
            [
                "icone" => "bi-mic-fill",
                "titulo" => "Palestras e Workshops",
                "descricao" => "Ministramos palestras e workshops especializados em diversas áreas culturais e de gestão de eventos."
            ],
            [
                "icone" => "bi-brush",
                "titulo" => "Elaboração de Projetos Culturais e Artísticos",
                "descricao" => "Criamos propostas para editais, elaboramos leis de incentivo e desenvolvemos projetos para diversas áreas culturais."
            ],
            [
                "icone" => "bi-building",
                "titulo" => "Eventos Corporativos",
                "descricao" => "Planejamos e executamos eventos e propostas para empresas, garantindo uma experiência personalizada e impactante."
            ],
            [
                "icone" => "bi-check-circle",
                "titulo" => "Avaliação e Submissão de Propostas",
                "descricao" => "Avaliamos e submetemos projetos em editais, maximizando as chances de aprovação."
            ],
            [
                "icone" => "bi-cash-stack",
                "titulo" => "Captação de Recursos",
                "descricao" => "Nosso foco é sempre fortalecer a rede de recursos disponíveis para os projetos, garantindo viabilidade e sucesso."
            ],
            [
                "icone" => "bi-music-note",
                "titulo" => "Produção Artística e Executiva",
                "descricao" => "Gerenciamos e realizamos a produção artística e executiva de eventos, desde a criação até a execução."
            ],
            [
                "icone" => "bi-kanban",
                "titulo" => "Gestão Completa de Projetos",
                "descricao" => "Oferecemos gerenciamento completo de projetos, garantindo que todas as etapas sejam realizadas com excelência e dentro do prazo."
            ],
            [
                "icone" => "bi-instagram", 
                "titulo" => "Gestão de Mídias (Instagram, etc.)", 
                "descricao" => "Gerenciamento profissional de redes sociais para engajamento e conversão.",
                "mais" => "Na HD Arte Produções e Eventos, oferecemos gestão estratégica de redes sociais, com foco no engajamento e conversão de seu público. Atuamos com parceiros e influencers, recomendando sempre aqueles que melhor se alinham com a identidade do seu projeto, ampliando sua presença digital e fortalecendo sua conexão com o público. A parceria com influenciadores é uma ferramenta poderosa para expandir o alcance e gerar resultados reais, trazendo uma abordagem autêntica e impactante para suas campanhas nas redes sociais.
"
            ]
        ]
    ];

    $styles = [
        '/assets/css/servicos-site.css'
    ];

    return new View(view: 'site/servicos', vars: $data, styles: $styles);
}

    public function equipe()
    {

        $equipe = [
            ['nome' => 'Heloisa Peres', 'cargo' => 'Direção Artística e Produção Executiva', 'resumo' => 'Lidera a visão criativa da HD Arte, com anos de experiência em produção de eventos e direção artística.', 'foto' => '/assets/imgs/heloisa.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => true],
            ['nome' => 'Michelle Rodrigues', 'cargo' => 'Produção Cultural, Elaboração de Projetos', 'resumo' => 'Especialista em projetos culturais, Michelle traz inovação e paixão para a HD Arte.', 'foto' => '/assets/imgs/michelle.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => true],
            ['nome' => 'Regina Retzel', 'cargo' => 'Assessoria e Produção Cultural', 'resumo' => 'Foco em projetos culturais para o 3º setor.', 'foto' => '/assets/imgs/regina-ritzel.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => false],
            ['nome' => 'Lucas Carvalho', 'cargo' => 'CEO da Code Experts', 'resumo' => 'Desenvolvimento de sites, consultoria e gestão de redes.', 'foto' => 'lucas.jpg', 'redes' => ['instagram' => 'https://instagram.com/hej.lucasmcar', 'facebook' => 'https://www.facebook.com/lmcarvalho90', 'linkedin' => 'https://linkedin.com/in/lucas-m-carvalho'], 'destaque' => false],
            ['nome' => 'Eduardo Raupp', 'cargo' => 'Comercial e Consultoria SC', 'resumo' => 'Responsável por estratégias comerciais e consultoria em SC.', 'foto' => '/assets/imgs/eduardo-raupp.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => false],
            ['nome' => 'Carla Castro', 'cargo' => 'Assessoria de Imprensa', 'resumo' => 'Jornalista com mais de 20 anos de experiência, foca em comunicação estratégica para a cultura.', 'foto' => '/assets/imgs/carla-castro.jpg', 'redes' => ['instagram' => '#', 'facebook' => '#', 'linkedin' => '#'], 'destaque' => false],
           
            
            
        ];



        $data = [
            'title' => 'Conheça nossa Equipe',
            'equipe' => $equipe
        ];

        $styles = [
            '/assets/css/equipe.css'
        ];

        return new View(view: 'site/equipe', vars: $data, styles: $styles);
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

        
        $styles = [
            '/assets/css/depoimentos-site.css'
        ];


        return new View(view: 'site/depoimento', vars: $data, styles: $styles);
    }

    public function contato()
    {
        $data = [
            'title' => 'Contato',
        ];

        $styles = [
            'assets/css/contato.css',
        ];

        $script = [
            'assets/js/contato.js'
        ];

        return new View(view: 'site/contato', vars: $data, styles: $styles, scripts: $script);
    }

    public function criaDepoimento()
    {
        $data = [
            'title' => 'Cria Depoimento',
        ];

        return new View(view: 'site/cria-depoimento', vars: $data);
    }

    public function enviarEmail()
    {
      
        header('Content-Type: application/json'); // Garante o tipo de conteúdo
        $data = InputFilterHelper::filterInputs(INPUT_POST, [
            'nome',
            'email',
            'assunto',
            'mensagem'
        ]);

        $emailHelper = new MailerHelper(
            $_ENV['E_HOST'],
            $_ENV['E_PORT'],
            $_ENV['E_USER'],
            $_ENV['E_PASS'],
            $_ENV['EF_EMAIL'],
            $_ENV['EF_NAME']
        );

        $emailHelper->addRecipient($_ENV['EF_EMAIL'], $_ENV['EF_NAME']);
        $emailHelper->setSubject($data['assunto']);
        $emailHelper->setBody($data['mensagem']);
        if($emailHelper->send()){
            echo JsonHelper::toJson(['success' => true]);
        } else {
            echo JsonHelper::toJson(['success' => false]);
        }
    }
}

<?php

namespace App\Controller;

use Core\View\View;
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


        $styles = [
            'assets/css/home.min.css',
        ];

        $scripts = [
            '/assets/js/main.min.js',
            '/assets/js/theme.min.js'
        ];

        $data = [
            'titulo' => 'BiblioGo - A sua biblioteca online',
            'livros' => [
                ['titulo' => 'O Pequeno Principe', 'capa' => '/assets/imgs/opequeno.webp', 'criado_em' => '2025-03-22', 'slug' => 'o-pequeno-principe','curto'=> 'Um clássico atemporal sobre amizade e descoberta', 'preco'=> 15.00],
                ['titulo' => '1984', 'capa' => '/assets/imgs/1984.webp', 'criado_em' => '2025-03-20', 'slug' => '1984', 'curto' => 'Uma distopia poderosa sobre controle e liberdade', 'preco' => 10.00],
                ['titulo' => 'Dom Casmurro', 'capa' => '/assets/imgs/domcasmurro.webp', 'criado_em' => '2025-03-18', 'slug' => 'dom-casmurro','curto' => 'Um romance clássico da literatura brasileira', 'preco'=>7.50],
            ]
        ];


        return new View('site/home',  $data, $styles, $scripts);
    }


    public function sobre()
    {
        $data = [
            'titulo' => 'Sobre o BiblioGo',
        ];

        $styles = [
            'assets/css/sobre.min.css',
        ];

        $scripts = [
            '/assets/js/sobre.min.js'
        ];

        return new View(view: 'site/sobre', vars: $data, styles: $styles, scripts: $scripts);
    }

    public function comoFunciona()
    {
        $data = [
            'titulo' => 'Como Funciona',
            'servicos' => [
                [
                    "icone" => "bi-person-plus",
                    "titulo" => "Facilitamos o Cadastro de Bibliotecas Pessoais",
                    "descricao" => "Crie sua conta e adicione livros com título, autor, número de páginas, fotos e estado físico de forma simples."
                ],
                [
                    "icone" => "bi-search",
                    "titulo" => "Conectamos Leitores a Livros Únicos",
                    "descricao" => "Busque livros por categoria e localização, veja fotos, descrições e estado antes de alugar."
                ],
                [
                    "icone" => "bi-cart-check",
                    "titulo" => "Promovemos o Aluguel Acessível de Livros",
                    "descricao" => "Alugue livros por um pagamento simbólico (disponível no MVP) e mantenha por até um mês."
                ],
                [
                    "icone" => "bi-share",
                    "titulo" => "Integração com Redes Sociais como Instagram",
                    "descricao" => "Compartilhe sua biblioteca pessoal e conecte-se com outros leitores diretamente pelo Instagram."
                ],
                [
                    "icone" => "bi-check-circle",
                    "titulo" => "Suporte para Aluguel e Devolução",
                    "descricao" => "Marque 'Terminei de ler' para registrar o tempo de leitura e finalize o aluguel com facilidade."
                ],
                [
                    "icone" => "bi-star",
                    "titulo" => "Avaliações para Confiança na Comunidade",
                    "descricao" => "Deixe e receba avaliações de 1 a 5 estrelas para construir uma rede confiável."
                ],
                [
                    "icone" => "bi-chat",
                    "titulo" => "Chat Direto entre Usuários",
                    "descricao" => "Comunique-se com o locador ou leitor para coordenar a entrega e devolução."
                ],
                [
                    "icone" => "bi-bookmark",
                    "titulo" => "Gestão de Bibliotecas Pessoais",
                    "descricao" => "Gerencie seus livros, defina preços com sugestões automáticas e aceite ou recuse pedidos de aluguel."
                ],
                [
                    "icone" => "bi-geo-alt",
                    "titulo" => "Busca por Localização Próxima",
                    "descricao" => "Encontre livros disponíveis perto de você com base na sua localização, sem exibir mapas.",
                    //de ou região) para sugerir livros próximos, facilitando trocas locais e seguras. Essa funcionalidade é projetada para conectar você a leitores e donos de livros na sua área, promovendo uma experiência comunitária e prática."
                ]
            ]
        ];

        $styles = [
            '/assets/css/como-funciona.min.css'
        ];

        return new View(view: 'site/como-funciona', vars: $data, styles: $styles);
    }

    public function biblioteca()
    {
        $data = [
            'title' => 'Bibliotecas',
            'bibliotec' => [
                [
                    'id' => 1,
                    'nome' => 'Biblioteca Literária',
                    'criador' => 'Ana Silva',
                    'localizacao' => 'São Paulo',
                    'numero_livros' => 45,
                    'nota' => 4.8
                ],
                [
                    'id' => 2,
                    'nome' => 'Estante do Saber',
                    'criador' => 'Carlos Oliveira',
                    'localizacao' => 'Rio de Janeiro',
                    'numero_livros' => 12,
                    'nota' => 4.7
                ],
                [
                    'id' => 3,
                    'nome' => 'Livros & Cia',
                    'criador' => 'Mariana Costa',
                    'localizacao' => 'Belo Horizonte',
                    'numero_livros' => 60,
                    'nota' => 4.5
                ],
                [
                    'id' => 4,
                    'nome' => 'Leitura Compartilhada',
                    'criador' => 'João Mendes',
                    'localizacao' => 'Curitiba',
                    'numero_livros' => 8,
                    'nota' => 4.3
                ],
                [
                    'id' => 5,
                    'nota' => 'Clube do Livro',
                    'criador' => 'Fernanda Lima',
                    'localizacao' => 'Porto Alegre',
                    'numero_livros' => 25,
                    'nota' => 4.2
                ]
            ]
        ];

        $styles = [
            '/assets/css/bibliotecas.min.css'
        ];

        $scripts = [
            '/assets/js/bibliotecas.min.js'
        ];

        return new View(view: 'site/biblioteca', vars: $data, styles: $styles);
    }

    public function cadastro()
    {

        $data = [
            'title' => 'Faça seu cadastro',
        ];

        $styles = [
            '/assets/css/cadastro.min.css'
        ];

        $scripts =[
            '/assets/js/cadastro-usuario.min.js'        
        ];

        return new View(view: 'site/cadastro', vars: $data, styles: $styles, scripts: $scripts);
    }

    /*public function depoimentos()
    {

        $depoimentos = new DepoimentoRepository();
        $depoimentosData = $depoimentos->verDepoimentos();




        $data = [
            'title' => 'Depoimentos',
            //Retornar depoimentos do banco de dados
            'depoimentos' =>  $depoimentosData,

        ];

        
        $styles = [
            '/assets/css/depoimentos-site.min.css'
        ];


        return new View(view: 'site/depoimento', vars: $data, styles: $styles);
    }

    public function contato()
    {
        $data = [
            'title' => 'Contato',
        ];

        $styles = [
            'assets/css/contato.min.css',
        ];

        $script = [
            'assets/js/contato.min.js'
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
    }*/
}
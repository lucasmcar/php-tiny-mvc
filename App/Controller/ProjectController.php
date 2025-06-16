<?php

namespace App\Controller;


use Core\View\View;

class ProjectController
{

    public function index()
    {
        // Lista de projetos (simulação - substitua por um repositório real)
        $projects = $this->fetchAllProjects();

        // Projetos em destaque (os mais importantes)
        $featuredProjects = array_filter($projects, function ($project) {
            return isset($project['is_featured']) && $project['is_featured'];
        });

        // Todos os projetos (exclui duplicatas se desejar)
        $allProjects = $projects;

        $data = [
            'title' => 'Projetos e Eventos Realizados',
            'featured_projects' => $featuredProjects,
            'all_projects' => $allProjects
        ];

        $styles = ['/assets/css/all-project.min.css'];
        $scripts = []; // Para interatividade, se necessário

        return new View('site/projetos', $data, $styles);
    }
    public function show($params)
    {
        $slug = $params[0] ?? '';

        $project = $this->fetchProjectBySlug($slug);
        if (!$project) {
            http_response_code(404);
            return new View('errors/404', ['title' => 'Projeto Não Encontrado'], [], [], 'main-layout');
        }

        error_log("Project Data: " . print_r($project, true));

        $data = [
            'title' => $project['title'],
            'project' => $project
        ];

        $styles = ['/assets/css/project.min.css'];
        $scripts = [
            '/assets/js/event-carousel.min.js'
        ];

        return new View('site/projects', $data, $styles, $scripts);
    }

    private function fetchProjectBySlug($slug)
    {
        $projects = [
            'festa-junina-2025' => [
                'title' => 'Festa Junina de Porto Alegre 2025',
                'description' => 'Uma das maiores festas culturais do estado.',
                'image' => '/assets/imgs/festa-junina-2025.jpg',
                'details' => 'Realizada em Junho de 2025.',
                'author' => 'João Silva',
                'post_date' => '2025-03-15',
                'content' => 'A Festa Junina de Porto Alegre 2025 foi um marco cultural, reunindo milhares de pessoas em uma celebração vibrante. Com apresentações de quadrilhas, shows de artistas locais e uma variedade de comidas típicas, o evento destacou a riqueza da tradição gaúcha. O planejamento começou meses antes, com captação de recursos via editais culturais.',
                'extra_image' => '/assets/imgs/festa-junina-2025-extra.jpg',
                'sponsors' => [
                    ['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 1'],
                    ['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 2'],
                ]
            ],

            'festival-musica-2024' => [
                'title' => 'Festival de Música 2024',
                'description' => 'Evento nacional com artistas renomados.',
                'image' => '/assets/imgs/festival-musica-2024.jpg',
                'details' => 'Ocorrido em Agosto de 2024.',
                'author' => 'Maria Oliveira',
                'post_date' => '2024-09-01',
                'content' => 'O Festival de Música 2024 trouxe ao palco grandes nomes da música brasileira, como Anitta e Jorge Ben Jor. Realizado em São Paulo, o evento contou com uma produção impecável e um público de mais de 10.000 pessoas. A HD Arte foi responsável pela gestão financeira e logística, garantindo o sucesso do festival.',
                'extra_image' => '/assets/imgs/festival-musica-2024-extra.jpg',
                'sponsors' => [
                    ['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 1'],
                    ['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 2'],
                ]
            ],

            'show-local-2023' => [
                'title' => 'Show local 2023',
                'description' => 'Evento local de 2023 com artistas renomados.',
                'image' => '/assets/imgs/show-local-2023.jpg',
                'details' => 'Ocorrido em Agosto de 2023.',
                'author' => 'Maria Paula',
                'post_date' => '2023-10-01',
                'content' => 'O festival local que ocorreu em agosto desse mes, trouxe grandes convidados da música nacional e também mostrou ao público revelações locais.',
                'extra_image' => '/assets/imgs/festival-musica-2024-extra.jpg',
                'sponsors' => [
                    ['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 1'],
                    ['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 2'],
                ]
            ],

            'circuito-urbano-restinga' => [
                'title' => 'Circuito Urbano Na Restinga 2025',
                'description' => 'Projeto de Cultura Hip Hop.',
                'image' => '/assets/imgs/cur.jpg',
                'details' => 'Será realizado em Maio de 2025, nos dias 2, 3 e 4',
                'author' => 'Michelle Rodrigues',
                'post_date' => '2025-03-15',
                'content' => 'O Circuito Urbano na Restinga 2025 é um projeto que visa promover a cultura Hip Hop na região da Restinga, em Porto Alegre. Com oficinas, shows e intervenções artísticas, o evento busca valorizar a produção cultural local e incentivar a participação da comunidade. A HD Arte é parceira nessa iniciativa, contribuindo com a produção e divulgação do projeto.',
                'extra_image' => '/assets/imgs/cur-extra.jpg',
                'sponsors' => [
                    /*['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 1'],
                    ['logo' => '/assets/imgs/logo1.jpg', 'name' => 'Patrocinador 2'],*/
                ],
                'shows' => [
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show1.jpeg',
                        'description' => '',
                        'slug' => 'show1',
                        'participants' => 'DJ N1ngu3m'
                    ],
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show2.jpeg',
                        'description' => '',
                        'slug' => 'show2',
                        'participants' => 'DJ Caramão'
                    ],
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show3.jpeg',
                        'description' => '',
                        'slug' => 'show2',
                        'participants' => 'DJ Nezzo'
                    ],
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show4.jpeg',
                        'description' => '',
                        'slug' => 'show2',
                        'participants' => 'DJ JB'
                    ],
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show5.jpeg',
                        'description' => '',
                        'slug' => 'show2',
                        'participants' => 'DJ Serginho'
                    ],
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show6.jpeg',
                        'description' => '',
                        'slug' => 'show2',
                        'participants' => 'Jeff Conex'
                    ],
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show7.jpeg',
                        'description' => '',
                        'slug' => 'show2',
                        'participants' => 'Restinga Crew'
                    ],
                    [
                        'title' => 'Atração Confirmada',
                        'image' => '/assets/imgs/cur/show8.jpeg',
                        'description' => '',
                        'slug' => 'show2',
                        'participants' => 'Família Preto Bom'
                    ],
                    
                    
                ],
                'attractions' => [
                    [
                        'title' => 'Ana Scarceli',
                        'description' => 'Ana, artesã e artista autodidata há 23 anos, começou em 2012 a se expressar pelas ruas através do grafitti. Ao longo de sua trajetória, 
                        pintando e estudando, também tem se dedicado ao muralismo.
                            Dentre os diversos temas de seus trabalhos, nota-se um carinho especial à fauna e à flora, preservação do meio ambiente, luta pelos direitos das mulheres, 
                            valorização dos povos originários e muitos outros.
                            Tem participado de diversos eventos de arte urbana importantes e tem suas artes espalhadas pelas ruas, periferias, comércios, casas e locais influentes, como por exemplo, o Museu do Hip Hop RS, 
                            Casa da Cultura do Hip Hop de Esteio, Casa do Hip Hop Rubem Berta.
                            Acredita que a arte transforma não só os lugares como também a vida das pessoas.',
                        'image' => '/assets/imgs/cur/cur1.jpeg'
                    ],
                    [
                        'title' => 'Marcus Gorga',
                        'description' => 'Marcus Gorga é um artista autodidata que iniciou
                            a desenhar em 1997 com referências em desenho
                            animado, história em quadrinhos, skate, cultura
                            de rua e a cultura indígena brasileira. 

                            Cria livremente, buscando o primitivo do homem e suas origens, o inconsciente inquieto do multiartista que nos
                            revela uma transição em torno de um caminho
                            trilhado por suas intervenções urbanas com graffiti desde 2011, estêncil e incansáveis adesivos, demarcando suas
                            novas experiências e linguagens experimentais.

                            Criador da oficina ateliê urbano onde fala sobre a
                            cultura hip hop e ensina técnicas com spray.',
                        'image' => '/assets/imgs/cur/cur2.jpeg'
                    ],
                    [
                        'title' => 'Sabrina Brum',
                        'description' => 'Sabrina  Brum nascida em Tupanciretã RS em 1984,conheceu o Graffiti no ano de 2000  é mãe, Oficineira, arte educadora, educadora social, artesã , grafiteira ,horticultura e mediadora no Museu Hip Hop RS, residente no bairro Restinga é protagonista e ativista de sua comunidade ,tem seu estilo próprio, técnicas e ações ,ativista da cultura Hip Hop a muitos anos ,foi uma das pesquisadoras do Museu do Hip Hop do RS, é uma das fundadoras do Coletivo Ação Mulher cujo intuito é promover a igualdade de gênero ,Co- fundadora do coletivo Gurias do Graffiti que  visa formar novas escritoras urbanas, já participou de diversos encontros nacionais e internacionais de Graffiti, atualmente é oficineira  de arte e cultura, educadora social  e Mediadora do Museu do Hip Hop RS, cultiva plantas , universo pelo qual é sua inspiração na arte e na vida.
                            Traz em suas obras o traço lúdico e colorido  ao mesmo tempo a representação feminina de diversas formas, raças e gêneros, valorizando sempre  o poder de transformação social que a arte reflete.',
                        'image' => '/assets/imgs/cur/cur3.jpeg'
                    ],
                    /*[
                        'title' => 'Jaque Vieira',
                        'description' => 'Sem informações adicionais',
                        'image' => '/assets/imgs/cur/cur4.jpeg'
                    ],*/
                    [
                        'title' => 'Allan Vieira',
                        'description' => 'Allan Vieira é grafiteiro, nascido em Porto Alegre, usa arte urbana commo ferramenta de transformação social.
                            Seu trabalho valoriza a cultura negra e busca fortalecer o empoderamento do povo preto, levando cor, identidade e resistência para as ruas.
                            Através do graffiti, expressa narrativas de luta, orgulho e ancestralidade, criando conexões e inspirando mudanças.',
                        'image' => '/assets/imgs/cur/cur5.jpeg'
                    ],
                    [
                        'title' => 'Felipe Reis',
                        'description' => 'Felipe Reis, natural de Porto Alegre, desde criança um apaixonado pela arte do desenho, conheceu o graffiti em 1992, através das revistas de skate. Residiu em 98 na cidade do Rio de janeiro por 16 anos onde se  desenvolveu como arte educador por toda sua trajetória em projetos sociais na Rocinha e Complexo da Maré. Retornou a Porto Alegre em 2014, em trabalhos comerciais, curadorias de exposições e seu projeto de estudos de abordagem antropológica da cultura HIP HOP num todo. Seu estilo traz um histórico retratando a velha escola e seus estilos, a origem sendo mantida em suas pinturas de quem construiu os pilares dessa ancestralidade que carregamos.',
                        'image' => '/assets/imgs/cur/cur6.jpeg'
                    ],
                    [
                        'title' => 'Adalberto Reis',
                        'description' => 'Adalberto Reis dos Santos, 39 anos, é pai, grafiteiro, arte-educador e assistente social. Há 18 anos usa a rua como suporte para suas manifestações urbanas, criando uma identidade estética com personagens e stencil. Atua também como educador em oficinas de graffiti em eventos como o Seminário Juventudes Negras e Políticas Públicas (PUCRS) e participou da exposição coletiva Presença Negra no MARGS em 2022.
                            Sua escolha pela rua como suporte se conecta à cultura hip hop e à democratização da arte. Sua produção é marcada pelo ativismo político, afirmando a negritude e combatendo racismo, discriminação e guetofobia. Assinando como Triafu, além das ruas, o artista também trabalha com telas, roupas, tênis e garrafas customizadas. Sua arte é uma forma de luta pela diversidade, pela cidadania e pelo respeito à vida.',
                        'image' => '/assets/imgs/cur/cur7.jpeg'
                    ],
                    [
                        'title' => 'Leandro Alves',
                        'description' => 'Natural de Rosário do Sul, 
                            Leandro Alves é um artista visual autodidata e atua na Arte Urbana há 20 anos.
                            Na sua trajetória experimentou diversos segmentos artísticos, dentre eles a pixação, o graffiti, a pintura mural, a pintura em telas, os artesanatos e as esculturas. Atualmente vem se dedicando a pintura e aos estudos do realismo e surrealismo, atuando tanto em murais públicos, na rua, ao alcance e acesso de todos, 
                            quanto em trabalhos comerciais, privados, em empresas, comércios e residências. 
                            Sempre na busca de transformar os espaços através da arte, dando uma vida, uma cara nova, levando cores, trazendo diálogos, reflexões, questionamentos, dialogando ou as vezes contrastando com o
                            contexto local. 
                            Nas suas obras, questões sociais, culturais e
                            ambientais são grandes fontes de inspiração.
                            Tem trabalhos e já participou de eventos e festivais em várias cidades, como Porto Alegre, Caxias do Sul, além de
                            Florianópolis, Rio de Janeiro e São Paulo.',
                        'image' => '/assets/imgs/cur/cur8.jpeg'
                    ],
                    [
                        'title' => 'Marcinha Dark',
                        'description' => 'Nascida em Porto Alegre, RS, Marcinha Dark é uma artista e empreendedora atuante desde 2018.
                            Ilustradora e graffiteira, consolidou-se na cena da arte urbana gaúcha a partir de 2021. Como
                            autodidata, desenvolveu uma assinatura artística autêntica e autoral, pautada na liberdade de
                            expressão e no empoderamento feminino.
                            Marcinha participou de diversos festivais, incluindo Resistinta 2024 (RS),Santa jam 2024 (RS)
                            Colorindo o bairro 2024 (RS), Rolezada das gurias 2024 (RS), Hip Hop Sul Regional 2024 (RS) Nest
                            Support Sala Hip Hop 2024 (RS), 3º Válvula de Escape 2024 (SC), Festival Agenda Urbana: Graffiti
                            Delas 2024 (SC), Origraffes 2023 (ES), Santa Jam 10 Anos AVU – Ateliê Vivências Urbanas 2023
                            (RS), 1° Festival Elas na Rua 2023 (RS), Pimp My Carroça 2023 (RS), Quebrada Virou Postal 2023
                            (RS) e Museu Mais Cores: Museu do Hip Hop 2022 (RS), entre outros.
                            Como educadora, atuou na ACHE: Casa de Cultura Hip Hop de Esteio durante o ano letivo de 2022.
                            Além disso, ministrou palestras e workshops, como o Workshop de Empreendedorismo – RS Criativo
                            e Projovem 2023 (RS), a Oficina de Artes: Conceito Arte e Viva Elizabeth 2023 (RS) e a Oficina na
                            31ª Porto Alegre em Cena 2024 (RS).
                            Sua personagem icônica, "Fridinha", é inspirada na artista mexicana Frida Kahlo. Com um estilo em
                            cartoon, a personagem celebra a feminilidade, destacando a beleza, força e coragem das mulheres.
                            Fridinha simboliza a presença feminina como protagonista nos trabalhos de Marcinha, reforçando a
                            mensagem de empoderamento e representatividade.',
                        'image' => '/assets/imgs/cur/cur9.jpeg'

                    ],
                    [
                        'title' => 'Paulo Ice',
                        'description' => 'Paulo ice natural de Porto Alegre,
                            Comecei a pintar graffiti em 1997 por curiosidade e hobb, me apaixonei pela cultura e pela técnica.
                            Em 2003 me aperfeiçoei e comecei a ministrar oficinas e workshop pela cidade e estado. Sou autodidata há mais de 20 anos.
                            Nos dia de hoje minha principal renda é o graffiti de  oficinas e trabalhos comerciais.',
                        'image' => '/assets/imgs/cur/cur10.jpeg'
                    ],
                    [
                        'title' =>  'Lisi',
                        'description' => 'Desde 2015 participa de feiras gráficas e exposições, 
                            em 2017 começou a levar seus desenhos do papel para a rua e 
                            desde então segue realizando trabalhos comerciais, pintando fachadas e fazendo parte de eventos de graffiti em POA e outras cidades. Suas personagens buscam representar as mulheres e celebrar as diversidades. Por meio de traços lúdicos, suas pinturas também se conectam muito com as crianças. Atualmente cursa Licenciatura em Artes Visuais na UFRGS e atua também como arte educadora ministrando oficinas de graffiti, utilizando essa ferramenta como uma forma de diálogo e empoderamento das comunidades através da arte.',
                        'image' => '/assets/imgs/cur/cur11.jpeg'
                    ],
                    [
                        'title' =>  'Taina Moxa',
                        'description' => 'Taina Moxa é artista urbana em expansão, há 10 anos leva a arte pelas ruas. Suaa expressão tem características de um ritual qye vai desde as cores ao traço, além de elementos 
                            que compõem suas artes e que acompanham em sua busca por autoconhecimento, 
                            para que se desperte todas as mulheres.',
                        'image' => '/assets/imgs/cur/cur12.jpeg'
                    ],
                    [
                        'title' =>  'Rikardo Dias',
                        'description' => 'Rikardo Diasm conhecido na cena do graffiti como Riko, é professor da rede pública de ensino, artista visual e escritor de graffiti.
                            Sua relação com a arte urbana começou em 2004, e desde então, o escritor faz intervenções pela cidade.
                            Em meados de 2011, sua carreira como arte-educador começou a se consolidar, bem como seu trabalho autoral,
                            reconhecidos pelas letras e o personagem, geralmente na cor rosa e com traços retilíneos.
                            Ministrou oficinas na Casa de Cultura Mario Quintana, Centros Comunitários e escolas. 
                            Também participou de exposições no Museu de Arte Contemporânea do RS, Gasômetro e Assembléia Legislativa do RS.
                            Atualmente segue ativo na cena gaúcha de graffiti, focado em realizar pintura em locais descentralizados,
                            principalmente no extremo sul de Porto Alegre.',
                        'image' => '/assets/imgs/cur/cur13.jpeg'
                    ],





                // Adicione mais atrações aqui, se necessário
                ]
            ],
            'campanha-pre-lancamento' => [
                'title' => 'Pré-lançamento de Campanha de Financiamento Coletivo',
                'description' => 'Campanha de financiamento coletivo na plataforma Benfeitoria.',
                'image' => '/assets/imgs/otto.jpg',
                'details' => 'Será realizado, amanhã dia 12 de Abril',
                'author' => 'HD Arte',
                'post_date' => '2025-04-11',
                'content' => '
                    <div class="campaign-content">
                        <h3 class="campaign-title">🎶 A MÚSICA NÃO PARA — E VOCÊ PODE FAZER PARTE DESSA HISTÓRIA! 🎤</h3>
                        <p class="hda-text">Otto Gomes, cantor e compositor com mais de 30 anos de trajetória, está lançando seu novo EP autoral com 5 faixas inéditas e 3 videoclipes incríveis! Esse projeto marca um novo momento em sua carreira solo, e você pode ajudar a transformar esse sonho em realidade. 🙌</p>

                        <p class="hda-text">💥 Essa é uma campanha de financiamento coletivo na plataforma Benfeitoria! Otto quer expandir sua música, fortalecer sua identidade artística e levar sua arte a mais pessoas – mas para isso, ele precisa de você.</p>

                        <p class="hda-text">👨‍👧‍👦 Pai de 5 filhos e com uma história marcada por dedicação à música, Otto acredita no poder da arte para transformar vidas – inclusive a dele e a de quem ouve.</p>

                        <h4 class="campaign-subtitle">✨ Tem recompensas lindas pra quem apoiar!</h4>
                        <p class="hda-text">Ajude e ainda ganhe experiências exclusivas, conteúdos especiais e muito mais!</p>

                        <h4 class="campaign-subtitle">📲 Acesse agora e contribua:</h4>
                        <ul class="campaign-links">
                            <li><a href="https://www.benfeitoria.com/ottogomes" target="_blank" class="btn btn-hda">Apoie na Benfeitoria 👉</a></li>
                            <li><span class="link-label">📸 Siga e compartilhe:</span> <a href="https://www.instagram.com/ottogomesmusic" target="_blank" class="campaign-link">@ottogomesmusic</a></li>
                            <li><span class="link-label">🌐 Saiba mais em:</span> <a href="https://www.hdarte.com.br" target="_blank" class="campaign-link">www.hdarte.com.br</a></li>
                            <li><span class="link-label">📧 Contato:</span> <a href="mailto:hdarteprodutora@hdarte.com.br" class="campaign-link">hdarteprodutora@hdarte.com.br</a></li>
                        </ul>

                        <h4 class="campaign-subtitle">📢 Como você pode ajudar (além de contribuir):</h4>
                        <ul class="campaign-actions">
                            <li>✅ Compartilhe com seus amigos e grupos!</li>
                            <li>✅ Envie para aquela pessoa que ama música e valoriza a arte local!</li>
                            <li>✅ Poste nas suas redes com a hashtag <strong>#OttoGomesNaBenfeitoria</strong></li>
                        </ul>

                        <p class="hda-text campaign-cta">Vamos juntos fazer esse projeto acontecer? 💛</p>
                    </div>
                ',
                'extra_image' => '/assets/imgs/otto-extra.jpg',
                'sponsors' => []
            ]
        ];


        return $projects[$slug] ?? null;
    }

    public function approved()
    {
        $projects = $this->fetchAllProjects();
        $approvedProjects = array_filter($projects, function ($project) {
            return $project['status'] === 'approved';
        });
        return $this->renderProjects('Projetos Aprovados', $approvedProjects);
    }

    public function ongoing()
    {
        $projects = $this->fetchAllProjects();
        $ongoingProjects = array_filter($projects, function ($project) {
            return $project['status'] === 'ongoing';
        });
        return $this->renderProjects('Projetos em Andamento', $ongoingProjects);
    }

    private function fetchAllProjects()
    {

        /* 'Territórios Criativos - SEDAC RS 2025
RS - Seguro Circuito Urbano na Restinga - Projeto de Cultura Hip Hop
Projeto PNAB 32/RS Música - Memória Popular 4 Edições
Festival Zumbi PNAB RS - Festivais e Mostras
PNAB POA - LIGA dos Blocos de Carnaval de Rua Descentralizados de Porto Alegre e RS
Nossa Arte Circula RS 2024 - 2 Projetos Contemplados
Especial Luiz Melodia de Paulo Dionísio e Gilberto Oliveira
Meu Canto de Luciara Batista
Arte Negra do Sul Edição 2025 - Roaunet';*/

        return [
            'circuito-urbano-restinga' => [
                'title' => 'Circuito Urbano na Restinga 2025',
                'slug' => 'circuito-urbano-restinga',
                'description' => 'Projeto de Cultura Hip Hop.',
                'short_description' => 'Rio Grande do Sul, 2025 - Público: a estimar',
                'image' => '/assets/imgs/cur.jpg',
                'video' => '',
                'is_featured' => true,
                'status' => 'ongoing' // Aprovado
            ],
            'festa-junina-2025' => [
                'title' => 'Festa Junina de Porto Alegre 2025',
                'slug' => 'festa-junina-2025',
                'description' => 'Uma das maiores festas culturais do estado.',
                'short_description' => 'Porto Alegre, 2025 - Público: 5.000',
                'image' => '/assets/imgs/festa-junina-2025.jpg',
                'video' => '/assets/videos/festa-junina-2025-trailer.mp4',
                'is_featured' => true,
                'status' => 'approved' // Aprovado
            ],
            'festival-musica-2024' => [
                'title' => 'Festival de Música 2024',
                'slug' => 'festival-musica-2024',
                'description' => 'Evento nacional com artistas renomados.',
                'short_description' => 'São Paulo, 2024 - Público: 10.000',
                'image' => '/assets/imgs/festival-musica-2024.jpg',
                'video' => null,
                'is_featured' => true,
                'status' => 'approved' // Aprovado
            ],
            'show-local-2023' => [
                'title' => 'Show Local 2023',
                'slug' => 'show-local-2023',
                'description' => 'Evento comunitário com artistas locais.',
                'short_description' => 'Curitiba, 2023 - Público: 2.000',
                'image' => '/assets/imgs/show-local-2023.jpg',
                'video' => null,
                'is_featured' => false,
                'status' => 'approved' // Em andamento
            ],
            'campanha-pre-lancamento' => [
                'title' => 'Campanha de Benfeitoria',
                'slug' => 'campanha-pre-lancamento',
                'description' => 'Campanha de financiamento coletivo na plataforma Benfeitoria.',
                'short_description' => 'Online, 2025 - Objetivo: 1.500',
                'image' => '/assets/imgs/otto.jpg',
                'video' => null,
                'is_featured' => false,
                'status' => 'ongoing'
            ] // Em andamento
        ];
    }

    private function renderProjects($title, $projects)
    {
        $featuredProjects = array_filter($projects, function ($project) {
            return isset($project['is_featured']) && $project['is_featured'];
        });

        $data = [
            'title' => $title,
            'featured_projects' => $featuredProjects,
            'all_projects' => $projects
        ];

        $styles = ['/assets/css/project.min.css'];


        return new View('site/projetos', $data, $styles);
    }
}

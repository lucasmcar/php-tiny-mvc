<?php

namespace App\Controller;

use Core\View\View;

class BlogController
{
    public function posts()
    {
        $data = [
            'title' => 'Posts',
            'posts' => [
                ["id" => 1, "titulo" => "Post 1", "conteudo" => "Conteúdo do post 1"],
                ["id" => 2, "titulo" => "Post 2", "conteudo" => "Conteúdo do post 2"],
                ["id" => 3, "titulo" => "Post 3", "conteudo" => "Conteúdo do post 3"],
                ["id" => 4, "titulo" => "Post 4", "conteudo" => "Conteúdo do post 4"],
                ["id" => 5, "titulo" => "Post 5", "conteudo" => "Conteúdo do post 5"],
                ["id" => 6, "titulo" => "Post 6", "conteudo" => "Conteúdo do post 6"],
                ["id" => 7, "titulo" => "Post 7", "conteudo" => "Conteúdo do post 7"],
                ["id" => 8, "titulo" => "Post 8", "conteudo" => "Conteúdo do post 8"],
                ["id" => 9, "titulo" => "Post 9", "conteudo" => "Conteúdo do post 9"],
                ["id" => 10, "titulo" => "Post 10", "conteudo" => "Conteúdo do post 10"]
            ]
        ];

        $styles = [
            '/assets/css/posts.min.css'
        ];

        $scripts = [
        '/assets/js/main-admin.min.js',
            '/assets/js/posts.min.js'
        ];

        if ($_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: text/json');
            return new View('admin/posts', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function post($params)
    {
        $postId = $params[0] ?? '';

        $post = $this->fetchPostsById($postId);
        if (!$post) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Post não encontrado']);
            exit; // Garante que nada mais seja executado
        }

        $data = [
            'id' => $postId,
            'titulo' => $post['titulo'],
            'created_at' => $post['created_at'],
            'image' => $post['image'] ?? null,
            'conteudo' => $post['conteudo']
        ];

        // Verifica se é uma requisição AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode($data);
            exit; // Força o término da execução após enviar JSON
        } else {
            // Para requisições não-AJAX, você pode redirecionar ou retornar algo diferente
            http_response_code(400);
            echo json_encode(['error' => 'Requisição inválida']);
            exit;
        }
    }

    private function fetchPostsById($id)
    {
        $posts = [
            "1" => [
                "titulo" => "Post 1",
                "conteudo" => "Conteúdo do post 1",
                "created_at" => "2021-10-10 10:00:00",
                "author" => "Admin",
                "image" => "/assets/imgs/post1.jpg"
            ],
            "2" => [
                "titulo" => "Post 2",
                "conteudo" => "Conteúdo do post 2",
                "created_at" => "2021-10-15 10:00:00",
                "author" => "Colaborador",
                "image" => "/assets/imgs/post2.jpg"
            ],
            "3" => [
                "titulo" => "Post 3",
                "conteudo" => "Conteúdo do post 3",
                "created_at" => "2021-10-20 10:00:00",
                "author" => "Admin",
                "image" => "/assets/imgs/post3.jpg"
            ],
            "4" => [
                "titulo" => "Post 4",
                "conteudo" => "Conteúdo do post 4",
                "created_at" => "2021-10-25 10:00:00",
                "author" => "Colaborador",
                "image" => "/assets/imgs/post4.jpg"
            ],
            "5" => [
                "titulo" => "Post 5",
                "conteudo" => "Conteúdo do post 5",
                "created_at" => "2021-10-30 10:00:00",
                "author" => "Admin",
                "image" => "/assets/imgs/post5.jpg"
            ],
            "6" => [
                "titulo" => "Post 6",
                "conteudo" => "Conteúdo do post 6",
                "created_at" => "2021-11-05 10:00:00",
                "author" => "Colaborador",
                "image" => "/assets/imgs/post6.jpg"
            ],
            "7" => [
                "titulo" => "Post 7",
                "conteudo" => "Conteúdo do post 7",
                "created_at" => "2021-11-10 10:00:00",
                "author" => "Admin",
                "image" => "/assets/imgs/post7.jpg"
            ],
            "8" => [
                "titulo" => "Post 8",
                "conteudo" => "Conteúdo do post 8",
                "created_at" => "2021-11-15 10:00:00",
                "author" => "Colaborador",
                "image" => "/assets/imgs/post8.jpg"
            ],
            "9" => [
                "titulo" => "Post 9",
                "conteudo" => "Conteúdo do post 9",
                "created_at" => "2021-11-20 10:00:00",
                "author" => "Admin",
                "image" => "/assets/imgs/post9.jpg"
            ],
            "10" => [
                "titulo" => "Post 10",
                "conteudo" => "Conteúdo do post 10",
                "created_at" => "2021-11-25 10:00:00",
                "author" => "Colaborador",
                "image" => "/assets/imgs/post10.jpg"
            ]
        ];

        return $posts[$id] ?? null;
    }

    public function novoBlog()
    {
        $data = [
            'title' => 'Novo Artigo',
        ];

        $styles = [
            '/assets/css/admin/blog.min.css'
        ];

        $scripts =[
            '/assets/js/ckeditor5/ckeditor5.js'
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            // Para requisições AJAX, renderize apenas o conteúdo
            return new View('admin/blog', $data, $styles, $scripts, 'admin-layout');
        } else {
            // Para requisições normais, também renderize o layout
            return new View('admin/blog', $data, $styles, $scripts, 'admin-layout');
        }
    }

    public function mostrarArtigo($params)
    {
            
        $createdAt = $params[0];
        $slug = $params[1];
        // Simulação de busca no banco de dados
        $data = [
            'article' =>[
            'title' => 'Como Planejar um Evento de Sucesso',
            'created_at' => '2025-03-22',
            'slug' => 'como-planejar-evento-sucesso',
            'content' => '<p>Planejar um evento requer organização e criatividade. Aqui estão algumas dicas:</p><ul><li>Defina o objetivo</li><li>Escolha o local</li></ul><img src="/assets/imgs/evento.jpg" alt="Evento">'
            ]
            
        ];

        $styles =['/assets/css/artigo.min.css'];
        // Verificar se o artigo existe
        //if ($article['created_at'] === $createdAt && $article['slug'] === $slug) {
            return new View('site/artigo',  $data, $styles, [], 'layout');
        //}
        // Redirecionar ou mostrar erro 404 se não encontrado
        header("HTTP/1.0 404 Not Found");
        exit('Artigo não encontrado');
    }
}
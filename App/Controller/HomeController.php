<?php

namespace App\Controller;

use App\Core\Security\Csrf;
use App\Core\View\View;
use App\Repository\TesteRepository;

class HomeController extends Controller
{
    public function welcome()
    {
        $data = [
            'name' => 'Lucas',
            'items' => ['1', '2', '3'],
            'title' => 'Home Page',
            'data' => ['empty', 'data']

        ];
        
        return new View('welcome', $data);
    }
    public function index() 
    {

        $data = [
            'name' => 'Lucas',
            'items' => ['1', '2', '3'],
            'title' => 'Home Page',
            'data' => ['empty', 'data']

        ];
        
        return new View('index', $data);
    }

    public function list() 
    {
        $model = new TesteRepository();
        $array = $model->getTest();
        
        $data = [
            /**
             * Para passar dados para tela, criar um array associativo
             * os dados que serão listas devem ter o formato como na posição dados
             */
            'title' => 'Teste',
            'dados' => [
                ['id' => 1, 'data' => 'empty', 'datas' => 'data', 'dates' =>'12/12/2020'],
                ['id' => 2,'data' => 'full', 'datas' =>'null', 'dates' =>  '12/10/2020'],
                ['id' => 3,'data' => 'semi-full', 'datas' =>'null', 'dates' =>  '12/10/2020'],
                
            ],

            'itens' =>[$array][0]
            
                
                /*['id' => 1,'data' => 'empty', 'datas' => 'data', 'dates' =>'12/12/2020'],
                ['id' => 2,'data' => 'full', 'datas' =>'null', 'dates' =>  '12/10/2020'],
                ['id' => 3,'data' => 'semi-full', 'datas' =>'null', 'dates' =>  '12/10/2020'],*/
                
            
        ];

        
        
        return new View('list',$data);
    }

    public function showPost($id, $postId = null, $c = null, $commentId = null)
    {
        echo "Exibindo informações do usuário com ID: " . htmlspecialchars($id);

        if ($postId && !$c && !$commentId) {
            echo " e o post com ID: " . htmlspecialchars($postId) . "";
            // Aqui você pode adicionar a lógica para buscar o post no banco de dados
        } else if($c && $commentId){
            echo " e o post com ID: " . htmlspecialchars($postId) . " e comentario $commentId";
        } 
        else {
            echo " e todos os posts.";
            // Aqui você pode adicionar a lógica para buscar todos os posts do usuário no banco de dados
        }
    }

    public function teste($id)
    {
        echo "Exibindo informações do usuário com ID: " . htmlspecialchars($id);
    }

    public function settings()
    {
        $data = [
            'title' => 'Configurações',
            'key' => 'Caiu no settings'];
        return new View('settings', $data);
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? '';

            var_dump($token);
        
            if (!Csrf::verifyToken($token)) {
                die('CSRF token validation failed');
            }
        
            // Processar o formulário
        }
    }

}
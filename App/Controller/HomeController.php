<?php

namespace App\Controller;

use App\Core\Security\Csrf;
use App\Core\View\View;
use App\Core\Http\Request;
use App\Core\Http\Response;

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
        $data = [
            'title' => 'Teste',
            'dados' => [
                ['id' => 1, 'data' => 'empty', 'datas' => 'data', 'dates' =>'12/12/2020'],
                ['id' => 2,'data' => 'full', 'datas' =>'null', 'dates' =>  '12/10/2020'],
                ['id' => 3,'data' => 'semi-full', 'datas' =>'null', 'dates' =>  '12/10/2020'],
                
            ],

            'itens' => [
                ['id' => 1,'data' => 'empty', 'datas' => 'data', 'dates' =>'12/12/2020'],
                ['id' => 2,'data' => 'full', 'datas' =>'null', 'dates' =>  '12/10/2020'],
                ['id' => 3,'data' => 'semi-full', 'datas' =>'null', 'dates' =>  '12/10/2020'],
                
            ]
        ];
        
        return new View('list',$data);
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
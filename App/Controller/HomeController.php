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
            'title' => 'Welcome to Tiny!',
        ];
        
        return new View('welcome', $data);
    }
}

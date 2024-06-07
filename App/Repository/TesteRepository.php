<?php

namespace App\Repository;

use App\Connection\DB;
use App\Model\Teste;

class TesteRepository
{

    private $model;
    private $db;

    public function __construct()
    {
        $this->db = new DB;
        $this->model = new Teste($this->db);    
    }

    public function getTest()
    {
        return $this->model->getTeste();
    }
}
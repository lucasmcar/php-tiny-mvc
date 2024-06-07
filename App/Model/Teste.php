<?php

namespace App\Model;

use App\Connection\Builder;
use App\Connection\DB;

class Teste
{
    
    private $table = 'teste';
    private $db;
    private $qb;

    public function __construct(DB $db)
    {
        $this->db = $db;
        $this->qb = new Builder($this->db);
    }
    

    public function getTeste()
    {
        $result = $this->qb->table($this->table)->select()->get();
        return $result;
    }

    public function save()
    {
        $data = [
            'nome' => "Lucas C",
            'idade' => 26
        ];
        $this->qb->table($this->table)->save($data);
    }
    
}
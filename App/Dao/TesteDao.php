<?php 

namespace App\Dao;

use App\Connection\DB;

class TesteDao
{
    private $con;

    public function __construct()
    {
       // $this->con =  $db;
    }

    public function selectAllData() 
    {
        $sql = "SELECT * FROM teste";
        $this->con->query($sql);
        $resultado = $this->con->rs();
        return  $resultado;
    } 

    
}
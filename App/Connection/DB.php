<?php

namespace App\Connection;

use PDO;
use PDOStatement;

class DB implements IConnection
{
    private $user;
    private $db;
    private $host;
    private $password;
    private $instance;
    private $stmt;

    public function __construct()
    {
        $this->user = $_ENV['USER'];
        $this->password = $_ENV['PASS'];
        $this->host = $_ENV['HOST'];
        $this->db = $_ENV['DATABASE'];
        $this->getConnection();
    }

    public function getConnection() : PDO
    {
        try{
            $this->instance = new \PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->password);
            $this->instance->setAttribute(\PDO::ERRMODE_EXCEPTION, \PDO::ATTR_ERRMODE);
            return $this->instance;
        } catch (\PDOException $ex){
            throw new \PDOException($ex->getMessage());
        }
    }

    public function prepare(string $sql, array $options = []) : PDOStatement|false
    {
        return $this->stmt = $this->instance->prepare($sql, $options);
    }

    public function bind(string $param, mixed $value, int $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function query(string $sql, int $fetchMode = \PDO::FETCH_ASSOC) : PDOStatement | false
    {
        if(isset($fetchMode)){
            return $this->stmt = $this->instance->query($sql, $fetchMode); 
        }

        return $this->stmt = $this->instance->query($sql);
    }

    public function execute() : bool
    {
        return $this->stmt->execute();
    }

    public function rs() : array 
    {
        
        $this->execute();

        $arrayResult = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);

     
        return  !empty($arrayResult) ? $arrayResult : ["retorno" => "Sem Resultado"];
    }

    public function getTotalResults(array $results) : int
    {
        if(!empty($results)){
            return count($results);
        }

        return 0;
    }

    public function one() : array | null
    {
        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_ASSOC) ? $this->stmt->fetch(\PDO::FETCH_ASSOC) : null;
    }

    public function lastInsertId() : int 
    {
        return $this->instance->lastInsertId();
    }
}
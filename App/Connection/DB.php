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
        $this->user = $_ENV['USER'] ?? 'root';
        $this->password = $_ENV['PASS'] ?? 'root';
        $this->host = $_ENV['HOST'] ?? 'localhost';
        $this->db = $_ENV['DATABASE'] ?? 'default_db';
        $this->instance = $this->getConnection();
    }

    public function getConnection() : PDO
    {
        try {
            $this->instance = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8mb4", $this->user, $this->password);
            $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->instance;
        } catch (\PDOException $ex) {
            throw new \PDOException($ex->getMessage());
        }
    }

    public function prepare(string $sql, array $options = []) : PDOStatement|false
    {
        return $this->stmt = $this->instance->prepare($sql, $options);
    }

    public function bind(string $param, mixed $value, int $type = null) : bool
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        if (!$this->stmt instanceof PDOStatement) {
            throw new \Exception("Nenhuma consulta preparada para vincular parâmetros.");
        }
        return $this->stmt->bindValue($param, $value, $type);
    }

    public function query(string $sql, int $fetchMode = PDO::FETCH_ASSOC) : PDOStatement | false
    {
        return $this->stmt = $this->instance->query($sql, $fetchMode);
    }

    public function execute(array $params = []) : bool
    {
        if (!$this->stmt instanceof PDOStatement) {
            throw new \Exception("Nenhuma consulta preparada para executar.");
        }
        // Se $params for null, significa que os parâmetros já foram vinculados com bind()
        if (empty($params)) {
            return $this->stmt->execute();
        }
        // Caso contrário, usa os parâmetros fornecidos
        return $this->stmt->execute($params);
    }

    public function rs() : array 
    {
        $this->execute([]);
        $arrayResult = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        return !empty($arrayResult) ? $arrayResult : [];
    }

    public function queryWithParams(string $sql, array $params = [], int $fetchMode = PDO::FETCH_ASSOC) : array
    {
        $this->prepare($sql);
        // Passa os parâmetros diretamente para execute()
        $this->execute($params);
        $arrayResult = $this->stmt->fetchAll($fetchMode);
        return !empty($arrayResult) ? $arrayResult : [];
    }

    public function getTotalResults(array $results) : int
    {
        return !empty($results) ? count($results) : 0;
    }

    public function one() : array | null
    {
        $this->execute([]);
        $result = $this->stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    public function lastInsertId() : int 
    {
        return (int) $this->instance->lastInsertId();
    }
}
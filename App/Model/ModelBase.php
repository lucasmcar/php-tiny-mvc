<?php

namespace App\Model;

use App\Connection\DB;

class ModelBase
{
    protected $table;
    protected $fillable;
    protected $db;
    private $conditions = [];
    private $bindings = [];
    private $joins = [];
    private $alias;

    public function __construct()
    {
        $this->db = new DB(); // Uma única instância por modelo
    }

    protected function connect(): DB
    {
        return $this->db;
    }

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $this->connect()->prepare($sql);
        return $this->connect()->rs();
    }

    public function find($id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $this->connect()->prepare($sql);
        $this->connect()->bind(':id', $id);
        return $this->connect()->one();
    }

    public function lastInsertId(): int
    {
        return $this->connect()->lastInsertId();
    }

    public function findForSign($email): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $this->connect()->prepare($sql);
        $this->connect()->bind(':email', $email);
        return $this->connect()->rs();
    }

    public function create($data)
    {
        $fields = implode(',', $this->fillable);
        $values = ':' . implode(',:', $this->fillable);
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
        $this->connect()->prepare($sql);
        foreach ($this->fillable as $field) {
            if(!isset($data[$field])){
                $data[$field] = null;
            }
            if(isset($data[$field])){
                $this->connect()->bind(":$field", $data[$field]);
            }
        }
        $this->connect()->execute();
        return $this->connect()->lastInsertId();
    }

    public function update($id, $data): bool
    {
        $fields = [];
        $params = [];

        // Apenas incluir os campos que estão presentes no array $data
        foreach ($this->fillable as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false; // Nenhum campo para atualizar
        }

        $setClause = implode(',', $fields);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $this->connect()->prepare($sql);

        // Vincular os parâmetros
        foreach ($params as $param => $value) {
            $this->connect()->bind($param, $value);
        }
        $this->connect()->bind(':id', $id);

        return $this->connect()->execute();
    }

    public function delete($id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $this->connect()->prepare($sql);
        $this->connect()->bind(':id', $id);
        return $this->connect()->execute();
    }

    // Adiciona uma cláusula WHERE
    public function where($column, $operator, $value)
    {
        $paramName = ":param_" . count($this->bindings); // Gera um nome único para o binding
        $column = "LOWER($column)"; // Adiciona LOWER para tornar a comparação insensível a maiúsculas/minúsculas
        $value = strtolower($value); // Garante que o valor também seja convertido para minúsculas
        $this->conditions[] = ['AND', "$column $operator $paramName"];
        $this->bindings[$paramName] = $value;
        return $this;
    }

    // Adiciona uma cláusula OR WHERE
    public function orWhere($column, $operator, $value)
    {
        $paramName = ":param_" . count($this->bindings); // Gera um nome único para o binding
        $column = "LOWER($column)"; // Adiciona LOWER para tornar a comparação insensível a maiúsculas/minúsculas
        $value = strtolower($value); // Garante que o valor também seja convertido para minúsculas
        $this->conditions[] = ['OR', "$column $operator $paramName"];
        $this->bindings[$paramName] = $value;
        return $this;
    }

    public function andWhere($column, $operator, $value)
    {
        $paramName = ":param_" . count($this->bindings); // Gera um nome único para o binding
        $column = "LOWER($column)"; // Adiciona LOWER para tornar a comparação insensível a maiúsculas/minúsculas
        $value = strtolower($value); // Garante que o valor também seja convertido para minúsculas
        $this->conditions[] = ['AND', "$column $operator $paramName"];
        $this->bindings[$paramName] = $value;
        return $this;
    }

    // Executa a consulta com as condições
    public function get(array $fields = [])
    {
        if (empty($this->conditions) && empty($this->joins)) {
            return $this->all();
        }

        $sql = '';

        $alias = $this->alias ? " as {$this->alias}" : '';
        if(!empty($fields)){
            $sql = "SELECT ". implode(', ', $fields) ." FROM {$this->table} {$this->alias} "; 
        } else {
            $sql = "SELECT * FROM {$this->table} {$this->alias} "; 
        }
        

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        // Adiciona a cláusula WHERE, se houver
        if (!empty($this->conditions)) {
            $sql .= " WHERE ";
            $conditionsStr = '';
            foreach ($this->conditions as $index => [$logic, $condition]) {
                if ($index === 0) {
                    $conditionsStr .= $condition;
                } else {
                    $conditionsStr .= " $logic $condition";
                }
            }
            $sql .= $conditionsStr;
        }

        $stmt = $this->connect()->prepare($sql);
        foreach ($this->bindings as $param => $value) {
            $stmt->bindValue($param, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Reseta as condições e bindings para a próxima consulta
        $this->conditions = [];
        $this->bindings = [];
        $this->joins = [];

        return $results;
    }

    // Adiciona um INNER JOIN
    public function join($table, $condition)
    {
        $this->joins[] = "INNER JOIN $table ON $condition";
        return $this;
    }

    // Adiciona um LEFT JOIN
    public function leftJoin($table, $condition)
    {
        $this->joins[] = "LEFT JOIN $table ON $condition";
        return $this;
    }

    // Adiciona um RIGHT JOIN
    public function rightJoin($table, $condition)
    {
        $this->joins[] = "RIGHT JOIN $table ON $condition";
        return $this;
    }
}

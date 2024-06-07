<?php

namespace App\Connection;

use App\Connection\DB;

class Builder 
{
    private $db;
    private $table;
    private $selects  = [];
    private $wheres   = [];
    private $bindings = [];
    private $inserts  = [];
    private $orderBy  = '';
    private $limit    = '';
    private $offset   = '';
    
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function select(string ...$fields)
    {
        $this->selects = $fields;
        return $this;
    }

    public function where(string $field, string $operator, $value)
    {
        $this->wheres[] = "$field $operator :$field";
        $this->bindings[$field] = $value;
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC')
    {
        $this->orderBy = "ORDER BY $field $direction";
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function offset(int $offset)
    {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    public function like()
    {

    }

    private function buildSelectQuery()
    {
        $sql = 'SELECT ';
        $sql .= $this->selects ? implode(', ', $this->selects) : '*';
        $sql .= ' FROM ' . $this->table;
        
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($this->orderBy) {
            $sql .= ' ' . $this->orderBy;
        }

        if ($this->limit) {
            $sql .= ' ' . $this->limit;
        }

        if ($this->offset) {
            $sql .= ' ' . $this->offset;
        }

        return $sql;
    }

    public function get()
    {
        $sql = $this->buildSelectQuery();
        $stmt = $this->db->prepare($sql);
        
        foreach ($this->bindings as $param => $value) {
            $this->db->bind(":$param", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return $results ? $results[0] : null;
    }

    public function save(array $data)
    {
        $sql = $this->buildInsertQuery($data);
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $param => $value) {
            $this->db->bind(":$param", $value);
        }

        return $stmt->execute();
    }

    private function buildInsertQuery(array $data)
    {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        return $sql;
    }

    public function destroy()
    {

    }

    public function update()
    {

    }

}
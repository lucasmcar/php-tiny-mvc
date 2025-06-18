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
    private $joins = [];
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
        $param = $field . count($this->wheres); // exemplo: id0, id1
        $this->wheres[] = "$field $operator :$param";
        $this->bindings[$param] = $value;
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

    public function like(string $field, string $value)
    {
        $param = $field . count($this->wheres);
        $this->wheres[] = "$field LIKE :$param";
        $this->bindings[$param] = "%$value%";
        return $this;
    }


    private function buildSelectQuery()
    {
        $sql = 'SELECT ';
        $sql .= $this->selects ? implode(', ', $this->selects) : '*';
        $sql .= ' FROM ' . $this->table;

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

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
        $sql = "DELETE FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        } else {
            throw new \Exception("DELETE sem WHERE não permitido por segurança.");
        }

        $stmt = $this->db->prepare($sql);

        foreach ($this->bindings as $param => $value) {
            $this->db->bind(":$param", $value);
        }

        return $stmt->execute();
    }

    public function join(string $table, string $firstField, string $operator, string $secondField, string $type = 'INNER')
    {
        $this->joins[] = "$type JOIN $table ON $firstField $operator $secondField";
        return $this;
    }


    public function update(array $data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $param = "upd_$key";
            $set[] = "$key = :$param";
            $this->bindings[$param] = $value;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set);

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $stmt = $this->db->prepare($sql);

        foreach ($this->bindings as $param => $value) {
            $this->db->bind(":$param", $value);
        }

        return $stmt->execute();
    }

    public function leftJoin(string $table, string $first, string $operator, string $second)
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin(string $table, string $first, string $operator, string $second)
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }
}

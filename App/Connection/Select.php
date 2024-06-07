<?php

//namespeace temporarario
namespace App\Connection;

use ISelect;

class Select
{

    /*public function __construct(ISelect $select)
    {
        $this->select = $select;
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
    }*/
}
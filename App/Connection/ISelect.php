<?php

namespace App\Connection;

interface ISelect
{
    public function select(string ...$fields);
    public function where(string $field, string $operator, $value);
    public function orderBy(string $field, string $direction = 'ASC');
    public function limit(int $limit);
} 
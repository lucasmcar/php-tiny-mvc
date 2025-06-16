<?php

namespace App\Connection;

interface IConnection
{
    public function getConnection() : \PDO;
    public function prepare(string $sql, array $options = []) : \PDOStatement|false;
    public function bind(string $param, mixed $value, int $type);
    public function query(string $sql, int $fetchMode = \PDO::FETCH_ASSOC) : \PDOStatement | false;
    public function execute() : bool;
    public function rs() : array;
    public function getTotalResults(array $results) : int;

} 
<?php 

namespace App\Models;

use App\Connection\DB;
use App\Connection\Builder;

abstract class Model
{
    protected static string $table;

    protected static function query(): Builder
    {
        return (new Builder(new DB()))->table(static::$table);
    }

    public static function all()
    {
        return static::query()->get();
    }

    public static function find($id)
    {
        return static::query()->where('id', '=', $id)->first();
    }

    public static function where(string $field, string $op, $value)
    {
        return static::query()->where($field, $op, $value);
    }

    public static function create(array $data)
    {
        return static::query()->save($data);
    }

    public static function updateWhere(array $data, array $conditions)
    {
        $builder = static::query();
        foreach ($conditions as $field => $val) {
            $builder->where($field, '=', $val);
        }
        return $builder->update($data);
    }

    public static function deleteWhere(array $conditions)
    {
        $builder = static::query();
        foreach ($conditions as $field => $val) {
            $builder->where($field, '=', $val);
        }
        return $builder->destroy();
    }

    public static function join(string $table, string $first, string $op, string $second)
    {
        return static::query()->join($table, $first, $op, $second);
    }   

    public static function leftJoin(string $table, string $first, string $op, string $second)
    {
        return static::query()->leftJoin($table, $first, $op, $second);
    }
}

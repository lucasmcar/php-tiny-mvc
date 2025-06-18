<?php 

namespace App\Models;
use App\Models\Model;


class Usuario extends Model
{

    protected $table = 'usuarios';
    protected $alias = 'u';
    protected $fillable = [
        'nome',
        'usuario',
        'email',
        'senha',
        'tipo'
    ];

    

    
}
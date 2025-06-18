<?php

namespace App\Model;

class Depoimento extends ModelBase
{
    protected $table = 'depoimentos';
    protected $fillable = [
        'projeto_id', 
        'usuario_id', 
        'nome', 
        'profissao', 
        'depoimento', 
        'titulo', 
        'foto', 
        'data_criacao', 
        'status'
    ];
}

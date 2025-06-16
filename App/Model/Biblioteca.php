<?php

namespace App\Model;

use App\Model\ModelBase;

class Biblioteca extends ModelBase
{

    protected $fillable = [
        'nome',
    ];
    protected $alias = '';
    protected $table = 'bibliotecas';

    public function checarNomeDisponivel(string $nome)
    {
        $sql = "SELECT COUNT(nome) FROM bibliotecas WHERE nome = :nome";
        $this->db->prepare($sql);
        $this->db->bind(':nome', $nome);
        $result = $this->db->execute([]);
        return $result;
    }
}
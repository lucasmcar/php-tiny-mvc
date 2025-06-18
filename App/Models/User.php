<?php 

namespace App\Model;
use App\Model\ModelBase;


class User extends ModelBase
{

    protected $table = 'usuarios';
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'foto',
        'usuario',
        'funcao'
    ];

    // Novo método para atualizar apenas o ultimo_login
    public function updateLastLogin($id, $lastLogin)
    {
        $sql = "UPDATE usuarios SET ultimo_login = :ultimo_login WHERE id = :id";
        $this->db->prepare($sql);
        $this->db->bind(':ultimo_login', $lastLogin);
        $this->db->bind(':id', $id, null);
        $result = $this->db->execute([]);
        return $result;
    }

    
}
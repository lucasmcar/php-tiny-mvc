<?php 

namespace App\Model;
use App\Model\ModelBase;

class Administrador extends ModelBase
{
    protected $table = 'administradores';
    protected $alias= 'a';
    protected $fillable = [
        'usuario_id',
        'nome',
        'email',
        'funcao'
    ];

    public function deleteByUserId(int $userId)
    {
        $sql = "DELETE FROM administradores WHERE usuario_id = :id";
        $this->db->prepare($sql);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
    
}
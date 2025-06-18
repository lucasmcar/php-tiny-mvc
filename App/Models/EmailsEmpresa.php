<?php 

namespace App\Model;

use App\Model\ModelBase;

class EmailsEmpresa extends ModelBase
{
    protected $table = 'empresa_emails';
    protected $alias = 'emp';
    protected $fillable = [
        'usuario_id',
        'email'
    ];

    public function deleteByUserId(int $id)
    
    {
        $sql = "DELETE FROM empresa_emails WHERE usuario_id = :id";
        $this->db->prepare($sql);
        $this->db->bind(':id', $id);
        return $this->db->execute([]);
    }
}
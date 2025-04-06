<?php

namespace App\Model;

class Edital extends ModelBase
{
    protected $table = 'editais';
    protected $fillable = ['name', 'usuario_id', 'filename', 'url', 'status'];

    // Novo método para atualizar apenas o ultimo_login
    public function updateEditalStatus($id, $status)
    {
        $sql = "UPDATE editais SET status = :status WHERE id = :id";
        $this->db->prepare($sql);
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        $result = $this->db->execute([]);
        return $result;
    }
}
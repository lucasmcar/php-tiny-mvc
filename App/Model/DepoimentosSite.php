<?php 

namespace App\Model;

use App\Model\ModelBase;

class DepoimentosSite extends ModelBase
{
    protected $table = 'depoimentos_site';
    protected $alias = 'ts';
    protected $fillable = [
        'config_site_id',
        'depoimento_id',
    ];

    public function deleteByConfigId(int $configId)
    {
        $sql = "DELETE FROM depoimentos_site WHERE config_site_id = :id";
        $this->db->prepare($sql);
        $this->db->bind(':id', $configId);
        return $this->db->execute();
    }
}
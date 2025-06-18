<?php 

namespace App\Model;

use App\Model\ModelBase;

class DestaquesSite extends ModelBase 
{
    protected $table = 'destaques_sites';
    protected $alias = 'ds';
    protected $fillable = [
        'config_site_id',
        'url_imagem',
        'texto_overlay',
        'display_order',
    ];

    public function deleteByConfigId(int $configId)
    {
        $sql = "DELETE FROM destaques_site WHERE config_site_id = :id";
        $this->db->prepare($sql);
        $this->db->bind(':id', $configId);
        return $this->db->execute();
    }
}
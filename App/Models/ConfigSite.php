<?php 

namespace App\Model;

use App\Model\ModelBase;

class ConfigSite extends ModelBase
{
    protected $table = 'config_site';
    protected $alias = 'cs';
    protected $fillable = [
        'titulo',
        'cor_overlay',
        'mostrar_depoimentos'
    ];

    public function findLatest()
    {
        $query = "SELECT * FROM config_site ORDER BY data_criacao DESC LIMIT 1";
        return $this->db->one();
    }
}
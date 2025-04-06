<?php

namespace App\Repository;

use App\Dao\DepoimentoDao;
use App\Model\Depoimento;

class DepoimentoRepository
{

    private $depoimentoDao;

    public function __construct()
    {
        $this->depoimentoDao = new DepoimentoDao();
    }

    public function criaDepoimento(Depoimento $model)
    {
        $this->depoimentoDao->criaDepoimento($model);
    }

    public function verDepoimentos()
    {
        return $this->depoimentoDao->verDepoimnetos();
    }

    public function deletarDepoimento($id)
    {
        $this->depoimentoDao->deletarDepoimento($id);
    }

    public function editarDepoimento(Depoimento $model)
    {
        $this->depoimentoDao->editarDepoimento($model);
    }

    public function totalDepoimentos()
    {
        return $this->depoimentoDao->totalDepoimentos();
    }

    
}
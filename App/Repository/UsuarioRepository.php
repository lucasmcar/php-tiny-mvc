<?php

namespace App\Repository;

use App\Model\Usuario;

class UsuarioRepository
{

    private $model;

    public function __construct()
    {
        $this->model = new Usuario();
    }

    public function create(array $data)
    {
        $this->model->create($data);
    }

    public function findForSign(string $email)
    {
        return $this->model->findForSign($email);
    }

    public function updateLastLogin($id, $lastLogin)
    {
        $this->model->atualizaUltimoAcesso($id, $lastLogin);
    }
}
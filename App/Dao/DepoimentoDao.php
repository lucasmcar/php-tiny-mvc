<?php 

namespace App\Dao;

use App\Connection\DB;
use App\Model\Depoimento;

class DepoimentoDao
{
    private $connection;

    public function __construct()
    {
        $this->connection = new DB();
    }

    public function criaDepoimento(Depoimento $model)
    {
        $sql = "INSERT INTO depoimentos (nome, depoimento, titulo, profissao, foto, data_criacao) VALUES (:nome, :depoimento, :titulo, :profissao, :foto, :data_criacao)";
        $this->connection->prepare($sql);
        $this->connection->bind(':nome', $model->getNome());
        $this->connection->bind(':depoimento', $model->getDepoimento());
        $this->connection->bind(':profissao', $model->getProfissao());
        $this->connection->bind(':titulo', $model->getTitulo());
        $this->connection->bind(':foto', $model->getFoto());
        $this->connection->bind(':data_criacao', date('Y-m-d H:i:s'));
        $this->connection->execute();

    }

    public function verDepoimnetos()
    {
        $sql = "SELECT * FROM depoimentos";
        $this->connection->prepare($sql);
        $this->connection->execute();
        return $this->connection->rs();
    }

    public function deletarDepoimento($id)
    {
        $sql = "DELETE FROM depoimentos WHERE id = :id";
        $this->connection->prepare($sql);
        $this->connection->bind(':id', $id);
        $this->connection->execute();
    }

    public function editarDepoimento(Depoimento $model)
    {
        $sql = "UPDATE depoimentos SET nome = :nome, depoimento = :depoimento, profissao = :profissao, foto = :foto WHERE id = :id";
        $this->connection->prepare($sql);
        $this->connection->bind(':nome', $model->getNome());
        $this->connection->bind(':depoimento', $model->getDepoimento());
        $this->connection->bind(':profissao', $model->getProfissao());
        $this->connection->bind(':foto', $model->getFoto());
        $this->connection->bind(':id', $model->getIdDepoimento());
        $this->connection->execute();
    }

    public function totalDepoimentos()
    {
        $sql = "SELECT COUNT(*) as total FROM depoimentos";
        $this->connection->prepare($sql);
        $this->connection->execute();
        $total = $this->connection->rs();
        return $total[0]['total'];
    }
}
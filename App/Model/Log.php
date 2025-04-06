<?php

namespace App\Model;

class Log extends ModelBase
{
    protected $table = 'logs_atividades';
    protected $fillable = ['usuario_id', 'acao', 'tabela_afetada', 'id_registro', 'detalhes', 'data_acao'];

    public function create($data)
    {
        // Converte detalhes para JSON, se for array
        if (isset($data['detalhes']) && is_array($data['detalhes'])) {
            $data['detalhes'] = json_encode($data['detalhes']);
        }

        // Usa a implementação do ModelBase diretamente
        $fields = implode(',', $this->fillable);
        $values = ':' . implode(',:', $this->fillable);
        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
        $stmt = $this->connect()->prepare($sql);
        foreach ($this->fillable as $field) {
            $stmt->bindParam(":$field", $data[$field]); // Usa bindParam como no ModelBase
        }
        return $stmt->execute(); // Retorna bool, compatível com ModelBase
    }
}
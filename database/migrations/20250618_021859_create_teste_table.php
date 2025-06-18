<?php

return new class {
    public function up(\PDO $pdo)
    {
        // Crie sua tabela aqui:
        $pdo->exec("CREATE TABLE IF NOT EXISTS teste (
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function down(\PDO $pdo)
    {
        // Exclua sua tabela aqui
        $pdo->exec("DROP TABLE IF EXISTS teste");
    }
};
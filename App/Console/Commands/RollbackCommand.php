<?php

namespace App\Console\Commands;

use PDO;

class RollbackCommand
{
    protected string $migrationPath = __DIR__ . '/../../../database/migrations';
    private $user;
    private $db;
    private $host;
    private $password;

    public function __construct()
    {
        $this->user = $_ENV['USER'] ?? 'root';
        $this->password = $_ENV['PASS'] ?? '';
        $this->host = $_ENV['HOST'] ?? 'localhost';
        $this->db = $_ENV['DATABASE'] ?? 'default_db';
    }

    public function handle(array $args): void
    {
        $pdo = $this->getConnection();

        $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id DESC LIMIT 1");
        $lastMigration = $stmt->fetchColumn();

        if (!$lastMigration) {
            echo "Nenhuma migration para reverter.\n";
            return;
        }

        $file = $this->migrationPath . '/' . $lastMigration;

        if (!file_exists($file)) {
            echo "Arquivo da migration não encontrado: $lastMigration\n";
            return;
        }

        echo "Revertendo: $lastMigration\n";
        $migration = require $file;
        $migration->down($pdo);

        $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration = :migration");
        $stmt->execute(['migration' => $lastMigration]);

        echo "Rollback concluído.\n";
    }

    private function getConnection(): PDO
    {
        return new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8mb4", $this->user, $this->password);
    }
}

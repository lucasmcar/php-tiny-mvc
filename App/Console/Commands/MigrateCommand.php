<?php

namespace App\Console\Commands;

use PDO;

class MigrateCommand
{
    protected string $migrationPath = __DIR__ . '/../../../database/migrations';
    private $user;
    private $db;
    private $host;
    private $password;

    public function __construct()
    {
        $this->user = $_ENV['USER'] ?? 'root';
        $this->password = $_ENV['PASS'] ?? 'root';
        $this->host = $_ENV['HOST'] ?? 'localhost';
        $this->db = $_ENV['DATABASE'] ?? 'default_db';
    }

    public function handle(array $args): void
    {
        $pdo = $this->getConnection();
        $this->createMigrationsTable($pdo);

        $applied = $this->getAppliedMigrations($pdo);
        $files = glob($this->migrationPath . '/*.php');
        $executed = 0;

        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $applied)) {
                continue;
            }

            echo "Executando: $name\n";
            $migration = require $file;
            $migration->up($pdo);
            $this->storeMigration($pdo, $name);
            $executed++;
        }

        if ($executed === 0) {
            echo "Nenhuma nova migration encontrada.\n";
        } else {
            echo "Executadas $executed migrations.\n";
        }
    }

    private function getConnection(): PDO
    {
        return new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8mb4", $this->user, $this->password);
    }

    private function createMigrationsTable(PDO $pdo): void
    {
        $pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    private function getAppliedMigrations(PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT migration FROM migrations");
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }

    private function storeMigration(PDO $pdo, string $migration): void
    {
        $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
        $stmt->execute(['migration' => $migration]);
    }
}

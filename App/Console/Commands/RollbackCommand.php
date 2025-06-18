<?php

namespace App\Console\Commands;

use App\Connection\DB;
use PDO;

class RollbackCommand extends DB
{
    protected string $migrationPath = __DIR__ . '/../../../database/migrations';

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

}

<?php

namespace App\Console\Commands;

class MakeMigrationCommand
{
    public function handle(array $args): void
    {
        if (empty($args[0])) {
            echo "Informe o nome da migration.\n";
            return;
        }

        $name = $args[0];
        $timestamp = date('Ymd_His');
        $filename = $timestamp . '_' . $name . '.php';

        $migrationsPath = dirname(__DIR__, 3) . '/database/migrations';

        if (!is_dir($migrationsPath)) {
            mkdir($migrationsPath, 0777, true);
        }

        $filePath = $migrationsPath . '/' . $filename;

        // Tenta extrair o nome da tabela da migration
        $table = 'example_table'; // valor padrão
        if (preg_match('/create_(.*?)_table/', $name, $matches)) {
            $table = $matches[1];
        }

        $template = <<<PHP
<?php

return new class {
    public function up(\PDO \$pdo)
    {
        // Crie sua tabela aqui:
        \$pdo->exec("CREATE TABLE IF NOT EXISTS $table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function down(\PDO \$pdo)
    {
        // Exclua sua tabela aqui
        \$pdo->exec("DROP TABLE IF EXISTS $table");
    }
};
PHP;

        file_put_contents($filePath, $template);
        echo "Migration criada: $filename (tabela: $table)\n";
    }
}

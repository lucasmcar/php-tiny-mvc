<?php

namespace App\Console;

class Kernel
{
    protected array $commands = [];

    public function __construct()
    {
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        $this->commands = [
            'make:migration' => \App\Console\Commands\MakeMigrationCommand::class,
            'migrate'        => \App\Console\Commands\MigrateCommand::class,
            'rollback'       => \App\Console\Commands\RollbackCommand::class,
        ];
    }

    public function handle(array $argv): void
    {
        $command = $argv[1] ?? null;
        $args = array_slice($argv, 2);

        if (!$command || !isset($this->commands[$command])) {
            echo "Comando inválido ou não informado.\n";
            exit(1);
        }

        $class = new ($this->commands[$command])();
        $class->handle($args);
    }
}

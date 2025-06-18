<?php

namespace App\Utils;

class DotEnvUtil
{
    public static function loadEnv(string $path): void
    {
        // Evita uso de arquivos .env-example
        if (strripos($path, '-example') !== false) {
            throw new \Exception("Arquivo inválido: não utilize um .env-example diretamente.");
        }

        // Verifica se o caminho foi passado e se o arquivo existe
        if (empty($path) || !file_exists($path)) {
            echo "Aviso: arquivo .env não encontrado em '$path'. Variáveis de ambiente não carregadas.\n";
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Ignora comentários e linhas vazias
            if ($line === '' || $line[0] === '#' || $line[0] === ';') {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            $_ENV[$name] = $value;
            putenv("$name=$value"); // opcional: torna acessível via getenv()
        }
    }
}

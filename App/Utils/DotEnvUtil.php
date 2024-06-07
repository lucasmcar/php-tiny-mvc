<?php

namespace App\Utils;

class DotEnvUtil
{
    public static function loadEnv(string $path)
    {
        if(strripos($path, "-example")){
            throw New \Exception("Arquivo inválido");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0 || strpos(trim($line), ';') === 0) {
                continue;
            }
    
            // Divide a linha em nome e valor, ignorando a linha se não for válida
            if (strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode("=", $line, 2);

            // Remove espaços em branco extras ao redor do nome e do valor
            $name = trim($name);
            $value = trim($value);

            $_ENV[$name] = $value;
        }
    }
}
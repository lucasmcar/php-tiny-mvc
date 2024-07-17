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
    
            // Divides the row in name and value, ignoring the row if is not valid
            if (strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode("=", $line, 2);

            // Remove the extras white spaces around the name and value
            $name = trim($name);
            $value = trim($value);

            $_ENV[$name] = $value;
        }
    }
}
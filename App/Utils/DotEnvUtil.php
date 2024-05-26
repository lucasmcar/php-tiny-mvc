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

            list($name, $value) = explode("=", $line, 2);

            $_ENV[$name] = $value;
        }
    }
}
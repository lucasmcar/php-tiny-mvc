<?php 

namespace App\Helper;

class InputFilterHelper
{
    public static function filterInputs(int $tipo, array $nomes)
    {
        if(!in_array($tipo, [INPUT_POST, INPUT_GET]) && !is_array($nomes)){
            return null;
        }

        $inputs = [];

        foreach($nomes as $nome){
            $value = filter_input($tipo, $nome);

            $inputs[$nome] = $value;
        }

        return $inputs;
    }
}

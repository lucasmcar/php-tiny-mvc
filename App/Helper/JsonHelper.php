<?php

namespace App\Helper;

class JsonHelper
{

    public static function toJson(array $data)
    {
        if(!empty($data)){
            return json_encode($data);
        }

        throw new \Exception("Não há dados disponíveis");
        
    }

    public static function toArray($data)
    {
        if(!empty($data)){
            return json_decode($data, true);
        }
        throw new \Exception("Não há dados disponíveis ou válidos");
    }

}
<?php 

namespace App\Utils;

abstract class GenerateUtil
{

    public static function generateRandomNrId() : int
    {
        return rand(000000, 999999);
    }

} 
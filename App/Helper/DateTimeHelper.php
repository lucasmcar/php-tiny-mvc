<?php 
namespace App\Helper;

use DateTime;

class DateTimeHelper
{
    /**
     * Converts a date string normal format to Database format
     * @param string $date
     * @return string | null
     */
    public static function toDatabaseFormat(string $date) : string
    {
        return \DateTime::createFromFormat('d/m/Y H:i:s', $date)->format("Y-m-d H:i:s");
    }

    /**
     * Converts a date string Database format to normal format
     * @param string $date
     * @return string | null
     */
    public static function toNormalFormat(string $date, $hasTime = true) : string 
    {
        if(!$hasTime){
            return \DateTime::createFromFormat('Y-m-d', $date)->format("d/m/Y");
        }
        return \DateTime::createFromFormat('Y-m-d H:i:s', $date)->format("d/m/Y H:i:s");
    }

    public static function calculaIdade(string $ano)
    {
        // Obtém o ano atual
        $ano_atual = date('Y');

        // Converte a string do ano de nascimento para um objeto DateTime
        $ano_nascimento = new DateTime($ano);

        // Obtém o ano de nascimento
        $ano_nascimento_int = $ano_nascimento->format('Y');

        // Calcula a idade
        $idade = $ano_atual - $ano_nascimento_int;

        return $idade;
    }
}
<?php

namespace App\Helper;

class JsonHelper
{
    /**
     * Converte dados para JSON com tratamento de erros.
     *
     * @param mixed $data
     * @param int $options
     * @param int $depth
     * @return string
     * @throws \Exception
     */
    public static function toJson($data, int $options = 0, int $depth = 512): string
    {
        if (empty($data)) {
            throw new \Exception("Não há dados disponíveis para converter.");
        }

        $json = json_encode($data, $options, $depth);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erro ao converter para JSON: ' . json_last_error_msg());
        }

        return $json;
    }

    /**
     * Converte JSON em array associativo.
     *
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $flags
     * @return array
     * @throws \Exception
     */
    public static function toArray(string $json, bool $assoc = true, int $depth = 512, int $flags = 0): array
    {
        if (empty($json)) {
            throw new \Exception("String JSON vazia.");
        }

        $data = json_decode($json, $assoc, $depth, $flags);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
        }

        return $data;
    }
}
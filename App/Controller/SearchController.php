<?php

namespace App\Controller;

class SearchController
{
    public function search($params = [])
    {
        

        // Obtém o termo bruto
        $rawQ = $params['q'] ?? $_GET['q'] ?? '';
        
        // Sanitiza o termo (remove tags HTML e caracteres especiais perigosos)
        $q = filter_var($rawQ, FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
        
        // Valida o termo (permite letras, números, espaços e hífens)
        if (!preg_match('/^[\p{L}\p{N}\s-]*$/u', $q) || strlen($q) > 100) {
            $q = ''; // Rejeita termos inválidos ou muito longos
        }

        // Simulação de busca (substitua por sua lógica real, ex.: consulta ao banco)
        $results = [];
        if (stripos('Festa Junina de Porto Alegre 2025', $q) !== false) {
            $results[] = [
                'title' => 'Festa Junina de Porto Alegre 2025',
                'url' => '/projetos/festa-junina-2025'
            ];
        }
        if (stripos('Festival de Música 2024', $q) !== false) {
            $results[] = [
                'title' => 'Festival de Música 2024',
                'url' => '/projetos/festival-musica-2024'
            ];
        }
        if(stripos('Show Local 2023', $q) !== false) {
            $results[] = [
                'title' => 'Show Local 2023',
                'url' => '/projetos/show-local-2023'
            ];

        }

        // Retorna os resultados em JSON
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
}
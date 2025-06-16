<?php

namespace App\Controller;

use Core\View\View;

class EventoController
{
    public function index()
    {
        $data = [
            'title' => 'Gerenciar Eventos',
        ];

        $styles = [
            'assets/css/admin/evento.min.css',
        ];
        $scripts = [
            'assets/js/evento.min.js',
        ];

        return new View(view: 'admin/evento', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }

    public function participantes()
    {

        $participants = $this->fetchParticipants();
        $events = $this->fetchEvents();

        $data = [
            'title' => 'Gerenciar Participantes',
            'participants' => $participants,
            'events' => $events
        ];

        $styles = [
            'assets/css/admin/participante.min.css',
        ];

        $scripts = [
            'assets/js/participante.min.js',
        ];

        return new View(view: 'admin/participante', vars: $data, styles: $styles, scripts: $scripts, layout: 'admin-layout');
    }


    private function fetchEvents()
    {
        // Dados fictícios de eventos
        return [
            ['id' => 1, 'name' => 'Festa Junina 2025', 'date' => '2025-06-15', 'location' => 'Porto Alegre, RS', 'description' => 'Celebração tradicional.', 'type' => 'Cultural', 'target_audience' => 'Famílias'],
            ['id' => 2, 'name' => 'Circuito Urbano 2025', 'date' => '2025-05-02', 'location' => 'Restinga, Porto Alegre', 'description' => 'Evento de hip-hop.', 'type' => 'Musical', 'target_audience' => 'Jovens']
        ] ?? [];
    }

    private function fetchParticipants()
    {
        // Dados fictícios de participantes
        $participants = [
            ['id' => 1, 'name' => 'Fornecedora de Bebidas XYZ', 'type' => 'Fornecedor', 'contact' => 'contato@xyz.com', 'event_id' => 1, 'description' => 'Fornece bebidas.'],
            ['id' => 2, 'name' => 'DJ Urbano', 'type' => 'Artista', 'contact' => 'djurbano@gmail.com', 'event_id' => 2, 'description' => 'Apresentação principal.']
        ] ?? [];

        $events = $this->fetchEvents();
        
        // Associar o nome do evento a cada participante
        foreach ($participants as &$participant) {
            $event = array_filter($events, fn($e) => $e['id'] == $participant['event_id']);
            $event = reset($event);
            $participant['event_name'] = $event ? $event['name'] : 'Evento não encontrado';
        }
        unset($participant); // Desfazer a referência

        return $participants;
    }
}

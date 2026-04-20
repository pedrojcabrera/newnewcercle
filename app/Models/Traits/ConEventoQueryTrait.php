<?php

namespace App\Models\Traits;

trait ConEventoQueryTrait
{
    protected function fetchConEvento(array $selectFields): array
    {
        return $this->select(implode(', ', $selectFields))
            ->join('neventos', 'neventos.id = id_evento')
            ->orderBy('fecha', 'DESC')
            ->findAll();
    }
}

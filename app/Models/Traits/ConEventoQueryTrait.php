<?php

namespace App\Models\Traits;

trait ConEventoQueryTrait
{
    protected function fetchConEvento(array $selectFields): array
    {
        $result = $this->db
            ->table($this->table)
            ->select(implode(', ', $selectFields))
            ->join('neventos', 'neventos.id = ' . $this->table . '.id_evento')
            ->orderBy($this->table . '.fecha', 'DESC')
            ->get();

        return $result ? $result->getResultObject() : [];
    }
}

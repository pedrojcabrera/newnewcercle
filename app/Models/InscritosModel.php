<?php

namespace App\Models;

use App\Models\Traits\ConEventoQueryTrait;
use CodeIgniter\Model;

class InscritosModel extends Model
{
    use ConEventoQueryTrait;

    protected $table      = 'inscritos';  // Nombre de la tabla en MySQL
    protected $primaryKey = 'id';         // Clave primaria de la tabla
    protected $returnType = 'object';

    protected $allowedFields = [
            'id_invitado',
            'id_evento',
            'id_contacto',
            'nombre',
            'apellidos',
            'email',
            'telefono',
            'via',
            'fecha'
        ];

    // Método para obtener el título del evento
    public function inscritosConEvento() {
        return $this->fetchConEvento([
            'inscritos.id AS id',
            'inscritos.nombre AS nombre',
            'inscritos.apellidos AS apellidos',
            'inscritos.email AS email',
            'inscritos.telefono AS telefono',
            'inscritos.fecha AS fecha',
            'inscritos.id_evento AS id_evento',
            'inscritos.id_contacto AS id_contacto',
            'inscritos.id_invitado AS id_invitado',
            'neventos.titulo AS titulo',
        ]);
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class EnEsperaModel extends Model
{
    protected $table      = 'enespera';  // Nombre de la tabla en MySQL
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
        'fecha'
    ]; // Campos permitidos para insertar

    // Método para obtener el título del evento
    public function emailsConEvento() {
        return $this->select(
            "
            enespera.id AS id,
            enespera.nombre AS nombre,
            enespera.apellidos AS apellidos,
            enespera.email AS email,
            enespera.telefono AS telefono,
            enespera.fecha AS fecha,
            enespera.id_evento AS id_evento,
            enespera.id_invitado AS id_invitado,
            enespera.fecha AS fecha,
            neventos.titulo AS titulo"
            )
            ->join('neventos', 'neventos.id = id_evento')
            ->OrderBy('fecha','DESC')
            ->findAll();
    }
}
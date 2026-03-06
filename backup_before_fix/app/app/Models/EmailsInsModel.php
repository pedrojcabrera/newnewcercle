<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailsInsModel extends Model
{
    protected $table      = 'emails_inscripciones';  // Nombre de la tabla en MySQL
    protected $primaryKey = 'id';         // Clave primaria de la tabla
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_evento',
        'id_contacto',
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'inscrito',
        'fecha'
    ]; // Campos permitidos para insertar

    // Método para obtener el título del evento
    public function emailsConEvento() {
        return $this->select(
            "
            emails_inscripciones.id AS id,
            emails_inscripciones.nombre AS nombre,
            emails_inscripciones.apellidos AS apellidos,
            emails_inscripciones.email AS email,
            emails_inscripciones.telefono AS telefono,
            emails_inscripciones.fecha AS fecha,
            emails_inscripciones.id_evento AS id_evento,
            emails_inscripciones.inscrito AS inscrito,
            emails_inscripciones.fecha AS fecha,
            neventos.titulo AS titulo"
            )
            ->join('neventos', 'neventos.id = id_evento')
            ->OrderBy('fecha','DESC')
            ->findAll();
    }
}
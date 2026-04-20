<?php

namespace App\Models;

use App\Models\Traits\ConEventoQueryTrait;
use CodeIgniter\Model;

class EmailsInsModel extends Model
{
    use ConEventoQueryTrait;

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
        return $this->fetchConEvento([
            'emails_inscripciones.id AS id',
            'emails_inscripciones.nombre AS nombre',
            'emails_inscripciones.apellidos AS apellidos',
            'emails_inscripciones.email AS email',
            'emails_inscripciones.telefono AS telefono',
            'emails_inscripciones.fecha AS fecha',
            'emails_inscripciones.id_evento AS id_evento',
            'emails_inscripciones.inscrito AS inscrito',
            'neventos.titulo AS titulo',
        ]);
    }
}

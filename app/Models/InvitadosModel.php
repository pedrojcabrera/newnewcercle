<?php

namespace App\Models;

use App\Models\Traits\ConEventoQueryTrait;
use CodeIgniter\Model;

class InvitadosModel extends Model
{
    use ConEventoQueryTrait;

    protected $table      = 'invitados';  // Nombre de la tabla en MySQL
    protected $primaryKey = 'id';         // Clave primaria de la tabla
    protected $returnType = 'object';

    protected $allowedFields = [
        'id_evento',
        'id_contacto',
        'nombre',
        'apellidos',
        'email',
        'dni',
        'telefono',
        'direccion',
        'codpostal',
        'poblacion',
        'provincia',
        'fecha',
        'enespera',
        'inscrito'
    ]; // Campos permitidos para insertar

    // Método para obtener el título del evento
    public function invitadosConEvento() {
        return $this->fetchConEvento([
            'invitados.id AS id',
            'invitados.nombre AS nombre',
            'invitados.apellidos AS apellidos',
            'invitados.email AS email',
            'invitados.telefono AS telefono',
            'invitados.fecha AS fecha',
            'invitados.id_evento AS id_evento',
            'neventos.titulo AS titulo',
        ]);
    }
}

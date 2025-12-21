<?php

namespace App\Models;

use CodeIgniter\Model;

class EventosModel extends Model
{
    protected $table            = 'neventos';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $protectFields    = true;
    protected $allowedFields    = [
        'titulo', 'eventotipo', 'desde',
        'hasta', 'texto', 'visible', 'socio',
        'alumno', 'pdalumno', 'pintor', 'dtaller',
        'amigo', 'inscripcion', 'desde_inscripcion',
        'hasta_inscripcion', 'inscripcion_invitacion',
        'aforo_completo', 'texto_carta', 'pdf_adjunto',
        'evento_cerrado',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    // protected $useTimestamps = true;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    private $eventoModel;
    private $id;

}
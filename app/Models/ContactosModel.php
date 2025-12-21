<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactosModel extends Model
{
    protected $table            = 'contactos';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nombre', 'apellidos', 'email', 'dni', 'telefono', 'direccion', 'codpostal', 'poblacion', 'provincia',
        'mailing', 'invitaciones', 'socio', 'alumno', 'pdalumno', 'pintor', 'dtaller', 'amigo'    ];

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

    private $contactoModel;
    private $id;

}
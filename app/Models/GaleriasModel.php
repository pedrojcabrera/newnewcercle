<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriasModel extends Model
{
    protected $table            = 'galerias';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_user', 'autores', 'titulo',
        'tecnica', 'soporte', 'medidas',
        'premios', 'comentarios', 'ano', 'precio'
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

    private $galeriaModel;
    private $id;

    /*
    public function getObrasConUsuario($id) {
        return $this->select('galerias.*, usuarios.nombre AS nombre')
        ->join('usuarios', 'id = galerias.id_user')
        ->find($id);
    }
    public function getGaleriasConUsuario() {
        return $this->select('galerias.*, usuarios.nombre AS nombre')
        ->join('usuarios', 'usuarios.id = galerias.id_user')
        ->findAll();
    }
    protected function getUsuarios() {
        return $this->select('usuarios.*, COUNT(galerias.id) AS obras')
        ->join('galerias', 'id_user = usuarios.id', 'left')
        ->groupBy('usuarios.id')
        ->findAll();
    }
    */
}
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

    /**
     * Obtiene contactos con calidades concatenadas en SQL para mejor rendimiento
     *
     * @param int $limit Cantidad de registros
     * @param int $offset Desplazamiento
     * @param string|null $searchValue Valor de búsqueda
     * @param string $orderBy Columna de ordenación
     * @param string $orderDir Dirección de ordenación (asc|desc)
     * @return array
     */
    public function getContactosConCalidades(int $limit = 10, int $offset = 0, ?string $searchValue = null, string $orderBy = 'nombre', string $orderDir = 'asc')
    {
        $calidadesSQL = "CONCAT_WS('|',
            IF(mailing = 1, 'Correos', NULL),
            IF(invitaciones = 1, 'Invitaciones', NULL),
            IF(socio = 1, 'Socio', NULL),
            IF(alumno = 1, 'Alumno', NULL),
            IF(pdalumno = 1, 'Padre/Madre', NULL),
            IF(pintor = 1, 'Pintor', NULL),
            IF(dtaller = 1, 'Talleres', NULL),
            IF(amigo = 1, 'Amigo', NULL)
        ) as calidades_concat";

        $this->select("id, nombre, apellidos, email, dni, telefono, {$calidadesSQL}");

        // Aplicar búsqueda si existe
        if (!empty($searchValue)) {
            $this->groupStart()
                ->like('nombre', $searchValue)
                ->orLike('apellidos', $searchValue)
                ->orLike('email', $searchValue)
                ->orLike('dni', $searchValue)
                ->orLike('telefono', $searchValue)
                ->groupEnd();
        }

        // Validar columna de ordenación
        $validColumns = ['id', 'nombre', 'email'];
        if (in_array($orderBy, $validColumns)) {
            $this->orderBy($orderBy, $orderDir);
        } else {
            $this->orderBy('nombre', $orderDir);
        }

        return $this->findAll($limit, $offset);
    }

    /**
     * Cuenta contactos con filtro de búsqueda
     *
     * @param string|null $searchValue Valor de búsqueda
     * @return int
     */
    public function countContactosFiltrados(?string $searchValue = null): int
    {
        if (!empty($searchValue)) {
            $this->groupStart()
                ->like('nombre', $searchValue)
                ->orLike('apellidos', $searchValue)
                ->orLike('email', $searchValue)
                ->orLike('dni', $searchValue)
                ->orLike('telefono', $searchValue)
                ->groupEnd();
        }

        return $this->countAllResults();
    }

}

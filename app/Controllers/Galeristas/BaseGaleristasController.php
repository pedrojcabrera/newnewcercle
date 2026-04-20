<?php

namespace App\Controllers\Galeristas;

use App\Controllers\BaseController;
use App\Models\GaleriasModel;
use App\Models\UsuariosModel;

abstract class BaseGaleristasController extends BaseController
{
    protected $UsuarioModel;
    protected $GaleriaModel;

    public function __construct()
    {
        $this->UsuarioModel = new UsuariosModel();
        $this->GaleriaModel = new GaleriasModel();
        $this->session = session();
    }

    protected function getYearRange(): array
    {
        $year_hoy = date('Y');

        return [
            'year_hoy' => $year_hoy,
            'year_inicio' => $year_hoy - 125,
        ];
    }
}

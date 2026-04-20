<?php

namespace App\Controllers;

use App\Models\EventosModel;

class Home extends BaseController
{
    private $eventoModel;

    private $id;
    private $db;
    private $sql;

    public function __construct()
    {
        $this->eventoModel = new EventosModel();

        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        $data['titulo'] = 'Bienvenidos';

        $this->sql = $this->db->table('cercledartfoios AS cercle');
        $query = $this->sql->get();
        $resultado = $query->getRow();

        $data['cercle'] = [
            'texto' => $resultado->texto ?? '',
            'noticia' => $resultado->noticia ?? '',
            'banner' => $resultado->pinterest ?? 'anagramaColor.png',
            'visible' => $resultado->visible ?? 0,
        ];

        $data['eventos'] = $this->eventoModel->getProximosEventos();

        $this->sql = $this->db->table('enlaces_de_interes AS enlaces');
        $query = $this->sql->get();
        $resultado = $query->getResult();

        $data['enlaces'] = $resultado;

        return view('home',$data);
    }
}

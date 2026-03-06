<?php

namespace App\Controllers;

use App\Models\EventosModel;
use CodeIgniter\I18n\Time;

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
        
        $hoy = Time::createFromDate();
        $hoy = $hoy->toDateString();
        
        $this->sql = $this->db->table('cercledartfoios AS cercle');
        $query = $this->sql->get();
        $resultado = $query->getResult();
        
        $data['cercle']['texto'] = $resultado[0]->texto;
        $data['cercle']['noticia'] = $resultado[0]->noticia;
        $data['cercle']['banner'] = $resultado[0]->pinterest;
        $data['cercle']['visible'] = $resultado[0]->visible;
        
        $this->sql = $this->db->table('neventos AS eventos');
        $this->sql->select('eventos.*, eventos.desde, eventos.hasta, tiposeventos.eventonombre AS grupo' );
        $this->sql->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo');
        $this->sql->where('eventos.visible', '1');
        $this->sql->where('eventos.hasta >= '."'".$hoy."'");
        $this->sql->orderby('eventos.hasta' , 'DESC');
        
        $query = $this->sql->get();
        $resultado = $query->getResult();

        $data['eventos'] = $resultado;

        $this->sql = $this->db->table('enlaces_de_interes AS enlaces');
        $query = $this->sql->get();
        $resultado = $query->getResult();

        $data['enlaces'] = $resultado;

        return view('home',$data);
    }
}
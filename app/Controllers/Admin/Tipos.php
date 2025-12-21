<?php

namespace App\Controllers\Admin;

use App\Models\TiposEventosModel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Tipos extends BaseController
{

    public $model;


    public function __construct()
    {
        $this->model = new TiposEventosModel;
    }
    
    public function lista(){
        $tipos = $this->model->findAll();
        $data = [
            'titulo'    => 'Tipos de Evento',
            'tipos'  => $tipos,
        ];
        return view('admin/tipos/lista',$data);
    }

    public function new(){
        $data = [
            'titulo' => 'Creación de Tipos de Evento',
        ];
        return view('admin/tipos/nuevo', $data);
    }

    public function create(){
        $reglas = [
            'eventonombre'  => 'required|is_unique[tiposeventos.eventonombre]',
            'eventotipo'    => 'required|is_unique[tiposeventos.eventotipo]',
        ];
        
        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/tipos/nuevo', $_SERVER['REQUEST_SCHEME']))->withInput();
        }

        $post = $this->request->getPost();

        $this->model->insert([
            'eventonombre'  => $post['eventonombre'],
            'eventotipo'    => $post['eventotipo'],
        ]);

        return redirect()->to(base_url('control/tipos', $_SERVER['REQUEST_SCHEME']));
    }

    public function edit($id = null){
        $tipo = $this->model->asObject()->find($id);
        $data = [
            'titulo'    => 'Edición de Tipos de Evento',
            'id'        => $id,
            'tipo'      => $tipo, 
        ];
        return view('admin/tipos/editar', $data);
    }

    public function update($id = null){
        $reglas = [
            'eventonombre'  => 'required|is_unique[tiposeventos.eventonombre,id,'.$id.']',
            'eventotipo'    => 'required|is_unique[tiposeventos.eventotipo,id,'.$id.']',
        ];

        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/tipos/editar/'.$id, $_SERVER['REQUEST_SCHEME']))->withInput();
        }

        $post = $this->request->getPost();
        
        $datos = [
            'id'            => $id,
            'eventonombre'  => $post['eventonombre'],
            'eventotipo'    => $post['eventotipo'],
        ];
        
        $this->model->save($datos);

        return redirect()->to(base_url('control/tipos', $_SERVER['REQUEST_SCHEME']));
    }

    public function delete($id = null){
        $this->model->delete($id);
        return redirect()->to(base_url('control/tipos', $_SERVER['REQUEST_SCHEME']));
    }
}
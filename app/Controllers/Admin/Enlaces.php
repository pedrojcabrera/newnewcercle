<?php

namespace App\Controllers\Admin;

use App\Models\EnlacesModel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Enlaces extends BaseController
{

    public $model;


    public function __construct()
    {
        $this->model = new EnlacesModel;
    }
    
    public function lista(){
        $enlaces = $this->model->OrderBy('fecha_insercion','DESC')->findAll();
        $data = [
            'titulo'    => 'Enlaces de Interés',
            'enlaces'  => $enlaces,
        ];
        return view('admin/enlaces/lista',$data);
    }

    public function new(){
        $data = [
            'titulo' => 'Creación de Enlaces de Interés',
        ];
        return view('admin/enlaces/nuevo', $data);
    }

    public function create(){
        $reglas = [
            'texto'     => 'required',
            'enlace'    => 'required|valid_url',
        ];
        
        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/enlaces/nuevo'))->withInput();
        }

        $post = $this->request->getPost();

        $this->model->insert([
            'texto'     => $post['texto'],
            'enlace'    => $post['enlace'],
        ]);

        return redirect()->to(base_url('control/enlaces'));
    }

    public function edit($id = null){
        $enlace = $this->model->asObject()->find($id);
        $data = [
            'titulo'    => 'Edición de Enlace de Interés',
            'id'        => $id,
            'enlace'    => $enlace, 
        ];
        return view('admin/enlaces/editar', $data);
    }

    public function update($id = null){
        $reglas = [
            'texto'     => 'required',
            'enlace'    => 'required|valid_url',
        ];

        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/enlaces/editar/'.$id))->withInput();
        }

        $post = $this->request->getPost();
        
        $datos = [
            'id'        => $id,
            'texto'     => $post['texto'],
            'enlace'    => $post['enlace'],
        ];
        
        $this->model->save($datos);

        return redirect()->to(base_url('control/enlaces'));
    }

    public function delete($id = null){
        $this->model->delete($id);
        return redirect()->to(base_url('control/enlaces'));
    }
}
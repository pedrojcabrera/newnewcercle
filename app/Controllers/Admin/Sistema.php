<?php

namespace App\Controllers\Admin;

use App\Models\CercledartfoiosModel;

use App\Controllers\BaseController;

class Sistema extends BaseController
{

    public $model;
    public $randomName;
    public $sistema;

    public function __construct()
    {
        $this->model = new CercledartfoiosModel;
    }

    public function edit($id = 1){
        $sistema = $this->model->asObject()->find($id);
        $data = [
            'titulo'    => 'Edición del Sistema',
            'id'        => $id,
            'sistema'   => $sistema,
            'banner'    => $sistema->pinterest,
        ];


        $this->session->pinterest = $sistema->pinterest;

        return view('admin/sistema/editar', $data);
    }

    public function update($id = 1){

        $reglas = [
            'correo'        => 'required|valid_email',
            'facebook'      => 'permit_empty|valid_url',
            'youtube'       => 'permit_empty|valid_url',
            'instagram'     => 'permit_empty|valid_url',
            'elcaballete'   => 'permit_empty|valid_url',
        ];

        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/sistema'))->withInput();
        }

        $post = $this->request->getPost();

        $visible = isset($post['visible']) ? 1 : 0;

        $datos = [
            'id'        => $id,
            'noticia'   => trim($post['noticia']),
            'texto'     => $post['texto'],
            'direccion' => trim($post['direccion']),
            'correo'    => trim($post['correo']),
            'facebook'  => trim($post['facebook']),
            'youtube'   => trim($post['youtube']),
            'instagram' => trim($post['instagram']),
            'elcaballete' => trim($post['elcaballete']),
            'visible'   => $visible,
        ];

        $imagen = $this->request->getFile('banner');

        $destino = FCPATH.'recursos/imagenes/';

        if($imagen && $imagen->isValid() && !$imagen->hasMoved() && $imagen->getClientName() !== '') {
            $banner = $imagen->getRandomName(); //.'.'.$ext;
            $banner_antiguo = $post['pinterest'];
            if(!empty($banner_antiguo) && file_exists($destino.$banner_antiguo)) {
                unlink($destino.$banner_antiguo);
            }
            $datos['pinterest'] = $banner;
        }

        $this->model->save($datos);

        if($imagen && $imagen->isValid() && !$imagen->hasMoved() && $imagen->getClientName() !== ''){
            $this->_upload($imagen,$destino,$banner);
        }

        return redirect()->to(base_url('control/sistema'));
    }

    private function _upload($imagen,$destino,$banner) {
        if($imagen->isValid() && !$imagen->hasMoved()) {
            $validated = $this->validate([
                'banner' => [
                    'uploaded[banner]',
                    'mime_in[banner,image/jpg,image/jpeg,image/png]',
                ]
            ]);
            if($validated){
                $destino = FCPATH.'recursos/imagenes/';
                $imagen->move($destino,$banner);
            }
        }
    }
}

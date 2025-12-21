<?php

namespace App\Controllers\Admin;

use App\Models\UsuariosModel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Usuarios extends BaseController
{

    public $model;


    public function __construct()
    {
        $this->model = new UsuariosModel;
    }
    
    public function lista(){
        $usuarios = $this->model->OrderBy('nombre')->findAll();
        $data = [
            'titulo' => 'Usuarios',
            'usuarios' => $usuarios
        ];
        return view('admin/usuarios/lista',$data);
    }

    public function new(){
        $data = [
            'titulo' => 'Creación de Usuarios',
        ];
        return view('admin/usuarios/nuevo', $data);
    }

    public function create(){
        $reglas = [
            'usuario'   => 'required|is_unique[usuarios.user]',
            'nombre'    => 'required',
            'password'  => 'required',
            'repetir'   => 'matches[password]',
            'correo'    => 'valid_email',
            'web'       => 'valid_url',
        ];
        if(!$this->validate($reglas)) {
            dd($this->validator->listErrors());
            return redirect()->to(base_url('control/usuarios/nuevo', $_SERVER['REQUEST_SCHEME']))->withInput();
        }
        $post = $this->request->getPost();

        $admin = isset($post['admin']) ? 1 : 0;
        $this->model->insert([
            'user'      => trim($post['usuario']),
            'pass'      => password_hash($post['password'], PASSWORD_DEFAULT),
            'nombre'    => trim($post['nombre']),
            'correo'    => trim($post['correo']),
            'telefono'  => trim($post['telefono']),
            'enlaces'   => esc(trim($post['enlaces'])),
            'web'       => trim($post['web']),
            'texto'     => esc($post['texto']),
            'admin'     => $admin,
        ]);
        $id = $this->model->getInsertID();
        $this->_upload($id);
        
        return redirect()->to(base_url('control/usuarios', $_SERVER['REQUEST_SCHEME']));
    }

    public function edit($id = null){
        $usuario = $this->model->asObject()->find($id);
        $data = [
            'titulo'    => 'Edición de Usuarios',
            'id'        => $id,
            'usuario'   => $usuario,
            'fotoUser'  => 'fotosUsuarios/'.$id.'.jpg',
        ];
        return view('admin/usuarios/editar', $data);
    }

    public function update($id = null){
        
        $reglas = [
            'usuario'   => 'required',
            'nombre'    => 'required',
            'repetir'   => 'matches[password]',
            'correo'    => 'valid_email',
            'web'       => 'permit_empty|valid_url',
        ];

        if(!$this->validate($reglas)) {
            $usuario = $this->model->asObject()->find($id);
            $data = [
                'titulo'    => 'Edición de Usuarios',
                'id'        => $id,
                'usuario'   => $usuario,
                'fotoUser'  => 'fotosUsuarios/'.$id.'.jpg',
                'validation'=> $this->validator,
            ];
                return view('admin/usuarios/editar',$data);
        }

        $post = $this->request->getPost();
        $admin = isset($post['admin']) ? 1 : 0;
        $datos = [
            'id'        => $id,
            'user'      => trim($post['usuario']),
            'nombre'    => trim($post['nombre']),
            'correo'    => trim($post['correo']),
            'telefono'  => trim($post['telefono']),
            'enlaces'   => trim($post['enlaces']),
            'web'       => trim($post['web']),
            'texto'     => $post['texto'],
            'admin'     => $admin
        ];

        if(!empty($pass)) {
            $datos['pass'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }
        
        if(isset($post['borrar_foto'])){
            $destino = FCPATH.'fotosUsuarios/';
            $fotoUser = $id.'.jpg';
            if (file_exists($destino.$fotoUser)) {
                unlink($destino.$fotoUser);
            }
        }
        
        $this->model->save($datos);
        
        if($imagen = $this->request->getFile('foto')){
            $this->_upload($id);
        }
        return $this->lista();
        //return redirect()->to(base_url('control/usuarios', $_SERVER['REQUEST_SCHEME']));
    }

    public function delete($id = null){
        $fotoUser = FCPATH.'fotosUsuarios/'.$id.'.jpg';
        if (file_exists($fotoUser)) {
            unlink($fotoUser);
        }
        $this->model->delete($id);
        return $this->lista();
    }

    private function _upload($id = null) {
        if($imagen = $this->request->getFile('foto')){
            if($imagen->isValid() && !$imagen->hasMoved()) {
                $validated = $this->validate([
                    'foto' => [
                        'uploaded[foto]',
                        'mime_in[foto,image/jpg,image/jpeg]',
                    ]
                ]);
                if($validated){
                    $destino = FCPATH.'fotosUsuarios/';
                    $fotoUser = $id.'.jpg';
                    if (file_exists($destino.$fotoUser)) {
                        unlink($destino.$fotoUser);
                    }
                    $imagen->move($destino,$fotoUser);
                }
            }
        }
    }
}
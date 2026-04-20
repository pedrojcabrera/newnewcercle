<?php

namespace App\Controllers\Admin;

use App\Models\UsuariosModel;

use App\Controllers\BaseController;

class Usuarios extends BaseController
{

    public $model;

    private function construirPayloadUsuario(array $post, ?int $id = null, bool $escaparContenido = false): array
    {
        $payload = [
            'user'      => trim((string) ($post['usuario'] ?? '')),
            'nombre'    => trim((string) ($post['nombre'] ?? '')),
            'correo'    => trim((string) ($post['correo'] ?? '')),
            'telefono'  => trim((string) ($post['telefono'] ?? '')),
            'enlaces'   => trim((string) ($post['enlaces'] ?? '')),
            'web'       => trim((string) ($post['web'] ?? '')),
            'texto'     => (string) ($post['texto'] ?? ''),
            'admin'     => isset($post['admin']) ? 1 : 0,
        ];

        if ($escaparContenido) {
            $payload['enlaces'] = esc($payload['enlaces']);
            $payload['texto'] = esc($payload['texto']);
        }

        if ($id !== null) {
            $payload['id'] = $id;
        }

        return $payload;
    }


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
            'web'       => 'permit_empty|valid_url',
        ];
        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/usuarios/nuevo'))->withInput();
        }
        $post = $this->request->getPost();

        $datos = $this->construirPayloadUsuario($post, null, true);
        $datos['pass'] = password_hash((string) ($post['password'] ?? ''), PASSWORD_DEFAULT);

        $this->model->insert($datos);
        $id = $this->model->getInsertID();
        $this->_upload($id);

        return redirect()->to(base_url('control/usuarios'));
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
            'usuario'   => 'required|is_unique[usuarios.user,id,'.$id.']',
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
        $password = trim((string) ($post['password'] ?? ''));
        $datos = $this->construirPayloadUsuario($post, (int) $id, false);

        if($password !== '') {
            $datos['pass'] = password_hash($password, PASSWORD_DEFAULT);
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

        return redirect()->to(base_url('control/usuarios'));
    }

    public function delete($id = null){
        $fotoUser = FCPATH.'fotosUsuarios/'.$id.'.jpg';
        if (file_exists($fotoUser)) {
            unlink($fotoUser);
        }
        $this->model->delete($id);

        return redirect()->to(base_url('control/usuarios'));
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

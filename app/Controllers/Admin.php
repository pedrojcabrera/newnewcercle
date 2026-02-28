<?php

namespace App\Controllers;

use App\Models\UsuariosModel;

class Admin extends BaseController
{
    private $UsuarioModel;

    private $id;
    private $db;
    private $sql;

    public function __construct()
    {
        $this->UsuarioModel = new UsuariosModel();

        $this->db = \Config\Database::connect();
        $this->sql = $this->db->table('usuarios');

    }
    public function index(){

        if(!$this->session->logueado){
            return $this->login(); //redirect()->route(base_url('login'));
        }
        return view('admin/dashboard',['titulo'=>'Administración']);
    }

    public function login(){

        if($this->UsuarioModel->where('user', "admin")->countAllResults() < 1) {
            // Usar variable de entorno para la contraseña de admin por defecto
            $defaultAdminPassword = env('defaultAdminPassword', 'Itvitv123');
            $admin = [
                'user' => 'admin',
                'pass' => password_hash($defaultAdminPassword, PASSWORD_DEFAULT),
                'admin'=> 1
            ];
        }

        $this->db = \Config\Database::connect();
        $this->sql = $this->db->table('usuarios');

        if(!$this->session->logueado){
            return view('login',['titulo'=>'Acceder']);
        }else {
            return $this->dashboard();
        }
    }

    public function validar(){
        $user = $this->request->getPost('user');
        $pass = $this->request->getPost('pass');

        $user = $this->UsuarioModel->asObject()->where('user',$user)->first();
        if($user and (strlen($user->pass) == 32)) {

            $id = $user->id;
            $password = password_hash($pass, PASSWORD_DEFAULT);
            $data = [
                'pass' => $password
            ];
            if($this->UsuarioModel->update($id, $data)) {
                return view('login',['titulo'=>'Acceder']);
            }
        }

        if(!$user or !password_verify($pass, $user->pass) or !$user->admin){
            return view('login',['titulo'=>'Acceder']);
        }

        $data_session = [
            'user' => $user->user,
            'user_id' => $user->id,
            'user_name' => $user->nombre,
            'user_admin' => $user->admin,
            'logueado' => true
        ];

        $this->session->set($data_session);

        return $this->dashboard();

    }

    public function dashboard(){
        return view('admin/dashboard',['titulo'=>'Administración']);

    }

    public function logout(){
        if($this->session->logueado){
            $this->session->destroy();
        }
        return redirect()->to(base_url('admin'));

    }

}

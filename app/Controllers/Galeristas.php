<?php
namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\GaleriasModel;

class Galeristas extends BaseController
{
    private $UsuarioModel;
    private $GaleriaModel;
    
    public function __construct()
    {
        $this->UsuarioModel = new UsuariosModel();
        $this->GaleriaModel = new GaleriasModel();

        $this->session = session();

    }
    
    public function index(){
        if(!$this->session->hayGalerias){
            return $this->login();
        }
        return redirect()->route('galeristas/lista');
    }
    
    public function login(){

        if($this->UsuarioModel->where('user', "admin")->countAllResults() < 1) {
            $admin = [
                'user' => 'admin',
                'pass' => password_hash('Itvitv123', PASSWORD_DEFAULT),
                'admin'=> 1
            ];
        }

        if(!$this->session->hayGalerias){
            return view('galeristas/login',['titulo'=>'Acceder']);
        }else {
            return redirect()->route('galeristas/validar');
        } 
    }
    
    public function validar(){
        $user = $this->request->getPost('user');
        $pass = $this->request->getPost('pass');

        $artista = $this->UsuarioModel
                        ->asObject()
                        ->where('user',$user)
                        ->first();
        
        if($artista and (strlen($artista->pass) == 32)) {
            $id = $artista->id;
            $password = password_hash($pass, PASSWORD_DEFAULT);
            $data = [
                'pass' => $password
            ];
            if($this->UsuarioModel->update($id, $data)) {
                return view('galeristas/login',['titulo'=>'Acceder']);
            }
        }
        
        if(!$artista or !password_verify($pass, $artista->pass)){
            return view('galeristas/login',['titulo'=>'Acceder']);
        }

        $data_session = [
            'artista' => $artista->user,
            'artista_id' => $artista->id,
            'artista_name' => $artista->nombre,
            'artista_admin' => $artista->admin,
            'hayGalerias' => true
        ];

        $this->session->set($data_session);

        return redirect()->route('galeristas/lista');
    }

    public function lista() {
        
        $id = $this->session->get('artista_id');
        
        $artista = $this->UsuarioModel->find($id);
        
        $obras = $this->GaleriaModel
                      ->asObject()
                      ->where('id_user',$id)
                      ->findAll();

        $datos = [
            'data'      => $artista,
            'obras'     => $obras,
            'titulo'    => 'Galería',
            'hayLogout' => true,
        ];
                      
        return view('galeristas/lista', $datos);
    }

    public function logout(){
        if($this->session->hayGalerias){
            $this->session->destroy();
        }
        return redirect()->to(base_url('galeristas'));
        
    }

    public function nuevo() {
        $id = $this->session->get('artista_id');
        $artista = $this->UsuarioModel->find($id);
        $year_hoy = date("Y");
        $year_inicio = $year_hoy - 125;

        $datos = [
            'usuario'       => $artista,
            'titulo'        => 'Nueva Obra en Galería',
            'year_hoy'      => $year_hoy,
            'year_inicio'   => $year_inicio,
        ];
        return view('galeristas/nuevo',$datos);
    }

    public function crear() {

        $validation = \Config\Services::validation();

        $id = session()->get('artista_id');
        $usuario = $this->UsuarioModel->find($id);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'titulo'    => 'required',
                'tecnica'   => 'required',
                'soporte'   => 'required',
                'medidas'   => 'required',
                'imagen' => [
                    'uploaded[imagen]',
                    'mime_in[imagen,image/jpg,image/jpeg]',
                    'max_size[imagen,2048]' // Máximo 2MB
                ],
            ];

            $year_hoy = date("Y");
            $year_inicio = $year_hoy - 125;

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $data = [
                'id_user'       => $id,
                'autores'       => $this->request->getPost('autor'),
                'titulo'        => $this->request->getPost('titulo'),
                'tecnica'       => $this->request->getPost('tecnica'),
                'soporte'       => $this->request->getPost('soporte'),
                'medidas'       => $this->request->getPost('medidas'),
                'premios'       => $this->request->getPost('premios'),
                'comentarios'   => $this->request->getPost('comentarios'),
                'ano'           => $this->request->getPost('ano'),
                'precio'        => $this->request->getPost('precio'),
            ];

            if($this->GaleriaModel->insert($data)) {

                $imagen = $this->request->getFile('imagen');
                if ($imagen->isValid() && !$imagen->hasMoved()) {

                    $rutaDestino = "galerias/".$id;
                    $nombreObra = $this->GaleriaModel->insertID().".jpg";
                    
                    // Crear la carpeta si no existe
                    if (!is_dir($rutaDestino)) {
                        mkdir($rutaDestino, 0777, true);
                    }
                    
                    // Mover la imagen

                    if ($imagen->move($rutaDestino, $nombreObra, true)) {
                        return redirect()->route('galeristas/lista');
                    } else {
                        return redirect()->back()->with('error', 'Error al subir la imagen.');
                    }
                }
            }
        }
        return redirect()->route('galeristas/lista');
    }

    public function cancelar() {
        return redirect()->route('galeristas/lista');
    }

    public function quitar($obra=false) {

        $artista = session()->get('artista_id');
        
        if(!$obra) {
            return redirect()->back()->withInput();
        }
        
        if($this->GaleriaModel->delete($obra)) {
            $destino = "galerias/$artista/$obra.jpg";
            if (file_exists($destino)) {
                unlink($destino);
            }
        }
        return redirect()->route('galeristas/lista');
    }

    public function editar($obra_id= false) {
        
        if(!$obra_id) {
            return redirect()->back()->withInput();
        }

        $regObra = $this->GaleriaModel->find($obra_id);
        
        if(!$regObra) {
            return redirect()->back()->withInput();
        }

        $regArtista = $this->UsuarioModel->find($regObra->id_user);
        
        if(!$regArtista) {
            return redirect()->back()->withInput();
        }

        $year_hoy = date("Y");
        $year_inicio = $year_hoy - 125;

        $datos = [
            'usuario'       => $regArtista,
            'obra'          => $regObra,
            'titulo'        => 'Edición de Obra en Galería',
            'year_hoy'      => $year_hoy,
            'year_inicio'   => $year_inicio,
        ];
        return view('galeristas/editar',$datos);
        
    }

    public function modificar($obra_id) {
        $validation = \Config\Services::validation();

        $id = session()->get('artista_id');
        $usuario = $this->UsuarioModel->find($id);

        if ($this->request->getMethod() === 'PUT') {
            $rules = [
                'titulo'    => 'required',
                'tecnica'   => 'required',
                'soporte'   => 'required',
                'medidas'   => 'required',
                'imagen' => [
                    'permit_empty',
                    'uploaded[imagen]',
                    'mime_in[imagen,image/jpg,image/jpeg]',
                    'max_size[imagen,2048]' // Máximo 2MB
                ],
            ];

            $year_hoy = date("Y");
            $year_inicio = $year_hoy - 125;

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $data = [
                'id_user'       => $id,
                'autores'       => $this->request->getPost('autor'),
                'titulo'        => $this->request->getPost('titulo'),
                'tecnica'       => $this->request->getPost('tecnica'),
                'soporte'       => $this->request->getPost('soporte'),
                'medidas'       => $this->request->getPost('medidas'),
                'premios'       => $this->request->getPost('premios'),
                'comentarios'   => $this->request->getPost('comentarios'),
                'ano'           => $this->request->getPost('ano'),
                'precio'        => $this->request->getPost('precio'),
            ];

            if($this->GaleriaModel->update($obra_id, $data)) {
                $imagen = $this->request->getFile('imagen');
                if ($imagen->isValid() && !$imagen->hasMoved()) {

                    $rutaDestino = "galerias/".$id;
                    $nombreObra = $obra_id.".jpg";
                    
                    // Crear la carpeta si no existe
                    if (!is_dir($rutaDestino)) {
                        mkdir($rutaDestino, 0777, true);
                    }
                    
                    // Mover la imagen

                    if ($imagen->move($rutaDestino, $nombreObra, true)) {
                        return redirect()->route('galeristas/lista');
                    } else {
                        return redirect()->back()->with('error', 'Error al subir la imagen.');
                    }
                }
            }
        }
        return redirect()->route('galeristas/lista');
    }

}
<?php

namespace App\Controllers\Admin;

use App\Models\ContactosModel;
use App\Models\InvitadosModel;
use App\Models\InscritosModel;
use App\Models\EnEsperaModel;
use App\Models\MailingsDetallesModel;
use App\Models\MailingsResumenModel;
use App\Models\EventosModel;
use App\Models\CorreosModel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Contactos extends BaseController
{

    public $model;


    public function __construct()
    {
        $this->model = new ContactosModel;
    }
    
    public function lista(){
        $contactos = $this->model->OrderBy('nombre')->findAll();
        $data = [
            'titulo'    => 'Contactos',
            'contactos'  => $contactos,
            'calidades' => [
                'mailing'       => 'Correos',
                'invitaciones'  => 'Invitaciones',
                'socio'         => 'Socio',
                'alumno'        => 'Alumno',
                'pdalumno'      => 'Padre/Madre',
                'pintor'        => 'Pintor',
                'dtaller'       => 'Talleres',
                'amigo'         => 'Amigo',
            ],
        ];
        return view('admin/contactos/lista',$data);
    }

    public function new(){
        $data = [
            'titulo' => 'Creación de Contactos',
        ];
        return view('admin/contactos/nuevo', $data);
    }

    public function create(){
        $reglas = [
            'nombre'    => 'required',
            'telefono'  => 'required',
            'correo'    => 'valid_email|is_unique[contactos.email]',
            'dni'       => 'permit_empty|is_unique[contactos.dni]',   
        ];
        
        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/contactos/nuevo', $_SERVER['REQUEST_SCHEME']))->withInput();
        }

        $post = $this->request->getPost();

        $mailing = isset($post['mailing']) ? 1 : 0;
        $invitaciones = isset($post['invitaciones']) ? 1 : 0;
        $socio = isset($post['socio']) ? 1 : 0;
        $alumno = isset($post['alumno']) ? 1 : 0;
        $pdalumno = isset($post['pdalumno']) ? 1 : 0;
        $pintor = isset($post['pintor']) ? 1 : 0;
        $dtaller = isset($post['dtaller']) ? 1 : 0;
        $amigo = isset($post['amigo']) ? 1 : 0;
        
        $this->model->insert([
            'nombre'        => trim($post['nombre']),
            'apellidos'     => trim($post['apellidos']),
            'email'         => trim($post['correo']),
            'dni'           => trim($post['dni']),
            'telefono'      => trim($post['telefono']),
            'direccion'     => trim($post['direccion']),
            'poblacion'     => trim($post['poblacion']),
            'codpostal'     => trim($post['codpostal']),
            'provincia'     => trim($post['provincia']),
            'mailing'       => $mailing,
            'invitaciones'  => $invitaciones,
            'socio'         => $socio,
            'alumno'        => $alumno,
            'pdalumno'      => $pdalumno,
            'pintor'        => $pintor,
            'dtaller'       => $dtaller,
            'amigo'         => $amigo,
        ]);

        return redirect()->to(base_url('control/contactos', $_SERVER['REQUEST_SCHEME']));
    }

    public function edit($id = null){
        $contacto = $this->model->asObject()->find($id);
        $data = [
            'titulo'    => 'Edición de Contactos',
            'id'        => $id,
            'contacto'   => $contacto,
        ];
        return view('admin/contactos/editar', $data);
    }

    public function update($id = null){
        $reglas = [
            'nombre'    => 'required',
            'telefono'  => 'required',
            'correo'    => 'valid_email|is_unique[contactos.email,id,'.$id.']',
            'dni'       => 'permit_empty|is_unique[contactos.dni,id,'.$id.']',
        ];

        if(!$this->validate($reglas)) {
            return redirect()->to(base_url('control/contactos/editar/'.$id, $_SERVER['REQUEST_SCHEME']))->withInput();
        }

        $post = $this->request->getPost();

        $mailing        = isset($post['mailing']) ? 1 : 0;
        $invitaciones   = isset($post['invitaciones']) ? 1 : 0;
        $socio          = isset($post['socio']) ? 1 : 0;
        $alumno         = isset($post['alumno']) ? 1 : 0;
        $pdalumno       = isset($post['pdalumno']) ? 1 : 0;
        $pintor         = isset($post['pintor']) ? 1 : 0;
        $dtaller        = isset($post['dtaller']) ? 1 : 0;
        $amigo          = isset($post['amigo']) ? 1 : 0;
        
        $datos = [
            'id'            => $id,
            'nombre'        => trim($post['nombre']),
            'apellidos'     => trim($post['apellidos']),
            'email'         => trim($post['correo']),
            'dni'           => trim($post['dni']),
            'telefono'      => trim($post['telefono']),
            'direccion'     => trim($post['direccion']),
            'poblacion'     => trim($post['poblacion']),
            'codpostal'     => trim($post['codpostal']),
            'provincia'     => trim($post['provincia']),
            'mailing'       => $mailing,
            'invitaciones'  => $invitaciones,
            'socio'         => $socio,
            'alumno'        => $alumno,
            'pdalumno'      => $pdalumno,
            'pintor'        => $pintor,
            'dtaller'       => $dtaller,
            'amigo'         => $amigo,
        ];
        
        $this->model->save($datos);

        return redirect()->to(base_url('control/contactos', $_SERVER['REQUEST_SCHEME']));
    }

    public function delete($id = null){
        $this->model->delete($id);
        return redirect()->to(base_url('control/contactos', $_SERVER['REQUEST_SCHEME']));
    }

    public function historia($id = null) {

        $modelInvitados = new InvitadosModel();
        $modelInscritos = new InscritosModel();
        $modelEnEspera  = new EnEsperaModel();
        $modelDetalles  = new mailingsDetallesModel();
        $modelResumen   = new MailingsResumenModel();
        $modelEventos   = new EventosModel();
        $modelCorreos   = new CorreosModel();

        $contacto = $this->model->find($id);

        $histEventos    = [];
        $histInscritos  = [];
        $histEsperando  = [];
        $histCorreos    = [];

        $invitaciones = $modelInvitados
                        ->where('id_contacto =', $id)
                        ->orderBy('id_evento', 'DESC')
                        ->findAll();

        foreach($invitaciones as $invitacion) {
            $evento     = $modelEventos->find($invitacion->id_evento);

            $estado = 'Sin Respuesta';
            if($invitacion->inscrito) $estado = 'Inscrito';
            if($invitacion->enespera) $estado = 'En espera';

            $color = 'danger';
            if($invitacion->inscrito) $color = 'success';
            if($invitacion->enespera) $color = 'info';

            $histEvento = [
                'id' => $evento->id,
                'evento' => $evento->titulo,
                'inicio' => $evento->desde,
                'fin' => $evento->hasta,
                'invitacion' => $invitacion->fecha,
                'estado' => $estado,
                'color' => $color,
            ];
            $histEventos[] = $histEvento;
        }

        $inscripciones = $modelInscritos
                        ->where('id_contacto =', $id)
                        ->orderBy('id_evento', 'DESC')
                        ->findAll();

        foreach($inscripciones as $inscrito) {
            $evento     = $modelEventos->find($inscrito->id_evento);
            $histInscrito = [
                'id' => $evento->id,
                'evento' => $evento->titulo,
                'inicio' => $evento->desde,
                'fin' => $evento->hasta,
                'inscripcion' => $inscrito->fecha,
            ];
            $histInscritos[] = $histInscrito;
        }

        $esperando = $modelEnEspera
                   ->where('id_contacto =', $id)
                   ->orderBy('id_evento', 'DESC')
                   ->findAll();

        foreach($esperando as $enespera) {
            $evento     = $modelEventos->find($enespera->id_evento);
            $histEnEspera = [
                'id' => $enespera->id,
                'evento' => $evento->titulo,
                'inicio' => $evento->desde,
                'fin' => $evento->hasta,
            ];
            $histEsperando[] = $histEnEspera;
        }

        $detalles   = $modelDetalles
                    ->where('id_contacto =', $id)
                    ->orderBy('id_mailing', 'DESC')
                    ->findAll();

        foreach($detalles as $detalle) {
            $correo     = $modelCorreos->find($detalle->id_correo);
            $resumen    = $modelResumen->find($detalle->id_mailing);

            $destino = [];
            if($resumen->socios)    $destino[]  = 'Socios';
            if($resumen->alumnos)   $destino[]  = 'Alumnos';
            if($resumen->padres)    $destino[]  = 'Padres';
            if($resumen->artistas)  $destino[]  = 'Pintores';
            if($resumen->talleres)  $destino[]  = 'Talleres';
            if($resumen->amigos)    $destino[]  = 'Amigos';

            $histCorreo = [
                'asunto'    => $correo->asunto,
                'fecha'     => $resumen->fechahora,
                'destinos'  => nl2br(implode("\n",$destino)),
            ];
            
            $histCorreos[] = $histCorreo;
        }
        
        $data = [
            'titulo'    => 'Histórico',
            'id'        => $id,
            'contacto'  => trim($contacto->nombre.' '.$contacto->apellidos),
            'eventos'   => $histEventos,
            'inscritos' => $histInscritos,
            'esperando' => $histEsperando,
            'correos'   => $histCorreos,
        ];

        return view('admin/contactos/historia', $data);
    }
}
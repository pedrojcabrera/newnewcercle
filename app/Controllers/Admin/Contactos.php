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
        $data = [
            'titulo'    => 'Contactos',
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

    public function getContactosAjax(){
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Solo peticiones AJAX']);
        }

        $request = $this->request->getGet();

        // Parámetros de DataTables
        $draw = intval($request['draw'] ?? 1);
        $start = intval($request['start'] ?? 0);
        $length = intval($request['length'] ?? 10);
        $searchValue = $request['search']['value'] ?? '';

        // Columnas para ordenación
        $columns = ['id', 'nombre', 'email', 'calidades'];
        $orderColumnIndex = intval($request['order'][0]['column'] ?? 0);
        $orderDir = $request['order'][0]['dir'] ?? 'asc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'nombre';

        // Obtener contactos con calidades concatenadas en SQL (mucho más rápido)
        $contactos = $this->model->getContactosConCalidades(
            $length,
            $start,
            $searchValue,
            $orderColumn,
            $orderDir
        );

        // Total de registros filtrados
        $recordsFiltered = $this->model->countContactosFiltrados($searchValue);

        // Total de registros sin filtrar
        $recordsTotal = $this->model->countAll();

        // Preparar datos para DataTables
        $data = [];

        foreach ($contactos as $contacto) {
            // Construir lista de calidades desde el campo concatenado
            $calidadesHtml = '';
            if (!empty($contacto->calidades_concat)) {
                $calidadesArray = explode('|', $contacto->calidades_concat);
                $calidadesArray = array_filter($calidadesArray); // Eliminar valores vacíos
                if (!empty($calidadesArray)) {
                    $calidadesHtml = '<ul>' .
                        implode('', array_map(fn($c) => '<li class="calidades"><small class="form-text text-muted">' . $c . '</small></li>', $calidadesArray)) .
                        '</ul>';
                }
            }

            // Construir acciones
            $acciones = '<a title="Editar" class="btn btn-success btn-sm bi-pencil" href="' . base_url('control/contactos/editar/' . $contacto->id) . '"> Editar</a> ';
            $acciones .= '<form style="display: inline;" action="' . base_url('control/contactos/' . $contacto->id) . '" method="POST">';
            $acciones .= '<input type="hidden" name="_method" value="DELETE">';
            $acciones .= '<button type="submit" title="Borrar" class="btn btn-danger btn-sm bi-eraser" onclick="return confirm(\'¿ Confirma el borrado ?\');"> Borrar</button>';
            $acciones .= '</form> ';
            $acciones .= '<a title="Historia" class="btn btn-secondary btn-sm bi-clock-history" href="' . base_url('control/contactos/historia/' . $contacto->id) . '"> Historia</a> ';
            $acciones .= '<a title="Inscribir" class="btn btn-warning btn-sm bi-pencil-square" href="' . base_url('control/inscripcionManual/' . $contacto->id) . '"> Inscribir</a>';

            $data[] = [
                '<span>' . $contacto->id . '</span>',
                '<small>' . trim($contacto->nombre . ' ' . $contacto->apellidos) .
                    (!empty($contacto->dni) ? '<br>DNI: ' . $contacto->dni : '') . '</small>',
                '<small>' . trim($contacto->email) . '<br>Tel: ' . trim($contacto->telefono) . '</small>',
                $calidadesHtml,
                '<div class="text-end ico-acciones">' . $acciones . '</div>'
            ];
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
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
            return redirect()->to(base_url('control/contactos/nuevo'))->withInput();
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

        return redirect()->to(base_url('control/contactos'));
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
            return redirect()->to(base_url('control/contactos/editar/'.$id))->withInput();
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

        return redirect()->to(base_url('control/contactos'));
    }

    public function delete($id = null){
        $this->model->delete($id);
        return redirect()->to(base_url('control/contactos'));
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

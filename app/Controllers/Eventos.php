<?php

namespace App\Controllers;

use App\Models\EventosModel;
use App\Models\EnEsperaModel;
use App\Models\InvitadosModel;
use App\Models\ContactosModel;
use CodeIgniter\I18n\Time;

class Eventos extends BaseController
{
    private $eventoModel;
    
    private $id;
    private $db;
    private $sql;

    public function __construct()
    {
        $this->eventoModel = new EventosModel();

        $this->db = \Config\Database::connect();
        $this->sql = $this->db->table('neventos AS eventos');

    }

    public function ultimos(): string
    {
        $hoy = Time::createFromDate();
        $hoy = $hoy->toDateString();
        
        $this->sql->select('eventos.*, eventos.desde, eventos.hasta, tiposeventos.eventonombre AS grupo' );
        $this->sql->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo');
        $this->sql->where('eventos.visible', '1');
        $this->sql->where('eventos.hasta < '."'".$hoy."'");
        $this->sql->orderby('eventos.hasta' , 'DESC');

        $query = $this->sql->get(3,0);

        $resultado = $query->getResult();

        $data = [
            'titulo'    => 'Últimos Eventos',
            'eventos' => $resultado
        ];

        return view('eventos/ultimos',$data);
    }

    public function lista(): string
    {
        $this->sql->select('eventos.*, tiposeventos.eventonombre AS grupo' );
        $this->sql->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo');
        $this->sql->where('eventos.visible = 1');
        $this->sql->orderby('eventos.hasta' , 'DESC');
        
        $query = $this->sql->get();

        $resultado = $query->getResult();

        $data = [
            'titulo'    => 'Histórico de Eventos',
            'eventos' => $resultado
        ];

        return view('eventos/lista',$data);
    }

    public function show($id)
    {
        $this->sql->select('eventos.*, tiposeventos.eventonombre AS grupo' );
        $this->sql->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo');
        $this->sql->where('eventos.id' , $id);
        
        $query = $this->sql->get();

        $resultado = $query->getResult();
        if(!$resultado) return redirect()->back();;
        $evento_actual = $resultado[0];
        
        $pdf = false;
        
        $pdf_adjunto = "pdfEventos/pdf_".$id.".pdf";
        if (file_exists($pdf_adjunto)) {
            $pdf = $pdf_adjunto;
        }

        $inscripcion = false;
        if($evento_actual->inscripcion > 0) {
            $inscripcion = (uti_estado_evento($evento_actual->desde,$evento_actual->hasta) == "PROXIMAMENTE" OR uti_estado_evento($evento_actual->desde,$evento_actual->hasta) == "EN CURSO" ) ? true : false;
        }

        // Busca Fotos

        $dirtoscan = dirname($_SERVER['PHP_SELF']);

        if(is_dir('imgEventos/ev_'.$evento_actual->id)) {
            $imgs = scandir('imgEventos/ev_'.$evento_actual->id);
        } else {
            $imgs = [];
        }

        $fotos = [];
        foreach($imgs as $img) {
            if(!in_array($img,['.','..','Cartel.jpg','cartel.jpg'])){
                $fotos[] = $img;
            }
        }
        $hoy = date('Y-m-d');
        $desde = $evento_actual->desde_inscripcion;
        $hasta = $evento_actual->hasta_inscripcion;
        $admiteInscripcion = ($hoy >= $desde && $hoy <= $hasta && $inscripcion) ? true : false;
        $data = [
            'titulo'    => 'Detalles del Evento',
            'evento' => $evento_actual,
            'pdf' => $pdf,
            'inscripcion' => $admiteInscripcion,
            'fotos' => $fotos
        ];
        
        return view('eventos/show',$data);
    }

    public function inscribirse($id, $contacto_id = 0) {

        $evento = $this->eventoModel->find($id);

        $data = [
            'titulo'    => 'Inscripción',
            'evento_titulo' => $evento->titulo,
            'evento_id' => $id,
        ];

        if($contacto_id) {
            $invitadoM = new InvitadosModel();
            $invitado  = $invitadoM
            ->where('id_contacto',$contacto_id)
            ->where('id_evento',$id)
            ->first();

            if(!$invitado) {
                return view('respuestasCorreo/noHayInvitacion',$data);
            }

            if($invitado->enespera) {
                return view('respuestasCorreo/registroRepetido',$data);
            }

            if($invitado->inscrito) {
                return view('respuestasCorreo/yaRegistrado',$data);
            }

            $contactoModel = new ContactosModel();
            $contacto = $contactoModel->find($contacto_id);
            $data['contacto'] = $contacto;
            $data['invitado'] = $invitado;
            
            return view('eventos/inscribirseSinMenu',$data);
        }
        return view('eventos/inscribirse',$data);
    }

    public function inscripcion() {

        $data = $this->request->getPost();

        $datos = [
            'id' => $data['id'],
            'titulo' => 'Resultado del Envío',
            'nuevo_titulo' => 'Detalle del Evento'
        ];

        $datosIns['id_evento'] = $data['id'];
        $datosIns['id_invitado'] = $data['id_invitado'];
        $datosIns['id_contacto'] = $data['id_contacto'];
        $datosIns['nombre'] = $data['nombre'];
        $datosIns['apellidos'] = $data['apellidos'];
        $datosIns['email'] = $data['email'];
        $datosIns['telefono'] = $data['telefono'];

        $esperandoM = new EnEsperaModel();
        
        $result = $esperandoM->insert($datosIns);

        if ($result) {
            
            $inviData = ['enespera' => 1];
            
            $invitados  = new InvitadosModel();
            $invitados->update($data['id_invitado'],$inviData);
            
            $email = \Config\Services::email();

            $email->setFrom('noreply@cercledartfoios.com',"Cercle d'Art Foios",'rechazados@cercledartfoios.com');
            $email->setTo('correo@cercledartfoios.com');

            $email->setSubject('Solicitud de Inscripción a Evento');

            $mensaje  = "<h2>Solicitud de Inscripción a Evento</h2>";
            $mensaje .= "<h5>desde la página Web</h5><hr>";
            $mensaje .= "<table>";
            $mensaje .= "<tr><td><b>Evento   : </b></td><td>".$data['titulo']."</td></tr>";
            $mensaje .= "<tr><td><b>Nombre   : </b></td><td>".$data['nombre']."</td></tr>";
            $mensaje .= "<tr><td><b>Apellidos: </b></td><td>".$data['apellidos']."</td></tr>";
            $mensaje .= "<tr><td><b>Email    : </b></td><td>".$data['email']."</td></tr>";
            $mensaje .= "<tr><td><b>Teléfono : </b></td><td>".$data['telefono']."</td></tr>";
            $mensaje .="</table>";

            $email->SetMessage($mensaje);

            if ($email->send()) {
                return view('respuestasCorreo/envioCorrecto',$datos);
            } else {
                return view('respuestasCorreo/envioErroneo',$datos);
            }
        }
    }
}
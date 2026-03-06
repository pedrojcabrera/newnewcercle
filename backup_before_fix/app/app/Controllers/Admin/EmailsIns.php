<?php

namespace App\Controllers\Admin;

use App\Models\EnEsperaModel;
use App\Models\InscritosModel;
use App\Models\InvitadosModel;
use App\Models\EventosModel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class EmailsIns extends BaseController
{

    public $model;
    public $eventosModel;

    public function __construct()
    {
        $this->model = new EnEsperaModel;
        $this->eventosModel = new EventosModel;
    }
    
    public function lista(){
        
        $emails = $this->model->emailsConEvento();

        $data = [
            'titulo' => 'Solicitudes de Inscripciones',
            'emails' => $emails
        ];
        return view('admin/emailsIns/lista',$data);
    }

    public function delete($id = null){
        if($id!=null) {
            $this->model->delete($id);
        }
        return $this->lista();
    }

    public function inscribe($id) {
        
        $esperandoM = new EnEsperaModel();
        
        $eEmailsIns = $esperandoM->find($id); /* solicitó inscripción por correo */
        
        if($eEmailsIns) {

            $registro = [
                'id_evento'     => $eEmailsIns->id_evento,
                'id_contacto'   => $eEmailsIns->id_contacto,                   'nombre'    => $eEmailsIns->nombre,
                'apellidos'     => $eEmailsIns->apellidos,
                'email'         => $eEmailsIns->email,
                'telefono'      => $eEmailsIns->telefono,
                'via'           => 'registro',
            ];

            $mInscritos = new InscritosModel();

            $mInscritos->insert($registro);

            $datos = [ 'inscrito'=> 1 ];
            $esperandoM->update($id, $datos);

            $mInvitados = new InvitadosModel();
            
            $mInvitados->where('id_contacto', $eEmailsIns->id_contacto)
                       ->where('id_evento', $eEmailsIns->id_evento)
                       ->set(['inscrito' => 1])
                       ->update();

            $id     = $registro['id_evento'];
            $evento = $this->eventosModel->find($id);

            $cabeceraCorreo = "recursos/imagenes/logo_Cercle_125.png";            
            $correoTexto  = "<div style='text-align: left;'>";
            $correoTexto .= "<p>Desde el Cercle d'Art de Foios le confirmamos que se ha procedido a su inscripción en el evento:</p>";
            $correoTexto .= "<p style='width: 100%; text-align: center; font-weight: bold;'>".strtoupper($evento->titulo)."</p>";
            
            if($evento->desde == $evento->hasta):
                $correoTexto .= "<p>que se celebrará durante el ".date('d/m/Y', strtotime($evento->desde)).".</p>";
            else:
                $correoTexto .= "<p>que se extenderá desde el ".date('d/m/Y', strtotime($evento->desde))." hasta el ".date('d/m/Y', strtotime($evento->hasta)).".</p>";
            endif;
            
            $correoTexto .= "<br><p>Un saludo del Cercle d'Art de Foios.</p>";
            $correoTexto .= "<br><p style='font-style: italic'>Si ud. desea cancelar esta inscripción, por favor, hagalo saber por correo electrónico a correo@cercledartfoios.com.</p>";
            $correoTexto .= "<p style='font-style: italic'>Si esta inscripción ha sido un error por nuestra parte, por favor no dude en comunicarlo por correo electrónico a correo@cercledartfoios.com.</p>";
            $correoTexto .= "</div>";
            $correoAsunto = "Inscripción en un evento del Cercle d'Art de Foios";
            $correoEmail = $registro['email'];
            
                        // dd($evento);
                        

            $email = \Config\Services::email();

            $email->setFrom('noreply@cercledartfoios.com',"Cercle d'Art Foios",'rechazados@cercledartfoios.com');

            $plantilla = file_get_contents('recursos/plantillasmail/cabeceraMail.php');
            // $plantilla = str_replace('{{cabeceraCorreo}}', $cabeceraCorreo, $plantilla);
            $plantilla .= $correoTexto;

            $email->clear();
            $email->setSubject($correoAsunto);
            $email->setTo($correoEmail,'info@cercledartfoios.com');

            $email->setMessage($plantilla);

            $res = $email->send();
            
        }
        return $this->lista();
    }
}
<?php

namespace App\Controllers;

use App\Models\EventosModel;
use App\Models\EnEsperaModel;
use App\Models\InvitadosModel;
use App\Models\ContactosModel;

class Eventos extends BaseController
{
    private $eventoModel;

    public function __construct()
    {
        $this->eventoModel = new EventosModel();
    }

    public function ultimos(): string
    {
        $eventos = $this->eventoModel->getUltimosEventos(3);

        $data = [
            'titulo'    => 'Últimos Eventos',
            'eventos'   => $eventos
        ];

        return view('eventos/ultimos', $data);
    }

    public function lista(): string
    {
        $eventos = $this->eventoModel->getListaEventos();

        $data = [
            'titulo'    => 'Histórico de Eventos',
            'eventos'   => $eventos
        ];

        return view('eventos/lista', $data);
    }

    public function show($id)
    {
        $evento = $this->eventoModel->getEventoConTipo($id);

        if (!$evento) {
            return redirect()->back();
        }

        $pdf = $this->eventoModel->getEventoPdf($id);
        $fotos = $this->eventoModel->getEventoFotos($id);
        $admiteInscripcion = $this->eventoModel->admiteInscripcion($evento);

        $data = [
            'titulo'       => 'Detalles del Evento',
            'evento'       => $evento,
            'pdf'          => $pdf,
            'inscripcion'  => $admiteInscripcion,
            'fotos'        => $fotos
        ];

        return view('eventos/show', $data);
    }

    public function inscribirse($id, $contacto_id = 0) {

        $evento = $this->eventoModel->find($id);

        $data = [
            'titulo'          => 'Inscripción',
            'evento_titulo'   => $evento->titulo,
            'evento_id'       => $id,
        ];

        if ($contacto_id) {
            $invitadoM = new InvitadosModel();
            $invitado = $invitadoM
                ->where('id_contacto', $contacto_id)
                ->where('id_evento', $id)
                ->first();

            if (!$invitado) {
                return view('respuestasCorreo/noHayInvitacion', $data);
            }

            if ($invitado->enespera) {
                return view('respuestasCorreo/registroRepetido', $data);
            }

            if ($invitado->inscrito) {
                return view('respuestasCorreo/yaRegistrado', $data);
            }

            $contactoModel = new ContactosModel();
            $contacto = $contactoModel->find($contacto_id);
            $data['contacto'] = $contacto;
            $data['invitado'] = $invitado;

            return view('eventos/inscribirseSinMenu', $data);
        }

        return view('eventos/inscribirse', $data);
    }

    public function inscripcion() {

        $data = $this->request->getPost();

        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');

        if (!uti_verifica_recaptcha_v3($recaptchaResponse, 'inscripcion_evento')) {
            $errores[] = 'Validación de seguridad fallida. Por favor, intenta nuevamente.';
            return $this->renderInscripcionConErrores($data, $errores);
        }

        $datos = [
            'id' => $data['id'],
            'titulo' => 'Resultado del Envío',
            'nuevo_titulo' => 'Detalle del Evento'
        ];

        $datosIns = [
            'id_evento' => $data['id'],
            'id_invitado' => $data['id_invitado'],
            'id_contacto' => $data['id_contacto'],
            'nombre' => $data['nombre'],
            'apellidos' => $data['apellidos'],
            'email' => $data['email'],
            'telefono' => $data['telefono'],
        ];

        $esperandoM = new EnEsperaModel();
        $result = $esperandoM->insert($datosIns);

        if ($result) {

            $inviData = ['enespera' => 1];

            $invitados = new InvitadosModel();
            $invitados->update($data['id_invitado'], $inviData);

            $email = \Config\Services::email();

            $email->setFrom('noreply@cercledartfoios.com', "Cercle d'Art Foios", 'rechazados@cercledartfoios.com');
            $email->setTo('correo@cercledartfoios.com');
            $email->setSubject('Solicitud de Inscripción a Evento');

            $mensaje = "<h2>Solicitud de Inscripción a Evento</h2>";
            $mensaje .= "<h5>desde la página Web</h5><hr>";
            $mensaje .= "<table>";
            $mensaje .= "<tr><td><b>Evento   : </b></td><td>" . $data['titulo'] . "</td></tr>";
            $mensaje .= "<tr><td><b>Nombre   : </b></td><td>" . $data['nombre'] . "</td></tr>";
            $mensaje .= "<tr><td><b>Apellidos: </b></td><td>" . $data['apellidos'] . "</td></tr>";
            $mensaje .= "<tr><td><b>Email    : </b></td><td>" . $data['email'] . "</td></tr>";
            $mensaje .= "<tr><td><b>Teléfono : </b></td><td>" . $data['telefono'] . "</td></tr>";
            $mensaje .= "</table>";

            $email->SetMessage($mensaje);

            if ($email->send()) {
                return view('respuestasCorreo/envioCorrecto', $datos);
            } else {
                return view('respuestasCorreo/envioErroneo', $datos);
            }
        }
    }

    private function renderInscripcionConErrores(array $data, array $errores)
    {
        $viewData = [
            'titulo' => 'Inscripción',
            'errores' => $errores,
            'evento_titulo' => $data['titulo'] ?? '',
            'evento_id' => $data['id'] ?? 0,
        ];

        if (!empty($data['id_contacto']) && $data['id_contacto'] !== '0') {
            $contacto = (object) [
                'id' => $data['id_contacto'],
                'nombre' => $data['nombre'] ?? '',
                'apellidos' => $data['apellidos'] ?? '',
                'email' => $data['email'] ?? '',
                'telefono' => $data['telefono'] ?? '',
            ];
            $viewData['contacto'] = $contacto;

            if (!empty($data['id_invitado']) && $data['id_invitado'] !== '0') {
                $invitado = (object) ['id' => $data['id_invitado']];
                $viewData['invitado'] = $invitado;
            }

            return view('eventos/inscribirseSinMenu', $viewData);
        }

        return view('eventos/inscribirse', $viewData);
    }
}

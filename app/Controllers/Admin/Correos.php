<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactosModel;
use App\Models\CorreosModel;
use App\Models\MailingsDetallesModel;
use App\Models\MailingsResumenModel;

class Correos extends BaseController
{
    public $model;

    public function __construct()
    {
        $this->model = new CorreosModel;
    }

    public function lista()
    {
        $correos = $this->model->orderBy('fecha', 'DESC')->findAll();
        $data = [
            'titulo'  => 'Correos predefinidos',
            'correos' => $correos,
        ];
        return view('admin/correos/lista', $data);
    }

    public function new()
    {
        $data = [
            'titulo' => 'Creación de Correos Pre-definidos',
        ];
        return view('admin/correos/nuevo', $data);
    }

    public function create()
    {
        $reglas = [
            'texto'  => 'required',
            'asunto' => 'required',
        ];

        if (!$this->validate($reglas)) {
            return redirect()->to(base_url('control/correos/nuevo'))->withInput();
        }

        $post = $this->request->getPost();

        $this->model->insert([
            'texto'  => $post['texto'],
            'asunto' => $post['asunto'],
        ]);

        return redirect()->to(base_url('control/correos'));
    }

    public function edit($id = null)
    {
        $correo = $this->model->asObject()->find($id);
        $data = [
            'titulo' => 'Edición de Correos Pre-definidos',
            'id'     => $id,
            'correo' => $correo,
        ];
        return view('admin/correos/editar', $data);
    }

    public function update($id = null)
    {
        $reglas = [
            'asunto' => 'required',
            'texto'  => 'required',
        ];

        if (!$this->validate($reglas)) {
            return redirect()->to(base_url('control/correos/editar/' . $id))->withInput();
        }

        $post = $this->request->getPost();

        $datos = [
            'id'     => $id,
            'asunto' => $post['asunto'],
            'texto'  => $post['texto'],
        ];

        $this->model->save($datos);

        return redirect()->to(base_url('control/correos'));
    }

    public function delete($id = null)
    {
        $this->model->delete($id);
        return redirect()->to(base_url('control/correos'));
    }

    public function prepmail($id = null)
    {
        $correo = $this->model->asObject()->find($id);
        $data = [
            'titulo' => 'Envío masivo de correo pre-definido',
            'id'     => $id,
            'correo' => $correo,
        ];
        if (isset($this->session->mensaje_error)) {
            $data['mensaje_error'] = $this->session->mensaje_error;
        }

        return view('admin/correos/cartear', $data);
    }

    public function sendmail($id = null)
    {
        $post = $this->request->getPost();

        if (empty($post)) {
            $mensaje_error = "Necesitas seleccionar al menos un grupo de destinatarios";
            return redirect()->to(base_url('control/correos/cartear/' . $id))->with('mensaje_error', $mensaje_error);
        }

        $mailingsResumenModel  = new MailingsResumenModel;
        $mailingsDetallesModel = new MailingsDetallesModel;

        $correo = $this->model->asObject()->find($id);

        $envios = [
            'id_correo' => $correo->id,
            'fechahora' => date('Y-m-d H:i:s'),
            'enviados'  => 0,
            'errores'   => 0,
            'socios'    => isset($post['socio']),
            'alumnos'   => isset($post['alumno']),
            'padres'    => isset($post['pdalumno']),
            'artistas'  => isset($post['pintor']),
            'talleres'  => isset($post['dtaller']),
            'amigos'    => isset($post['amigo']),
        ];

        $mailingsResumenModel->insert($envios);

        $enviosID = $mailingsResumenModel->insertID();

        $db = \Config\Database::connect();
        $builder = $db->table('contactos');

        $condiciones = [];
        if (isset($post['socio'])) {
            $condiciones[] = "socio = 1";
        }
        if (isset($post['alumno'])) {
            $condiciones[] = "alumno = 1";
        }
        if (isset($post['pdalumno'])) {
            $condiciones[] = "pdalumno = 1";
        }
        if (isset($post['pintor'])) {
            $condiciones[] = "pintor = 1";
        }
        if (isset($post['dtaller'])) {
            $condiciones[] = "dtaller = 1";
        }
        if (isset($post['amigo'])) {
            $condiciones[] = "amigo = 1";
        }

        $consulta = "mailing = 1 AND (" . implode(' OR ', $condiciones) . ")";

        $builder->where($consulta);
        $query = $builder->get();
        $contactos = $query->getResultObject();

        $correo = $this->model->asObject()->find($id);

        $email = \Config\Services::email();

        $email->setFrom('noreply@cercledartfoios.com', "Cercle d'Art Foios", 'rechazados@cercledartfoios.com');

        $errores = [];
        $correctos = [];

        $plantilla = file_get_contents('recursos/plantillasmail/cabeceraMail.php');
        $plantilla .= $correo->texto;
        $plantilla .= "<br>";
        $plantilla .= file_get_contents('recursos/plantillasmail/pieMail.php');

        foreach ($contactos as $contacto) {
            if (!filter_var($contacto->email, FILTER_VALIDATE_EMAIL)) {
                $errores[$contacto->id] = [
                    'nombre'   => $contacto->nombre,
                    'email'    => $contacto->email,
                    'telefono' => $contacto->telefono,
                ];
                continue;
            }

            $email->clear();
            $email->setSubject($correo->asunto);
            $email->setTo($contacto->email, 'info@cercledartfoios.com');

            $cuerpo = $plantilla;

            $cabeceraCorreo = "https://www.cercledartfoios.com/recursos/imagenes/logo_Cercle_125.png";
            $cuerpo = str_replace('{{cabeceraCorreo}}', $cabeceraCorreo, $cuerpo);

            $cuerpo = str_replace('{{id}}', $contacto->id, $cuerpo);
            $cuerpo = str_replace('{{nombre}}', trim($contacto->nombre), $cuerpo);
            $cuerpo = str_replace('{{email}}', $contacto->email, $cuerpo);
            $cuerpo = str_replace('{{telefono}}', $contacto->telefono, $cuerpo);
            $cuerpo = str_replace('{{direccion}}', $contacto->direccion, $cuerpo);
            $cuerpo = str_replace('{{codpostal}}', $contacto->codpostal, $cuerpo);
            $cuerpo = str_replace('{{poblacion}}', $contacto->poblacion, $cuerpo);
            $cuerpo = str_replace('{{provincia}}', $contacto->provincia, $cuerpo);

            $cuerpo = str_replace('{{baja_emails}}', base_url('bajasxpiecorreo/emails/' . $contacto->id), $cuerpo);
            $cuerpo = str_replace('{{baja_invitaciones}}', base_url('bajasxpiecorreo/invitaciones/' . $contacto->id), $cuerpo);
            $cuerpo = str_replace('{{baja_total}}', base_url('bajasxpiecorreo/total/' . $contacto->id), $cuerpo);

            $email->setMessage($cuerpo);

            $res = $email->send();

            if ($res) {
                $correctos[$contacto->id] = [
                    'nombre'   => $contacto->nombre,
                    'email'    => $contacto->email,
                    'telefono' => $contacto->telefono,
                ];
                $envio = [
                    'id_mailing'  => $enviosID,
                    'id_correo'   => $correo->id,
                    'id_contacto' => $contacto->id,
                    'error'       => 0,
                ];
            } else {
                $errores[$contacto->id] = [
                    'nombre'   => $contacto->nombre,
                    'email'    => $contacto->email,
                    'telefono' => $contacto->telefono,
                ];
                $envio = [
                    'id_mailing'  => $enviosID,
                    'id_correo'   => $correo->id,
                    'id_contacto' => $contacto->id,
                    'error'       => 1,
                ];
            }
            $mailingsDetallesModel->insert($envio);
        }

        $envios = [
            'enviados' => count($correctos),
            'errores'  => count($errores),
        ];

        $mailingsResumenModel->update($enviosID, $envios);

        $data = [
            'titulo'    => 'Resultado envío masivo',
            'asunto'    => $correo->asunto,
            'correctos' => $correctos,
            'errores'   => $errores,
        ];

        return view('admin/correos/carteado', $data);
    }

    public function listado($id = null)
    {
        $correosM   = new CorreosModel();
        $resumenesM = new MailingsResumenModel();
        $detallesM  = new MailingsDetallesModel();
        $contactosM = new ContactosModel();

        $correo    = $correosM->find($id);
        $resumenes = $resumenesM
            ->where('id_correo', $id)
            ->orderBy('fechahora', 'DESC')
            ->findAll();

        $destinatarios = [];
        $destinos      = [];
        $contactos     = [];
        $emails        = [];
        $detalles      = [];

        foreach ($resumenes as $resumen) {
            $id_mailing = $resumen->id;

            if ($resumen->socios) {
                $destinatarios[$resumen->id][] = 'Socios';
            }

            if ($resumen->alumnos) {
                $destinatarios[$resumen->id][] = 'Alumnos';
            }

            if ($resumen->padres) {
                $destinatarios[$resumen->id][] = 'Padres';
            }

            if ($resumen->artistas) {
                $destinatarios[$resumen->id][] = 'Artistas';
            }

            if ($resumen->talleres) {
                $destinatarios[$resumen->id][] = 'Talleres';
            }

            if ($resumen->amigos) {
                $destinatarios[$resumen->id][] = 'Amigos';
            }

            $detalles[$resumen->id] = $detallesM
                ->where('id_mailing', $resumen->id)
                ->findAll();

            $destinos[$resumen->id] = implode("\n", $destinatarios[$resumen->id]);

            foreach ($detalles[$resumen->id] as $detalle) {
                $result                  = $contactosM->find($detalle->id_contacto);
                $contactos[$detalle->id] = trim($result->nombre . ' ' . $result->apellidos);
                $emails[$detalle->id]    = $result->email;
            }
        }

        $data = [
            'titulo'    => 'Resumen mailings masivos',
            'correo'    => $correo,
            'resumenes' => $resumenes,
            'detalles'  => $detalles,
            'destinos'  => $destinos,
            'nombres'   => $contactos,
            'emails'    => $emails,
        ];

        return view('admin/correos/listado', $data);
    }
}
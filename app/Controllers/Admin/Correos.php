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

    private function construirPayloadCorreo(array $post, ?int $id = null): array
    {
        $payload = [
            'asunto' => $post['asunto'] ?? '',
            'texto'  => $post['texto'] ?? '',
        ];

        if ($id !== null) {
            $payload['id'] = $id;
        }

        return $payload;
    }

    private function sanitizeCorreoHtml(?string $html): string
    {
        $html = trim((string) $html);
        if ($html == '') {
            return '';
        }

        // Mantiene formato HTML enriquecido y elimina elementos de riesgo.
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><a><span><div><h1><h2><h3><h4><h5><h6><blockquote><hr><img><table><thead><tbody><tr><th><td>';
        $safe = strip_tags($html, $allowedTags);

        // Elimina atributos inline peligrosos como onclick, onerror, etc.
        $safe = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $safe) ?? $safe;

        // Bloquea protocolos javascript: en href/src.
        $safe = preg_replace('/\s(href|src)\s*=\s*("|\')\s*javascript:[^"\']*("|\')/i', ' $1="#"', $safe) ?? $safe;

        return $safe;
    }

    private function getUnsubscribeSecret(): ?string
    {
        $secret = trim((string) env('unsubscribeTokenSecret'));
        if ($secret !== '') {
            return $secret;
        }

        $secret = trim((string) env('encryption.key'));
        if ($secret !== '') {
            return $secret;
        }

        return null;
    }

    private function buildUnsubscribeToken(int $contactoId, string $scope): ?string
    {
        $secret = $this->getUnsubscribeSecret();
        if ($secret === null) {
            return null;
        }

        return hash_hmac('sha256', $scope . '|' . $contactoId, $secret);
    }

    private function buildUnsubscribeUrl(int $contactoId, string $scope): string
    {
        $token = $this->buildUnsubscribeToken($contactoId, $scope);
        if ($token === null) {
            log_message('critical', 'No se pudo generar token de baja: falta unsubscribeTokenSecret y encryption.key.');
            return base_url('contactar');
        }

        return base_url('bajasxpiecorreo/' . $scope . '/' . $contactoId . '/' . $token);
    }

    public function __construct()
    {
        $this->model = new CorreosModel;
    }

    public function lista()
    {
        $correos = $this->model->orderBy('fecha', 'DESC')->findAll();

        foreach ($correos as &$correo) {
            $correo->texto_sanitizado = $this->sanitizeCorreoHtml($correo->texto ?? '');
        }
        unset($correo);

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
        $this->model->insert($this->construirPayloadCorreo($post));

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

        $datos = $this->construirPayloadCorreo($post, (int) $id);

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

        $grupos = [];
        foreach (['socio', 'alumno', 'pdalumno', 'pintor', 'dtaller', 'amigo'] as $grupo) {
            if (isset($post[$grupo])) {
                $grupos[] = $grupo;
            }
        }

        if ($grupos === []) {
            $mensaje_error = "Necesitas seleccionar al menos un grupo de destinatarios";
            return redirect()->to(base_url('control/correos/cartear/' . $id))->with('mensaje_error', $mensaje_error);
        }

        $builder->where('mailing', 1);
        $builder->groupStart();
        foreach ($grupos as $index => $grupo) {
            if ($index === 0) {
                $builder->where($grupo, 1);
            } else {
                $builder->orWhere($grupo, 1);
            }
        }
        $builder->groupEnd();

        $query = $builder->get();
        $contactos = $query->getResultObject();

        $correo = $this->model->asObject()->find($id);

        $email = \Config\Services::email();

        $email->setFrom('noreply@cercledartfoios.com', "Cercle d'Art Foios", 'rechazados@cercledartfoios.com');

        $errores = [];
        $correctos = [];

        $correoHtmlSeguro = $this->sanitizeCorreoHtml($correo->texto ?? '');

        $plantilla = file_get_contents('recursos/plantillasmail/cabeceraMail.php');
        $plantilla .= $correoHtmlSeguro;
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

            $cuerpo = str_replace('{{baja_emails}}', $this->buildUnsubscribeUrl((int) $contacto->id, 'emails'), $cuerpo);
            $cuerpo = str_replace('{{baja_invitaciones}}', $this->buildUnsubscribeUrl((int) $contacto->id, 'invitaciones'), $cuerpo);
            $cuerpo = str_replace('{{baja_total}}', $this->buildUnsubscribeUrl((int) $contacto->id, 'total'), $cuerpo);

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

    public function sendmailLote($id = null)
    {
        $post     = $this->request->getPost();
        $offset   = (int) ($post['offset']   ?? 0);
        $enviosID = (int) ($post['enviosID'] ?? 0);
        $total    = (int) ($post['total']    ?? 0);
        $limit    = 10;

        $grupos = [];
        foreach (['socio', 'alumno', 'pdalumno', 'pintor', 'dtaller', 'amigo'] as $g) {
            if (!empty($post[$g])) {
                $grupos[] = $g;
            }
        }

        if (empty($grupos)) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Sin grupos']);
        }

        $correo = $this->model->asObject()->find($id);
        if (!$correo) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Correo no encontrado']);
        }

        $db                    = \Config\Database::connect();
        $mailingsResumenModel  = new MailingsResumenModel;
        $mailingsDetallesModel = new MailingsDetallesModel;

        $buildQuery = function () use ($db, $grupos) {
            $b = $db->table('contactos')->where('mailing', 1);
            $b->groupStart();
            foreach ($grupos as $i => $g) {
                $i === 0 ? $b->where($g, 1) : $b->orWhere($g, 1);
            }
            $b->groupEnd();
            return $b;
        };

        if ($offset === 0) {
            $total = (int) $buildQuery()->countAllResults();
            $mailingsResumenModel->insert([
                'id_correo' => $correo->id,
                'fechahora' => date('Y-m-d H:i:s'),
                'enviados'  => 0,
                'errores'   => 0,
                'socios'    => in_array('socio',    $grupos),
                'alumnos'   => in_array('alumno',   $grupos),
                'padres'    => in_array('pdalumno', $grupos),
                'artistas'  => in_array('pintor',   $grupos),
                'talleres'  => in_array('dtaller',  $grupos),
                'amigos'    => in_array('amigo',    $grupos),
            ]);
            $enviosID = (int) $mailingsResumenModel->insertID();
        }

        $contactos = $buildQuery()->limit($limit, $offset)->get()->getResultObject();

        $correoHtmlSeguro = $this->sanitizeCorreoHtml($correo->texto ?? '');
        $plantilla  = (string) @file_get_contents('recursos/plantillasmail/cabeceraMail.php');
        $plantilla .= $correoHtmlSeguro;
        $plantilla .= '<br>';
        $plantilla .= (string) @file_get_contents('recursos/plantillasmail/pieMail.php');

        $emailSvc = \Config\Services::email();
        $emailSvc->setFrom('noreply@cercledartfoios.com', "Cercle d'Art Foios", 'rechazados@cercledartfoios.com');

        $loteEnviados = 0;
        $loteErrores  = 0;

        foreach ($contactos as $contacto) {
            $hayError = false;

            if (!filter_var($contacto->email, FILTER_VALIDATE_EMAIL)) {
                $hayError = true;
            } else {
                $cuerpo = $plantilla;
                $cuerpo = str_replace('{{cabeceraCorreo}}', 'https://www.cercledartfoios.com/recursos/imagenes/logo_Cercle_125.png', $cuerpo);
                $cuerpo = str_replace('{{id}}',        $contacto->id,           $cuerpo);
                $cuerpo = str_replace('{{nombre}}',    trim($contacto->nombre), $cuerpo);
                $cuerpo = str_replace('{{email}}',     $contacto->email,        $cuerpo);
                $cuerpo = str_replace('{{telefono}}',  $contacto->telefono,     $cuerpo);
                $cuerpo = str_replace('{{direccion}}', $contacto->direccion,    $cuerpo);
                $cuerpo = str_replace('{{codpostal}}', $contacto->codpostal,    $cuerpo);
                $cuerpo = str_replace('{{poblacion}}', $contacto->poblacion,    $cuerpo);
                $cuerpo = str_replace('{{provincia}}', $contacto->provincia,    $cuerpo);
                $cuerpo = str_replace('{{baja_emails}}',       $this->buildUnsubscribeUrl((int) $contacto->id, 'emails'),       $cuerpo);
                $cuerpo = str_replace('{{baja_invitaciones}}', $this->buildUnsubscribeUrl((int) $contacto->id, 'invitaciones'), $cuerpo);
                $cuerpo = str_replace('{{baja_total}}',        $this->buildUnsubscribeUrl((int) $contacto->id, 'total'),        $cuerpo);

                $emailSvc->clear();
                $emailSvc->setSubject($correo->asunto);
                $emailSvc->setTo($contacto->email, 'info@cercledartfoios.com');
                $emailSvc->setMessage($cuerpo);

                $hayError = !$emailSvc->send();
            }

            if ($hayError) {
                $loteErrores++;
            } else {
                $loteEnviados++;
            }

            $mailingsDetallesModel->insert([
                'id_mailing'  => $enviosID,
                'id_correo'   => $correo->id,
                'id_contacto' => $contacto->id,
                'error'       => $hayError ? 1 : 0,
            ]);
        }

        $resumen = $mailingsResumenModel->find($enviosID);
        $mailingsResumenModel->update($enviosID, [
            'enviados' => (int) ($resumen->enviados ?? 0) + $loteEnviados,
            'errores'  => (int) ($resumen->errores  ?? 0) + $loteErrores,
        ]);

        $procesados = $offset + count($contactos);
        $done       = count($contactos) < $limit || $procesados >= $total;

        return $this->response->setJSON([
            'ok'         => true,
            'enviosID'   => $enviosID,
            'total'      => $total,
            'procesados' => $procesados,
            'done'       => $done,
            'csrf_name'  => csrf_token(),
            'csrf_hash'  => csrf_hash(),
        ]);
    }

    public function resultado($enviosID = null)
    {
        $mailingsResumenModel  = new MailingsResumenModel;
        $mailingsDetallesModel = new MailingsDetallesModel;
        $contactosM            = new ContactosModel;

        $resumen  = $mailingsResumenModel->find($enviosID);
        $correo   = $this->model->asObject()->find($resumen->id_correo ?? 0);
        $detalles = $mailingsDetallesModel->where('id_mailing', $enviosID)->findAll();

        $correctos = [];
        $errores   = [];
        foreach ($detalles as $d) {
            $c = $contactosM->find($d->id_contacto);
            if (!$c) {
                continue;
            }
            $arr = [
                'nombre'   => trim($c->nombre . ' ' . $c->apellidos),
                'email'    => $c->email,
                'telefono' => $c->telefono,
            ];
            if ($d->error) {
                $errores[$d->id_contacto] = $arr;
            } else {
                $correctos[$d->id_contacto] = $arr;
            }
        }

        return view('admin/correos/carteado', [
            'titulo'    => 'Resultado envío masivo',
            'asunto'    => $correo->asunto ?? '',
            'correctos' => $correctos,
            'errores'   => $errores,
        ]);
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

<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactosModel;

class Autobajas extends BaseController
{
    public $model;

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

    public function __construct()
    {
        $this->model = new ContactosModel;
    }

    private function isValidToken(int $id, string $scope, ?string $token): bool
    {
        if ($token === null || $token === '') {
            return false;
        }

        $secret = $this->getUnsubscribeSecret();
        if ($secret === null) {
            log_message('critical', 'No se puede validar token de baja: falta unsubscribeTokenSecret y encryption.key.');
            return false;
        }

        $expected = hash_hmac('sha256', $scope . '|' . $id, $secret);

        return hash_equals($expected, $token);
    }

    public function emails($id = null, $token = null)
    {
        if (!$this->isValidToken((int) $id, 'emails', $token)) {
            return view('admin/autobajas/resultado_gestion', [
                'titulo' => 'Solicitud de baja de emails',
                'contacto' => null,
                'mensaje' => "<p>La solicitud no es válida o ha caducado. Si desea gestionar la baja, contacte con la secretaría del Cercle d'Art Foios.</p>",
            ]);
        }

        $contacto = $this->model->find($id);
        $realizarCambios = false;

        $mensaje = "<p>Hemos recibido su solicitud para darse de baja de nuestra lista de emails generales, en consecuencia:</p>";

        if (!$contacto) {
            $mensaje .= "<p>La solicitud no ha podido ser atendida, si quiere realizar este proceso, le rogamos se ponga en contacto con la secretaría del Cercle d'Art Foios.</p><p>Muchas gracias por su confianza.</p>";
        } else {
            if ($contacto->mailing == 0) {
                $mensaje .= "<p>Según su estado, ya no se encontraba suscrito a nuestros emails genéricos.</p><p>Si no está conforme o requiere alguna aclaración, le rogamos se ponga en contacto con la secretaría del Cercle d'Art Foios.</p><p>Muchas gracias por su confianza.</p>";
            } else {
                $realizarCambios = true;
                $mensaje .= "<p>Su solicitud ha sido procesada y ya no recibirá más emails genéricos por nuestra parte.</p><p>Muchas gracias por su confianza.</p>";
            }
        }

        $mensaje .= "<br><br><br><p style='text-align: right;'>Atte. Secretaría del Cercle d'Art de Foios</p>";

        $datos = [
            'id'      => $id,
            'mailing' => 0,
        ];

        if ($realizarCambios) {
            $this->model->save($datos);
        }

        $data = [
            'titulo'   => 'Solicitud de baja de emails',
            'contacto' => $contacto,
            'mensaje'  => $mensaje,
        ];

        return view('admin/autobajas/resultado_gestion', $data);
    }

    public function invitaciones($id = null, $token = null)
    {
        if (!$this->isValidToken((int) $id, 'invitaciones', $token)) {
            return view('admin/autobajas/resultado_gestion', [
                'titulo' => 'Solicitud de baja de emails',
                'contacto' => null,
                'mensaje' => "<p>La solicitud no es válida o ha caducado. Si desea gestionar la baja, contacte con la secretaría del Cercle d'Art Foios.</p>",
            ]);
        }

        $contacto = $this->model->find($id);
        $realizarCambios = false;

        $mensaje = "<p>Hemos recibido su solicitud para darse de baja de nuestra lista para recibir invitaciones a nuestros eventos, en consecuencia:</p>";

        if (!$contacto) {
            $mensaje .= "<p>La solicitud no ha podido ser atendida, si quiere realizar este proceso, le rogamos se ponga en contacto con la secretaría del Cercle d'Art Foios.</p><p>Muchas gracias por su confianza.</p>";
        } else {
            if ($contacto->invitaciones == 0) {
                $mensaje .= "<p>Según su estado, ya no se encontraba suscrito a nuestra lista para recibir invitaciones a nuestros Eventos.</p><p>Si no está conforme o requiere alguna aclaración, le rogamos se ponga en contacto con la secretaría del Cercle d'Art Foios.</p><p>Muchas gracias por su confianza.</p>";
            } else {
                $realizarCambios = true;
                $mensaje .= "<p>Su solicitud ha sido procesada y ya no recibirá más invitaciones por email a nuestros eventos por nuestra parte.</p><p>Muchas gracias por su confianza.</p>";
            }
        }

        $mensaje .= "<br><br><br><p style='text-align: right;'>Atte. Secretaría del Cercle d'Art de Foios</p>";

        $datos = [
            'id'           => $id,
            'invitaciones' => 0,
        ];

        if ($realizarCambios) {
            $this->model->save($datos);
        }

        $data = [
            'titulo'   => 'Solicitud de baja de emails',
            'contacto' => $contacto,
            'mensaje'  => $mensaje,
        ];

        return view('admin/autobajas/resultado_gestion', $data);
    }

    public function bajaTotal($id = null, $token = null)
    {
        if (!$this->isValidToken((int) $id, 'total', $token)) {
            return view('admin/autobajas/resultado_gestion', [
                'titulo' => 'Solicitud de baja de emails',
                'contacto' => null,
                'mensaje' => "<p>La solicitud no es válida o ha caducado. Si desea gestionar la baja, contacte con la secretaría del Cercle d'Art Foios.</p>",
            ]);
        }

        $contacto = $this->model->find($id);
        $realizarCambios = false;

        $mensaje = "<p>Hemos recibido su solicitud para darse de baja de todas nuestras listas de emails, en consecuencia:</p>";

        if (!$contacto) {
            $mensaje .= "<p>La solicitud no ha podido ser atendida, si quiere realizar este proceso, le rogamos se ponga en contacto con la secretaría del Cercle d'Art Foios.</p><p>Muchas gracias por su confianza.</p>";
        } else {
            if ($contacto->mailing == 0 && $contacto->invitaciones == 0) {
                $mensaje .= "<p>Según su estado, ya no se encontraba suscrito a nuestras listas.</p><p>Si no está conforme o requiere alguna aclaración, le rogamos se ponga en contacto con la secretaría del Cercle d'Art Foios.</p><p>Muchas gracias por su confianza.</p>";
            } else {
                $realizarCambios = true;
                $mensaje .= "<p>Su solicitud ha sido procesada y ya no recibirá más invitaciones por email a nuestros eventos por nuestra parte.</p><p>Muchas gracias por su confianza.</p>";
            }
        }

        $mensaje .= "<br><br><br><p style='text-align: right;'>Atte. Secretaría del Cercle d'Art de Foios</p>";

        $datos = [
            'id'           => $id,
            'invitaciones' => 0,
            'mailing'      => 0,
        ];

        if ($realizarCambios) {
            $this->model->save($datos);
        }

        $data = [
            'titulo'   => 'Solicitud de baja de emails',
            'contacto' => $contacto,
            'mensaje'  => $mensaje,
        ];

        return view('admin/autobajas/resultado_gestion', $data);
    }
}

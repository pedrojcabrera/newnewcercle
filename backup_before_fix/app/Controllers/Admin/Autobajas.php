<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactosModel;

class Autobajas extends BaseController
{
    public $model;

    public function __construct()
    {
        $this->model = new ContactosModel;
    }

    public function emails($id = null)
    {
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

    public function invitaciones($id = null)
    {
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

    public function bajaTotal($id = null)
    {
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
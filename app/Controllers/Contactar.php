<?php

namespace App\Controllers;

class Contactar extends BaseController
{

    private $nombre;
    private $email;
    private $telefono;
    private $mensaje;

    public function index()
    {
        $data = [
            'titulo'    => 'Formulario de contacto'
        ];
        return view('contactar/contactar',$data);
    }

    public function submit()
    {
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');

        if (!uti_verifica_recaptcha_v3($recaptchaResponse, 'submit')) {
            $errores[] = "Validación de seguridad fallida. Por favor, intenta nuevamente.";
            return $this->renderContactarConErrores($errores);
        }

        $this->nombre = $this->request->getVar('nombre');
        $this->email  = $this->request->getVar('email');
        $this->telefono  = $this->request->getVar('telefono');
        $this->mensaje  = $this->request->getVar('mensaje');

        $data = [
            'nombre'   => $this->nombre,
            'email'    => $this->email,
            'telefono' => $this->telefono,
            'mensaje'  => $this->mensaje
        ];

        $email = \Config\Services::email();

        $email->setFrom('noreply@cercledartfoios.com',$this->nombre,'rechazados@cercledartfoios.com');

        $email->clear();

        $email->setTo('correo@cercledartfoios.com, info@cercledartfoios.com');

        $email->setSubject('Correo de Contactar de la Web');


        $mensaje = "<h4 style='padding: .5rem 1rem;'>Datos del formulario Contactar de la Web</h4>";
        $mensaje.= "<div style='border: thin solid silver; padding: .5rem 1rem; box-shadow: 2px 2px 3px silver;'><b>ATENCIÓN:</b><br>Este correo se ha creado desde la cuenta 'no-reply' por lo que no se debe responder a él.<br>Si se quiere contestar al remitente, realice un correo nuevo usando como destinatario la dirección que figura en el apartado email.</div>";
        $mensaje.= "<div style='border: thin solid silver; padding: .5rem 1rem; box-shadow: 2px 2px 3px silver;'><b>Recibido por:</b><br>correo@cercledartfoios.com y<br>info@cercledartfoios.com</div>";
        $mensaje.= "<div style='border: thin solid silver; padding: .5rem 1rem; box-shadow: 2px 2px 3px silver;'><b>eMail:</b><br>".$this->email."</div>";
        $mensaje.= "<div style='border: thin solid silver; padding: .5rem 1rem; box-shadow: 2px 2px 3px silver;'><b>Nombre:</b><br>".$this->nombre."</div>";
        $mensaje.= "<div style='border: thin solid silver; padding: .5rem 1rem; box-shadow: 2px 2px 3px silver;'><b>Teléfono:</b><br>".$this->telefono."</div>";
        $mensaje.= "<div style='border: thin solid silver; padding: .5rem 1rem; box-shadow: 2px 2px 3px silver;'><b>Mensaje:</b><br>".$this->mensaje."</div>";

        $email->setMessage($mensaje);

        $data = [
            'titulo'    => 'Formulario de contacto'
        ];

        if ($email->send())
        {
            $cabeceras[] = 'Correo enviado correctamente';
            $data = [
                'titulo'    => 'Formulario de contacto',
                'cabeceras' => $cabeceras,
            ];
        }
        else
        {
            $errores[] = 'Hubo un error en el envío de correo'; //$email->printDebugger(['headers']);
            return $this->renderContactarConErrores($errores);

        }

        return view('contactar/contactar',$data);

    }

    private function renderContactarConErrores(array $errores)
    {
        $data = [
            'titulo' => 'Formulario de contacto',
            'errores' => $errores,
        ];

        return view('contactar/contactar', $data);
    }
}

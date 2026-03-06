<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactosModel;
use App\Models\EventosModel;
use App\Models\EnEsperaModel;
use App\Models\InscritosModel;
use App\Models\InvitadosModel;
use App\Models\TiposEventosModel;
use Kint\Value\FunctionValue;

class Eventos extends BaseController
{
	public $model;
	public $db;
	public $sql;
	public $tipos;
	public $contactos;
	public $invitados;
	public $inscritos;
	public $enEspera;
	public $hoy;
	public $ahora;

	public function __construct()
	{
		$this->model     = new EventosModel;
		$this->tipos     = new TiposEventosModel;
		$this->contactos = new ContactosModel;
		$this->invitados = new InvitadosModel;
		$this->inscritos = new InscritosModel;
		$this->enEspera  = new EnEsperaModel;

		$this->db  = \Config\Database::connect();
		$this->sql = $this->db->table('neventos AS eventos');

		$this->hoy   = date('Y-m-d');
		$this->ahora = date('Y-m-d H:i');
	}

	public function sendmail($id = null)
	{
		// Guardar valores originales
		$original_max_execution_time = ini_get('max_execution_time');
		$original_max_input_time = ini_get('max_input_time');
		$original_memory_limit = ini_get('memory_limit');

		// Modificar valores
		ini_set('max_execution_time', 600);
		ini_set('max_input_time', 600);
		ini_set('memory_limit', '512M');

		$evento = $this->model->find($id);

		$email = \Config\Services::email();

		$email->setFrom('noreply@cercledartfoios.com', "Cercle d'Art Foios", 'rechazados@cercledartfoios.com');

		$errores   = [];
		$correctos = [];
		$repetidos = [];

		$plantilla = file_get_contents('recursos/plantillasmail/cabeceraMail.php');
		$plantilla .= $evento->texto_carta;
		$plantilla .= "<br><p></p>Puede inscribirse clicando <a href='" . base_url('inscribirse/'.$id.'/idcontacto') . "' class='boton_email_inscribirse'>aquí</a></><br>";
		$plantilla .= file_get_contents('recursos/plantillasmail/pieMail.php');

		$condiciones = [];
		if ($evento->socio) {
			$condiciones[] = "socio = 1"; 
		} 
		if ($evento->alumno) {
			$condiciones[] = "alumno = 1"; 
		} 
		if ($evento->pdalumno) {
			$condiciones[] = "pdalumno = 1"; 
		} 
		if ($evento->pintor) {
			$condiciones[] = "pintor = 1"; 
		} 
		if ($evento->dtaller) {
			$condiciones[] = "dtaller = 1"; 
		} 
		if ($evento->amigo) {
			$condiciones[] = "amigo = 1"; 
		} 

		$consulta = "invitaciones = 1 AND (" . implode(' OR ', $condiciones) . ")";

		$db = \Config\Database::connect();

		$sql = "SELECT * FROM contactos
				WHERE $consulta";

		$query     = $db->query($sql);
		$contactos = $query->getResultObject();

		foreach ($contactos as $contacto) {
			if (! filter_var($contacto->email, FILTER_VALIDATE_EMAIL)) {
				$errores[$contacto->id] = [
					'nombre'   => $contacto->nombre,
					'email'    => $contacto->email,
					'telefono' => $contacto->telefono,
				];
				continue;
			}

			$invi = $this->invitados
						->where('id_evento', $id)
						->where('id_contacto', $contacto->id)->countAllResults();

			if ($invi) {
				$repetidos[$contacto->id] = [
					'nombre'   => $contacto->nombre,
					'email'    => $contacto->email,
					'telefono' => $contacto->telefono,
				];
				continue;
			}

			$email->clear();
			$email->setSubject('Invitación a ' . $evento->titulo);
			$email->setTo($contacto->email, 'info@cercledartfoios.com');

			$cuerpo = $plantilla;
			$cabeceraCorreo = "https://cercledartfoios.com/recursos/imagenes/anagramaColor.png";
			//$cabeceraCorreo = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/public/recursos/imagenes/nofoto.jpg';
			$cuerpo = str_replace('{{cabeceraCorreo}}', $cabeceraCorreo, $cuerpo);

			$cuerpo = str_replace('{{id}}', $contacto->id, $cuerpo);
			$cuerpo = str_replace('idcontacto', $contacto->id, $cuerpo);

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

			$pdfPath = null;

			if (file_exists(FCPATH . 'pdfEventos/pdf_' . $id . '.pdf')) {
				$pdfPath = FCPATH . 'pdfEventos/pdf_' . $id . '.pdf';
			}

			$email->attach($pdfPath);

			$res = $email->send();

			if ($res) {
				$correctos[$contacto->id] = [
					'nombre'   => $contacto->nombre,
					'email'    => $contacto->email,
					'telefono' => $contacto->telefono,
				];

				$datosInvitado = [
					'id_evento'   => $evento->id,
					'id_contacto' => $contacto->id,
					'nombre'      => $contacto->nombre,
					'apellidos'   => $contacto->apellidos,
					'email'       => $contacto->email,
					'dni'         => $contacto->dni,
					'telefono'    => $contacto->telefono,
					'direccion'   => $contacto->direccion,
					'codpostal'   => $contacto->codpostal,
					'poblacion'   => $contacto->poblacion,
					'provincia'   => $contacto->provincia,
					'fecha'       => date('Y-m-d H:i:s'),
					'inscrito'    => 0,
				];

				$this->invitados->insert($datosInvitado);

			} else {
				$errores[$contacto->id] = [
					'nombre'   => $contacto->nombre,
					'email'    => $contacto->email,
					'telefono' => $contacto->telefono,
				];
			}
		}

		// Restaurar valores originales
		ini_set('max_execution_time', $original_max_execution_time);
		ini_set('max_input_time', $original_max_input_time);
		ini_set('memory_limit', $original_memory_limit);

		$data = [
			'titulo'    => 'Resultado envío masivo',
			'asunto'    => '<small>Invitaciónes a</small><br>' . $evento->titulo,
			'correctos' => $correctos,
			'errores'   => $errores,
			'repetidos' => $repetidos,
		];

		return view('admin/eventos/carteado', $data);
	}
}
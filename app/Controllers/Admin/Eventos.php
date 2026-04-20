<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactosModel;
use App\Models\EnEsperaModel;
use App\Models\EventosModel;
use App\Models\InscritosModel;
use App\Models\InvitadosModel;
use App\Models\TiposEventosModel;

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

  private function validarRangoFechas(?string $desde, ?string $hasta, string $errorKey, string $mensaje): bool
  {
    if (empty($desde) || empty($hasta)) {
      return true;
    }

    $desdeTs = strtotime($desde);
    $hastaTs = strtotime($hasta);

    if ($desdeTs === false || $hastaTs === false) {
      return true;
    }

    if ($hastaTs < $desdeTs) {
      session()->set([$errorKey => $mensaje]);
      return false;
    }

    return true;
  }

  private function construirPayloadEvento(
    array $post,
    ?int $id = null,
    bool $incluirInscripcionInvitacion = false,
    bool $incluirEventoCerrado = false
  ): array {
    $payload = [
      'titulo'         => trim((string) ($post['titulo'] ?? '')),
      'eventotipo'     => $post['eventotipo'] ?? '',
      'desde'          => $post['desde'] ?? null,
      'hasta'          => $post['hasta'] ?? null,
      'texto'          => trim((string) ($post['texto'] ?? '')),
      'texto_carta'    => trim((string) ($post['texto_carta'] ?? '')),
      'visible'        => isset($post['visible']) ? 1 : 0,
      'socio'          => isset($post['socio']) ? 1 : 0,
      'alumno'         => isset($post['alumno']) ? 1 : 0,
      'pdalumno'       => isset($post['pdalumno']) ? 1 : 0,
      'pintor'         => isset($post['pintor']) ? 1 : 0,
      'dtaller'        => isset($post['dtaller']) ? 1 : 0,
      'amigo'          => isset($post['amigo']) ? 1 : 0,
      'inscripcion'    => isset($post['inscripcion']) ? 1 : 0,
      'aforo_completo' => isset($post['aforo_completo']) ? 1 : 0,
    ];

    if ($id !== null) {
      $payload['id'] = $id;
    }

    if ($incluirInscripcionInvitacion) {
      $payload['inscripcion_invitacion'] = isset($post['inscripcion_invitacion']) ? 1 : 0;
    }

    if ($incluirEventoCerrado) {
      $payload['evento_cerrado'] = isset($post['evento_cerrado']) ? 1 : 0;
    }

    return $payload;
  }

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

  public function lista()
  {
    $perPage = 25;
    $page = $this->request->getVar('page') ?? 1;
    $offset = ($page - 1) * $perPage;

    // Contar total usando método dedicado
    $total = $this->model->countAllResults();
    $totalPages = ceil($total / $perPage);

    // Obtener eventos paginados
    $this->sql->select('eventos.*, eventos.desde, eventos.hasta, tiposeventos.eventonombre AS grupo');
    $this->sql->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo');
    $this->sql->orderby('eventos.id', 'DESC');
    $this->sql->limit($perPage, $offset);

    $query = $this->sql->get();
    $eventos = $query->getResult();

    $hoy = date('Y-m-d');

    $data = [
      'titulo'  => 'Eventos',
      'eventos' => $eventos,
      'hoy'     => $hoy,
      'page' => $page,
      'totalPages' => $totalPages,
      'total' => $total,
    ];
    return view('admin/eventos/lista', $data);
  }

  public function new ()
  {
    $lista = $this->tipos->asObject()->OrderBy('eventonombre', 'ASC')->findAll();
    $data  = [
      'lista'  => $lista,
      'titulo' => 'Creación de Eventos',
    ];
    return view('admin/eventos/nuevo', $data);
  }

  public function create()
  {

    $reglas = [
      'titulo'            => 'required|trim',
      'eventotipo'        => 'required',
      'desde'             => 'required|valid_date',
      'hasta'             => 'required|valid_date',
      'desde_inscripcion' => 'permit_empty|valid_date',
      'hasta_inscripcion' => 'permit_empty|valid_date',
      'cartel'            => 'permit_empty|max_size[cartel,6144]|ext_in[cartel,jpg,jpeg]|is_image[cartel]',
      'pdf_adjunto'       => 'permit_empty|max_size[pdf_adjunto,10240]|ext_in[pdf_adjunto,pdf]|mime_in[pdf_adjunto,application/pdf]',
    ];

    if (! $this->validarRangoFechas(
      $this->request->getPost('desde'),
      $this->request->getPost('hasta'),
      'error_hasta',
      'La fecha de finalización no puede ser anterior a la de Inicio de este evento'
    )) {
      $recarga = true;
    }

    $post = $this->request->getPost();

    $inscripcion            = isset($post['inscripcion']) ? 1 : 0;
    $inscripcion_invitacion = isset($post['inscripcion_invitacion']) ? 1 : 0;

    if ($inscripcion) {
      $reglas['desde_inscripcion'] = 'required|valid_date';
      $reglas['hasta_inscripcion'] = 'required|valid_date';

      if (! $this->validarRangoFechas(
        $this->request->getPost('desde_inscripcion'),
        $this->request->getPost('hasta_inscripcion'),
        'error_hasta_inscripcion',
        'La fecha final de inscripción no puede ser anterior a la de Inicio'
      )) {
        $recarga = true;
      }
    }

    $data = $this->construirPayloadEvento($post);

    if ($inscripcion) {
      if (! empty($post['desde_inscripcion'])) {
        $data['desde_inscripcion'] = $post['desde_inscripcion'];
      }
      if (! empty($post['hasta_inscripcion'])) {
        $data['hasta_inscripcion'] = $post['hasta_inscripcion'];
      }
    }

    if (! $this->validate($reglas)) {
      $reglas_errores = $this->validator->getErrors();
      $data           = [
        'titulo'         => 'Creación de Eventos',
        'reglas_errores' => $reglas_errores,
      ];
      $recarga = true;
    }

    if (isset($recarga)) {
      unset($recarga);
      return redirect()->back()->withInput();
    }

    $this->model->save($data);

    $imagen = $this->request->getFile('cartel');

    if ($imagen && $imagen->isValid() && ! $imagen->hasMoved()) {

      $destino = FCPATH . 'imgEventos/ev_' . $this->model->insertID() . '/';
      $ext     = $imagen->getClientExtension();
      $cartel  = 'cartel.jpg';
      if (file_exists($destino . $cartel)) {
        unlink($destino . $cartel);
      }

      $this->_upload($imagen, $destino, $cartel);
    }

    $pdf_adjunto = $this->request->getFile('pdf_adjunto');

    if ($pdf_adjunto && $pdf_adjunto->isValid() && ! $pdf_adjunto->hasMoved()) {

      $destino = 'pdf_' . $this->model->insertID() . '.pdf';
      $camino  = FCPATH . 'pdfEventos/';

      $this->_upload($pdf_adjunto, $camino, $destino);
    }

    return redirect()->to(base_url('control/eventos'));
  }

  public function edit($id = null)
  {
    $evento = $this->model->asObject()->find($id);
    $lista  = $this->tipos->asObject()->OrderBy('eventonombre', 'ASC')->findAll();
    $data   = [
      'titulo' => 'Edición de Eventos',
      'id'     => $id,
      'evento' => $evento,
      'lista'  => $lista,
    ];

    return view('admin/eventos/editar', $data);
  }

  public function update($id = null)
  {

    $reglas = [
      'titulo'            => "required|trim",
      'eventotipo'        => 'required',
      'desde'             => 'required|valid_date',
      'hasta'             => 'required|valid_date',
      'desde_inscripcion' => 'permit_empty|valid_date',
      'hasta_inscripcion' => 'permit_empty|valid_date',
      'cartel'            => 'permit_empty|max_size[cartel,6144]|ext_in[cartel,jpg,jpeg]|is_image[cartel]',
      'pdf'               => 'permit_empty|max_size[pdf,10240]|ext_in[pdf,pdf]|mime_in[pdf,application/pdf]',
    ];

    if (! $this->validarRangoFechas(
      $this->request->getPost('desde'),
      $this->request->getPost('hasta'),
      'error_hasta',
      'La fecha de finalización no puede ser anterior a la de Inicio de este evento'
    )) {
      $recarga = true;
    }

    $post = $this->request->getPost();

    $noTieneCartel = isset($post['noTieneCartel']) ? 1 : 0;
    $noTienePdf    = isset($post['noTienePdf']) ? 1 : 0;

    $inscripcion            = isset($post['inscripcion']) ? 1 : 0;
    $inscripcion_invitacion = isset($post['inscripcion_invitacion']) ? 1 : 0;

    if ($inscripcion) {
      $reglas['desde_inscripcion'] = 'required|valid_date';
      $reglas['hasta_inscripcion'] = 'required|valid_date';

      if (! $this->validarRangoFechas(
        $this->request->getPost('desde_inscripcion'),
        $this->request->getPost('hasta_inscripcion'),
        'error_hasta_inscripcion',
        'La fecha final de inscripción no puede ser anterior a la de Inicio'
      )) {
        $recarga = true;
      }
    }
    $data = $this->construirPayloadEvento(
      $post,
      (int) $id,
      true,
      true
    );

    if ($inscripcion) {
      $data['desde_inscripcion'] = $this->request->getPost('desde_inscripcion');
      $data['hasta_inscripcion'] = $this->request->getPost('hasta_inscripcion');
    }

    if (! $this->validate($reglas)) {
      $reglas_errores = $this->validator->getErrors();
      $recarga        = true;
    }

    if (isset($recarga)) {
      unset($recarga);
      return redirect()->back()->withInput();
    }

    if ($noTieneCartel) {
      $destino = FCPATH . 'imgEventos/ev_' . $id . '/cartel.jpg';
      if (file_exists($destino)) {
        unlink($destino);
      }
    } else {

      $file = $this->request->getFile('cartel');

      if ($file && $file->isValid() && ! $file->hasMoved()) {
        $uploads = FCPATH . 'imgEventos/ev_' . $id . '/';
        $newName = 'cartel.jpg';

        if (file_exists($uploads . $newName)) {
          unlink($uploads . $newName);
        }

        $this->_upload($file, $uploads, $newName);
      }
    }

    if ($noTienePdf) {
      $destino = FCPATH . 'pdfEventos/pdf_' . $id . '.pdf';
      if (file_exists($destino)) {
        unlink($destino);
      }
    } else {

      $file = $this->request->getFile('pdf');

      if ($file && $file->isValid() && ! $file->hasMoved()) {
        $uploads = FCPATH . 'pdfEventos/';
        $newName = 'pdf_' . $id . '.pdf';

        if (file_exists($uploads . $newName)) {
          unlink($uploads . $newName);
        }

        $this->_upload($file, $uploads, $newName);
      }
    }

    $this->model->save($data);

    return redirect()->to(base_url('control/eventos'));
  }

  public function delete($id = null)
  {
    $this->model->delete($id);
    return redirect()->to(base_url('control/eventos'));
  }

  private function _upload($origen, $camino, $destino)
  {
    if (! is_dir($camino)) {
      mkdir($camino, 0755, true);
    }

    if ($origen->isValid() && ! $origen->hasMoved()) {
      $origen->move($camino, $destino);
    }
  }

  public function sendmail($id = null)
  {

    $session = session();
    $session->setFlashdata('mensaje', 'Envío de invitaciones a ' . $id);

    if (! $id) {
      return redirect()->to(base_url('control/eventos'));
    }

    // Modificar valores
    //ini_set('max_execution_time', 600);
    //ini_set('max_input_time', 600);
    //ini_set('memory_limit', '512M');

    // Guardar valores originales
    $original_max_execution_time = ini_get('max_execution_time');
    $original_max_input_time     = ini_get('max_input_time');
    $original_memory_limit       = ini_get('memory_limit');

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
    $plantilla .= "<br><p></p>Puede inscribirse clicando <a href='" . base_url('inscribirse/' . $id . '/idcontacto') . "' class='boton_email_inscribirse'>aquí</a></><br>";

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

    $builder = $this->db->table('contactos');
    $builder->where('invitaciones', 1);

    if ($condiciones !== []) {
      $builder->groupStart();
      foreach ($condiciones as $index => $condicion) {
        [$campo] = explode(' ', $condicion);
        if ($index === 0) {
          $builder->where($campo, 1);
        } else {
          $builder->orWhere($campo, 1);
        }
      }
      $builder->groupEnd();
    } else {
      $builder->where('id', 0);
    }

    $query     = $builder->get();
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

      $cuerpo         = $plantilla;
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

      $cuerpo = str_replace('{{baja_emails}}', $this->buildUnsubscribeUrl((int) $contacto->id, 'emails'), $cuerpo);
      $cuerpo = str_replace('{{baja_invitaciones}}', $this->buildUnsubscribeUrl((int) $contacto->id, 'invitaciones'), $cuerpo);
      $cuerpo = str_replace('{{baja_total}}', $this->buildUnsubscribeUrl((int) $contacto->id, 'total'), $cuerpo);

      $email->setMessage($cuerpo);

      $pdfPath = null;

      if (file_exists(FCPATH . 'pdfEventos/pdf_' . $id . '.pdf')) {
        $pdfPath = FCPATH . 'pdfEventos/pdf_' . $id . '.pdf';
      }

      if($pdfPath != null) {
        $email->attach($pdfPath);
      }

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
      'asunto'    => '<small>Invitaciones a</small><br>' . $evento->titulo,
      'correctos' => $correctos,
      'errores'   => $errores,
      'repetidos' => $repetidos,
    ];

    return view('admin/eventos/carteado', $data);
  }

  public function listaInvitados($id = null)
  {

    $evento    = $this->model->find($id);
    $invitados = $this->invitados
      ->where('id_evento', $id)
      ->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Invitados',
      'invitados' => $invitados,
    ];

    return view('admin/eventos/listaInvitados', $data);
  }

  public function quitarInvitado($id)
  {

    $invitado  = $this->invitados->find($id);
    $id_evento = $invitado->id_evento;
    $evento    = $this->model->find($id_evento);

    $this->db->transStart();
    $this->enEspera->where('id_invitado', $id)->delete();
    $this->inscritos->where('id_invitado', $id)->delete();
    $this->invitados->delete($id);
    $this->db->transComplete();

    $invitados = $this->invitados->where('id_evento', $id_evento)->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Invitados',
      'invitados' => $invitados,
    ];

    return view('admin/eventos/listaInvitados', $data);
  }

  public function contactoDesdeInscrito($id = 0)
  {

    if (! $id) {
      $id = (int) $this->request->getPost('id_inscrito');
    }

    if (! $id) {
      return redirect()->to(base_url('control/eventos'));
    }

    $inscrito = $this->inscritos->find($id);

    if (! $inscrito) {
      return redirect()->to(base_url('control/eventos'));
    }

    $datosContacto = [
      'nombre'       => $inscrito->nombre,
      'apellidos'    => $inscrito->apellidos,
      'email'        => $inscrito->email,
      'telefono'     => $inscrito->telefono,
      'mailing'      => 1,
      'invitaciones' => 1,
      'socio'        => $this->request->getPost('socio') !== null ? 1 : 0,
      'alumno'       => $this->request->getPost('alumno') !== null ? 1 : 0,
      'pdalumno'     => $this->request->getPost('pdalumno') !== null ? 1 : 0,
      'pintor'       => $this->request->getPost('pintor') !== null ? 1 : 0,
      'dtaller'      => $this->request->getPost('dtaller') !== null ? 1 : 0,
      'amigo'        => $this->request->getPost('amigo') !== null ? 1 : 0,
    ];

    $id_contacto = $this->contactos->insert($datosContacto);

    $this->inscritos->update($id, ['id_contacto' => $id_contacto]);

    $evento    = $this->model->find($inscrito->id_evento);
    $inscritos = $this->inscritos->where('id_evento', $inscrito->id_evento)->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Inscritos',
      'inscritos' => $inscritos,
    ];

    return view('admin/eventos/listaInscritos', $data);

    //return redirect()->to(base_url('control/contactos/editar/'.$id_contacto));

  }

  public function completaContactoDesdeInscrito($id)
  {

    $inscrito = $this->inscritos->find($id);

    $data = [
      'titulo'    => 'Completar Contacto',
      'inscrito'  => $inscrito,
    ];

    return view('admin/eventos/completaContacto', $data);

  }

  public function inscribirInvitado($id, $id_evento)
  {

    $datosInvitado = $this->invitados->find($id);

    $datosInscritos = [
      'id_evento'   => $id_evento,
      'id_contacto' => $datosInvitado->id_contacto,
      'nombre'      => $datosInvitado->nombre,
      'apellidos'   => $datosInvitado->apellidos,
      'email'       => $datosInvitado->email,
      'telefono'    => $datosInvitado->telefono,
      'via'         => 'Invitado',
    ];

    if (! $datosInvitado->inscrito) {
      $this->db->transStart();
      $this->inscritos->insert($datosInscritos);
      $this->invitados->update($id, ['inscrito' => 1, 'enespera' => 0]);
      $this->db->transComplete();
    }

    $evento = $this->model->find($id_evento);

    $datosInvitado = $this->invitados
      ->where('id_evento', $id_evento)
      ->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Invitados',
      'invitados' => $datosInvitado,
    ];

    return view('admin/eventos/listaInvitados', $data);
  }

  public function listaInscritos($id = null)
  {

    $evento    = $this->model->find($id);
    $inscritos = $this->inscritos->where('id_evento', $id)->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Inscritos',
      'inscritos' => $inscritos,
    ];

    return view('admin/eventos/listaInscritos', $data);
  }

  public function quitarInscrito($id)
  {
    $inscrito = $this->inscritos->find($id);

    $datosEnEspera = [
      'id_invitado' => $inscrito->id_invitado,
      'id_evento'   => $inscrito->id_evento,
      'id_contacto' => $inscrito->id_contacto,
      'nombre'      => $inscrito->nombre,
      'apellidos'   => $inscrito->apellidos,
      'email'       => $inscrito->email,
      'telefono'    => $inscrito->telefono,
      'fecha'       => $inscrito->fecha,
    ];
    if ($inscrito->id_invitado > 0) {
      $this->db->transStart();
      $this->enEspera->insert($datosEnEspera);
      $this->invitados->update($inscrito->id_invitado, ['inscrito' => 0, 'enespera' => 1]);
      $this->inscritos->delete($id);
      $this->db->transComplete();
    } else {
      $this->inscritos->delete($id);
    }

    $evento    = $this->model->find($inscrito->id_evento);
    $inscritos = $this->inscritos->where('id_evento', $inscrito->id_evento)->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Inscritos',
      'inscritos' => $inscritos,
    ];

    return view('admin/eventos/listaInscritos', $data);
  }

  public function inscripcionManual($id_contacto = null)
  {
    $hoy = date('Y-m-d', strtotime($this->hoy));

    $eventos = $this->model
      ->where('evento_cerrado', 0)
      ->where('inscripcion', 1)
      ->where('desde_inscripcion <=', $hoy)
      ->where('hasta_inscripcion >=', $hoy)
      ->orderBy('desde', 'DESC')
      ->findAll();

    $contacto = null;

    if ($id_contacto) {
      $contacto = $this->contactos->find($id_contacto);
    }

    $data = [
      'contacto' => $contacto,
      'eventos'  => $eventos,
      'titulo'   => 'Inscripción Manual',
      'ahora'    => $this->ahora,
    ];

    return view('admin/eventos/inscripcionManual', $data);
  }

  public function grabarInscripcionManual()
  {

    $validation = \Config\Services::validation();

    $rules = [
      'nombre'    => 'required',
      'apellidos' => 'required',
      'telefono'  => 'required',
    ];

    if ($this->request->getPost('email') !== "") {
      $rules['email'] = 'valid_email';
    }

    if (! $this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    $datos = [
      'id_evento'   => $this->request->getPost('evento'),
      'id_contacto' => $this->request->getPost('id_contacto') ?? 0,
      'nombre'      => $this->request->getPost('nombre'),
      'apellidos'   => $this->request->getPost('apellidos'),
      'telefono'    => $this->request->getPost('telefono'),
      'email'       => $this->request->getPost('email'),
      'via'         => 'manual',
    ];

    if ($datos['id_contacto'] > 0) {
      if ($this->inscritos->where('id_evento', $datos['id_evento'])
        ->where('id_contacto', $datos['id_contacto'])
        ->countAllResults() > 0
      ) {
        return redirect()->back()->withInput()->with('errors', ['Ya está inscrito en este evento']);
      }
    }

    $this->inscritos->insert($datos);

    if ($this->request->getPost('avisar') !== null) {

      $id     = $this->request->getPost('evento');
      $evento = $this->model->find($id);

      $cabeceraCorreo = "recursos/imagenes/logo_Cercle_125.png";
      $correoTexto    = "<div style='text-align: left;'>";
      $correoTexto .= "<p>Desde el Cercle d'Art de Foios le comunicamos que se ha procedido a su inscripción en el evento</p>";
      $correoTexto .= "<p style='width: 100%; text-align: center; font-weight: bold;'>" . strtoupper($evento->titulo) . ".</p>";
      if ($evento->desde == $evento->hasta):
        $correoTexto .= "<p>que se celebrará durante el " . date('d/m/Y', strtotime($evento->desde)) . ".</p>";
      else:
        $correoTexto .= "<p>que se extenderá desde el " . date('d/m/Y', strtotime($evento->desde)) . " hasta el " . date('d/m/Y', strtotime($evento->hasta)) . ".</p>";
      endif;
      $correoTexto .= "<br><p>Un saludo del Cercle d'Art de Foios</p>";
      $correoTexto .= "<br><p style='font-style: italic'>Si esta inscripción ha sido un error por nuestra parte, por favor no dude en comunicarlo por correo electrónico a correo@cercledartfoios.com</p>";
      $correoTexto .= "</div>";
      $correoAsunto = "Inscripción en un evento del Cercle d'Art de Foios";
      $correoEmail  = $this->request->getPost('email');

      $email = \Config\Services::email();

      $email->setFrom('noreply@cercledartfoios.com', "Cercle d'Art Foios", 'rechazados@cercledartfoios.com');

      $plantilla = file_get_contents('recursos/plantillasmail/cabeceraMail.php');
      // $plantilla = str_replace('{{cabeceraCorreo}}', $cabeceraCorreo, $plantilla);
      $plantilla .= $correoTexto;

      $email->clear();
      $email->setSubject($correoAsunto);
      $email->setTo($correoEmail, 'info@cercledartfoios.com');

      $email->setMessage($plantilla);

      if ($this->request->getPost('email') !== "") {
        $res = $email->send();
      }
    }
    return view('admin/dashboard');
  }

  public function listaDeEspera($id = null)
  {

    $evento    = $this->model->find($id);
    $esperando = $this->enEspera
      ->where('id_evento', $id)
      ->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Espera',
      'esperando' => $esperando,
    ];

    return view('admin/eventos/listaDeEspera', $data);
  }

  public function inscribirDeEspera($id)
  {

    $enEspera = $this->enEspera->find($id);

    $datosInscritos = [
      'id_invitado' => $enEspera->id_invitado,
      'id_evento'   => $enEspera->id_evento,
      'id_contacto' => $enEspera->id_contacto,
      'nombre'      => $enEspera->nombre,
      'apellidos'   => $enEspera->apellidos,
      'email'       => $enEspera->email,
      'telefono'    => $enEspera->telefono,
      'via'         => 'De espera',
    ];

    $this->db->transStart();
    $this->inscritos->insert($datosInscritos);

    $this->invitados
      ->update(
        $enEspera->id_invitado,
        [
          'enespera' => 0,
          'inscrito' => 1,
        ]
      );

    $evento = $this->model->find($enEspera->id_evento);

    $this->enEspera->delete($id);
    $this->db->transComplete();

    $esperando = $this->enEspera
      ->where('id_evento', $enEspera->id_evento)
      ->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Espera',
      'esperando' => $esperando,
    ];

    return view('admin/eventos/listaDeEspera', $data);
  }

  public function quitarDeEspera($id)
  {
    $enEspera = $this->enEspera->find($id);

    $this->db->transStart();
    $this->enEspera->delete($id);
    $this->invitados->update($enEspera->id_invitado, ['enespera' => 0]);
    $this->db->transComplete();

    $evento    = $this->model->find($enEspera->id_evento);
    $esperando = $this->enEspera->where('id_evento', $evento->id)->findAll();

    $data = [
      'evento'    => $evento,
      'titulo'    => 'Lista de Espera',
      'esperando' => $esperando,
    ];

    return view('admin/eventos/listaDeEspera', $data);
  }

  public function fotos($id)
  {
    $evento = $this->model->find($id);

    // Busca Fotos

    $dirtoscan = dirname($_SERVER['PHP_SELF']);

    if (is_dir('imgEventos/ev_' . $id)) {
      $imgs = scandir('imgEventos/ev_' . $id);
    } else {
      $imgs = [];
    }

    $fotos = [];
    foreach ($imgs as $img) {
      if (! in_array($img, ['.', '..', 'Cartel.jpg', 'cartel.jpg'])) {
        $fotos[] = $img;
      }
    }

    $data = [
      'evento' => $evento,
      'fotos'  => $fotos,
      'titulo' => 'Fotografías',
    ];

    return view('admin/eventos/fotografias', $data);
  }

  public function eliminarFoto($id, $foto)
  {

    $evento = $this->model->find($id);

    // Busca Foto

    $fotoLimpia = basename(rawurldecode($foto));
    $a_borrar   = FCPATH . 'imgEventos/ev_' . $id . '/' . $fotoLimpia;
    if (file_exists($a_borrar)) {
      unlink($a_borrar);
    }

    return redirect()->to(base_url('control/fotos/' . $id));
  }

  public function agregarFotos($id)
  {

    $evento = $this->model->find($id);

    $images = $this->request->getFiles();

    $validationRules = [
      'images' => [
        'label'  => 'Fotografías',
        'rules'  => 'uploaded[images.0]|max_size[images,6144]|ext_in[images,jpg,jpeg]',
        'errors' => [
          'uploaded' => 'Debes seleccionar al menos una imagen.',
          'max_size' => 'Cada imagen no puede superar los 6MB.',
          'ext_in'   => 'Solo se permiten imágenes JPG o JPEG.',
        ],
      ],
    ];

    if (! $this->validate($validationRules)) {
      // Redirigir con errores de validación
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Verificar si se subieron archivos
    if ($images) {
      foreach ($images['images'] as $file) {
        if ($file->isValid() && ! $file->hasMoved()) {
          $nuevoNombre = $file->getRandomName(); // Nombre único
          $destino     = 'imgEventos/ev_' . $id . '/';
          $file->move($destino, $nuevoNombre);
          $imagePath = $destino . $nuevoNombre;

          // Obtener tamaño de la imagen
          list($width, $height) = getimagesize($imagePath);

          // Solo redimensionar si la imagen es mayor a 1280x800
          if ($width > 1280 || $height > 800) {

            // Redimensionar la imagen
            \Config\Services::image()
              ->withFile($imagePath)
              ->resize(1280, 800, true, 'auto') // Mantiene proporciones
              ->save($imagePath);               // Guarda la imagen
          }
        }
      }

      if (is_dir('imgEventos/ev_' . $id)) {
        $imgs = scandir('imgEventos/ev_' . $id);
      } else {
        $imgs = [];
      }

      return redirect()->to(base_url('control/fotos/' . $id));
    }
  }
}

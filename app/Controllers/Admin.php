<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\CercledartfoiosModel;

class Admin extends BaseController
{
    private $UsuarioModel;
    private $SistemaModel;

    private $id;
    private $db;
    private $sql;

    public function __construct()
    {
        $this->UsuarioModel = new UsuariosModel();
        $this->SistemaModel = new CercledartfoiosModel();

        $this->db = \Config\Database::connect();
        $this->sql = $this->db->table('usuarios');

    }
    public function index(){

        if(!$this->session->logueado){
            return $this->login(); //redirect()->route(base_url('login'));
        }
        return view('admin/dashboard', $this->buildDashboardData());
    }

    public function login(){

        if($this->UsuarioModel->where('user', "admin")->countAllResults() < 1) {
            // Solo crear el admin inicial con contraseña explícita de entorno.
            $defaultAdminPassword = trim((string) env('defaultAdminPassword'));
            if ($defaultAdminPassword === '') {
                log_message('critical', 'No se puede crear usuario admin inicial: falta configurar defaultAdminPassword en entorno.');
                return view('login', ['titulo' => 'Acceder']);
            }
            $admin = [
                'user' => 'admin',
                'pass' => password_hash($defaultAdminPassword, PASSWORD_DEFAULT),
                'admin'=> 1
            ];

            $this->UsuarioModel->insert($admin);
        }

        $this->db = \Config\Database::connect();
        $this->sql = $this->db->table('usuarios');

        if(!$this->session->logueado){
            return view('login',['titulo'=>'Acceder']);
        }else {
            return $this->dashboard();
        }
    }

    public function validar(){
        $user = trim((string) $this->request->getPost('user'));
        $pass = (string) $this->request->getPost('pass');

        if($user === '' || $pass === '') {
            return view('login',['titulo'=>'Acceder']);
        }

        $user = $this->UsuarioModel->asObject()->where('user',$user)->first();
        if($user && strlen((string) $user->pass) === 32) {

            $legacyMatches = hash_equals((string) $user->pass, md5($pass));

            if(!$legacyMatches) {
                return view('login',['titulo'=>'Acceder']);
            }

            $id = $user->id;
            $password = password_hash($pass, PASSWORD_DEFAULT);
            $data = [
                'pass' => $password
            ];

            if($this->UsuarioModel->update($id, $data)) {
                $user->pass = $password;
            }
        }

        if(!$user or !password_verify($pass, $user->pass) or !$user->admin){
            return view('login',['titulo'=>'Acceder']);
        }

        $data_session = [
            'user' => $user->user,
            'user_id' => $user->id,
            'user_name' => $user->nombre,
            'user_admin' => $user->admin,
            'logueado' => true
        ];

        $this->session->regenerate(true);
        $this->session->set($data_session);

        return redirect()->to(base_url('dashboard'));

    }

    public function dashboard(){
        return view('admin/dashboard', $this->buildDashboardData());

    }

    public function ayuda(){
        return view('admin/ayuda', ['titulo' => 'Ayuda del Panel']);
    }

    private function buildDashboardData(): array
    {
        $logoPath = 'recursos/imagenes/anagramaColor.png';
        $dashboardLogo = base_url($logoPath);
        $sistema = $this->SistemaModel->asObject()->find(1);
        $hoy = date('Y-m-d');

        if ($sistema && (int) $sistema->visible === 1) {
            $banner = trim((string) ($sistema->pinterest ?? ''));

            if ($banner !== '') {
                $candidatos = [];
                $candidatos[] = $banner;

                $urlPath = parse_url($banner, PHP_URL_PATH);
                if (is_string($urlPath) && $urlPath !== '') {
                    $candidatos[] = basename($urlPath);
                }

                $candidatos[] = basename($banner);
                $candidatos = array_values(array_unique(array_filter(array_map('trim', $candidatos))));

                $dir = FCPATH . 'recursos/imagenes/';

                foreach ($candidatos as $cand) {
                    if ($cand !== '' && is_file($dir . $cand)) {
                        $dashboardLogo = base_url('recursos/imagenes/' . $cand);
                        break;
                    }

                    $base = pathinfo($cand, PATHINFO_FILENAME);
                    if ($base !== '') {
                        $coincidencias = glob($dir . $base . '.*');
                        if (!empty($coincidencias)) {
                            $dashboardLogo = base_url('recursos/imagenes/' . basename($coincidencias[0]));
                            break;
                        }
                    }
                }
            }
        }

        $dashboardCounts = [
            'usuarios' => (int) $this->db->table('usuarios')->countAllResults(),
            'sistema' => (int) $this->db->table('cercledartfoios')->countAllResults(),
            'enlaces' => (int) $this->db->table('enlaces_de_interes')->countAllResults(),
            'contactos' => (int) $this->db->table('contactos')->countAllResults(),
            'correos' => (int) $this->db->table('correos')->countAllResults(),
            'tipos' => (int) $this->db->table('tiposeventos')->countAllResults(),
            'eventos' => (int) $this->db->table('neventos')->countAllResults(),
            'inscripcionManual' => (int) $this->db->table('neventos')
                ->where('inscripcion', 1)
                ->where('evento_cerrado', 0)
                ->where('desde_inscripcion <=', $hoy)
                ->where('hasta_inscripcion >=', $hoy)
                ->countAllResults(),
            'emailsIns' => (int) $this->db->table('emails_inscripciones')->where('inscrito', 0)->countAllResults(),
        ];

        $mailingsResumen = $this->db->table('mailings_resumen')
            ->selectSum('enviados', 'total_enviados')
            ->get();
        $mailingsRow = $mailingsResumen ? $mailingsResumen->getRow() : null;
        $totalEnviados = (int) ($mailingsRow->total_enviados ?? 0);

        $mailingsErrores = $this->db->table('mailings_detalles')
            ->where('error IS NOT NULL', null, false)
            ->where('error !=', '')
            ->countAllResults();

        $dashboardHistory = [
            'campanas' => (int) $this->db->table('mailings_resumen')->countAllResults(),
            'envios' => $totalEnviados,
            'errores' => (int) $mailingsErrores,
            'inscritos' => (int) $this->db->table('inscritos')->countAllResults(),
            'espera' => (int) $this->db->table('enespera')->countAllResults(),
        ];

        $dashboardExtras = [
            'usuarios' => [
                'label' => 'Admins',
                'value' => (int) $this->db->table('usuarios')->where('admin', 1)->countAllResults(),
            ],
            'sistema' => [
                'label' => 'Visible',
                'value' => (int) $this->db->table('cercledartfoios')->where('visible', 1)->countAllResults(),
            ],
            'contactos' => [
                'label' => 'Con mailing',
                'value' => (int) $this->db->table('contactos')->where('mailing', 1)->countAllResults(),
            ],
            'correos' => [
                'label' => 'Enviados',
                'value' => $totalEnviados,
            ],
            'tipos' => [
                'label' => 'En uso',
                'value' => (int) $this->db->table('neventos')->select('eventotipo')->groupBy('eventotipo')->countAllResults(),
            ],
            'eventos' => [
                'label' => 'Invitados pendientes',
                'value' => (int) $this->db->table('invitados')
                    ->where('inscrito', 0)
                    ->where('enespera', 0)
                    ->countAllResults(),
            ],
            'inscripcionManual' => [
                'label' => 'Inscritos totales',
                'value' => (int) $this->db->table('inscritos')->countAllResults(),
            ],
            'emailsIns' => [
                'label' => 'Ya inscritos',
                'value' => (int) $this->db->table('emails_inscripciones')->where('inscrito', 1)->countAllResults(),
            ],
        ];

        return [
            'titulo' => 'Administración',
            'dashboardLogo' => $dashboardLogo,
            'dashboardCounts' => $dashboardCounts,
            'dashboardExtras' => $dashboardExtras,
            'dashboardHistory' => $dashboardHistory,
        ];
    }

    public function logout(){
        if($this->session->logueado){
            $this->session->destroy();
        }
        return redirect()->to(base_url('admin'));

    }

}

<?php

namespace App\Controllers;

use App\Models\UsuariosModel;
use App\Models\ContactosModel;

class Pruebas extends BaseController
{
    private $usuariosModel;
    private $contactosModel;
    private $id;
    private $db;
    private $sql;

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();
        $this->contactosModel = new ContactosModel();

        $this->db = \Config\Database::connect();
    }

    public function hasea(): string
    {
        // Usar variable de entorno para la contraseña por defecto
        $defaultPassword = env('defaultUserPassword', 'Cercle46134');
        $data[] = $this->db->query("UPDATE Usuarios SET pass = '". password_hash($defaultPassword, PASSWORD_BCRYPT) ."'");


        return view('home',$data);
    }

    public function uneNombreApellidos() {
        $this->db->query("UPDATE Contactos SET nombre = TRIM(CONCAT(nombre, ' ', apellidos))");
    }

    public function pontokenunico() {
        $this->db->query("UPDATE `contactos` SET `tokenunico`=md5(`nombre`) WHERE 1");
    }

    public function informa_del_host() {
        $request = service('request');
        $host = $request->getServer('HTTP_HOST');
        echo "Con service request: " . $host;

        echo '<br>';

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        echo 'con $_SERVER[HTTP_HOST]: ' . $host;

        echo '<br>';

        $host = $_SERVER['SERVER_NAME'] ?? 'localhost';
        echo 'con $_SERVER[SERVER_NAME]: ' . $host;

        echo '<br>';

        $config = config('App');
        $baseURL = $config->baseURL;

        echo "con parse_url: " . parse_url($baseURL, PHP_URL_HOST);

        echo "<hr>";
        echo "recaptchaSiteKey: " . env("recaptchaSiteKey");
        echo "<br>";
        echo "recaptchaSecretKey: " . env("recaptchaSecretKey");
    }

}

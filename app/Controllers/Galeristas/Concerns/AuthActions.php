<?php

namespace App\Controllers\Galeristas\Concerns;

trait AuthActions
{
    public function index()
    {
        if (! $this->session->hayGalerias) {
            return $this->login();
        }

        return redirect()->route('galeristas/lista');
    }

    public function login()
    {
        if ($this->UsuarioModel->where('user', 'admin')->countAllResults() < 1) {
            $defaultAdminPassword = trim((string) env('defaultAdminPassword'));
            if ($defaultAdminPassword !== '') {
                $admin = [
                    'user' => 'admin',
                    'pass' => password_hash($defaultAdminPassword, PASSWORD_DEFAULT),
                    'admin' => 1,
                ];
                $this->UsuarioModel->insert($admin);
            } else {
                log_message('critical', 'Falta defaultAdminPassword para bootstrap de usuario admin en flujo de galeristas.');
            }
        }

        if (! $this->session->hayGalerias) {
            return view('galeristas/auth/login', ['titulo' => 'Acceder', 'mostrarCabecera' => false]);
        }

        return redirect()->route('galeristas/lista');
    }

    public function validar()
    {
        $user = $this->request->getPost('user');
        $pass = $this->request->getPost('pass');

        $artista = $this->UsuarioModel
            ->asObject()
            ->where('user', $user)
            ->first();

        if ($artista and strlen($artista->pass) == 32) {
            $id = $artista->id;
            $password = password_hash($pass, PASSWORD_DEFAULT);

            if ($this->UsuarioModel->update($id, ['pass' => $password])) {
                return view('galeristas/auth/login', ['titulo' => 'Acceder', 'mostrarCabecera' => false]);
            }
        }

        if (! $artista or ! password_verify($pass, $artista->pass)) {
            return view('galeristas/auth/login', ['titulo' => 'Acceder', 'mostrarCabecera' => false]);
        }

        $this->session->set([
            'artista' => $artista->user,
            'artista_id' => $artista->id,
            'artista_name' => $artista->nombre,
            'artista_admin' => $artista->admin,
            'hayGalerias' => true,
        ]);

        return redirect()->route('galeristas/lista');
    }

    public function logout()
    {
        if ($this->session->hayGalerias) {
            $this->session->remove([
                'artista',
                'artista_id',
                'artista_name',
                'artista_admin',
                'hayGalerias',
            ]);
        }

        return redirect()->to(base_url('galeristas'));
    }
}

<?php

namespace App\Controllers;

class Sitemap extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $eventos = $db->table('neventos')
            ->select('id, hasta')
            ->where('visible', 1)
            ->orderBy('hasta', 'DESC')
            ->get()
            ->getResult();

        $artistas = $db->table('galerias')
            ->select('DISTINCT id_user')
            ->get()
            ->getResult();

        $data = [
            'eventos'  => $eventos,
            'artistas' => $artistas,
        ];

        return $this->response
            ->setContentType('application/xml')
            ->setBody(view('sitemap', $data));
    }
}

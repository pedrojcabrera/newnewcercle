<?php

namespace App\Controllers;

use App\Models\GaleriasModel;

class Galerias extends BaseController
{
    private $galeriaModel;

    private $id;
    private $db;
    private $sql;

    public function __construct()
    {
        $this->galeriaModel = new GaleriasModel();

        $this->db = \Config\Database::connect();
        $this->sql = $this->db->table('galerias');

    }

    public function lista(): string
    {
        $this->sql = $this->db->table('galerias');
        $this->sql->select('galerias.id_user, usuarios.nombre, COUNT(galerias.id) AS total_obras');
        $this->sql->join('usuarios', 'usuarios.id = id_user');
        $this->sql->groupBy('galerias.id_user, usuarios.nombre');
        $this->sql->orderby('usuarios.nombre', 'ASC');
        $query = $this->sql->get();

        $artistas = $query->getResult();
        $nombres = [];
        $cantidad = [];

        if (count($artistas) > 0) {
            foreach ($artistas as $artista) {
                $nombres[$artista->id_user] = $artista->nombre;
                $cantidad[$artista->id_user] = (int) ($artista->total_obras ?? 0);
            }
        }

        $data = [
            'titulo'    => 'Galerias',
            'artistas'  => $artistas,
            'nombres'   => $nombres,
            'cantidad'  => $cantidad
        ];

        return view('galerias/lista',$data);
    }

    public function show($id)
    {
        $this->sql = $this->db->table('galerias');
        $this->sql->select('*, usuarios.nombre, galerias.id as cuadro');
        $this->sql->join('usuarios', 'usuarios.id = id_user');
        $this->sql->where('id_user',$id);
        $query = $this->sql->get();

        $obras = $query->getResult();

        if (count($obras) === 0) {
            return redirect()->to(base_url('galerias'));
        }

        $data = [
            'titulo'        => 'Galería',
            'obras'         => $obras,
            'nombre'        => $obras[0]->nombre,
            'artista'       => $id,
            'ogType'        => 'article',
            'ogImage'       => base_url('fotosUsuarios/' . $id . '.jpg'),
            'ogDescription' => "Galería de " . esc($obras[0]->nombre) . " en el Cercle d'Art de Foios.",
        ];

        return view('galerias/show',$data);
    }

}

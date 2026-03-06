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
        $this->sql->select('galerias.id_user, usuarios.nombre')->distinct();
        $this->sql->join('usuarios', 'usuarios.id = id_user');
        $this->sql->orderby('id_user' , 'ASC');
        $query = $this->sql->get();

        $artistas = $query->getResult();

        if(count($artistas) > 0) {

            foreach ($artistas as $artista) {

                $this->sql->select('*');
                $this->sql->where('id_user = ' , $artista->id_user);
                $query = $this->sql->get();
                
                $obras[$artista->id_user] = $query->getResult();
                $nombres[$artista->id_user] = $artista->nombre;
                $cantidad[$artista->id_user]=count($obras[$artista->id_user]);
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

    public function show($id): string
    {
        $this->sql->select('*, usuarios.nombre, galerias.id as cuadro');
        $this->sql->join('usuarios', 'usuarios.id = id_user');
        $this->sql->where('id_user',$id);
        $query = $this->sql->get();

        $obras = $query->getResult();

        $data = [
            'titulo'  => 'Galería',
            'obras'   => $obras,
            'nombre'  => $obras[0]->nombre,
            'artista' => $id
        ];

        return view('galerias/show',$data);
    }

}
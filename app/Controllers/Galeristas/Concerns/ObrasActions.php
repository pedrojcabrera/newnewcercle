<?php

namespace App\Controllers\Galeristas\Concerns;

trait ObrasActions
{
    public function lista()
    {
        $id = $this->session->get('artista_id');

        $datos = [
            'data' => $this->UsuarioModel->find($id),
            'obras' => $this->GaleriaModel->asObject()->where('id_user', $id)->findAll(),
            'titulo' => 'Galería',
            'hayLogout' => true,
        ];

        return view('galeristas/obras/lista', $datos);
    }

    public function cancelar()
    {
        return redirect()->route('galeristas/lista');
    }

    public function nuevo()
    {
        $id = $this->session->get('artista_id');

        return view('galeristas/obras/nuevo', array_merge([
            'usuario' => $this->UsuarioModel->find($id),
            'titulo' => 'Nueva Obra en Galería',
        ], $this->getYearRange()));
    }

    public function crear()
    {
        $validation = \Config\Services::validation();
        $id = session()->get('artista_id');

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'titulo' => 'required',
                'tecnica' => 'required',
                'soporte' => 'required',
                'medidas' => 'required',
                'imagen' => [
                    'uploaded[imagen]',
                    'mime_in[imagen,image/jpg,image/jpeg]',
                    'max_size[imagen,2048]',
                ],
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $data = [
                'id_user' => $id,
                'autores' => $this->request->getPost('autor'),
                'titulo' => $this->request->getPost('titulo'),
                'tecnica' => $this->request->getPost('tecnica'),
                'soporte' => $this->request->getPost('soporte'),
                'medidas' => $this->request->getPost('medidas'),
                'premios' => $this->request->getPost('premios'),
                'comentarios' => $this->request->getPost('comentarios'),
                'ano' => $this->request->getPost('ano'),
                'precio' => $this->request->getPost('precio'),
            ];

            if ($this->GaleriaModel->insert($data)) {
                return $this->guardarImagenObra($id, $this->GaleriaModel->insertID());
            }
        }

        return redirect()->route('galeristas/lista');
    }

    public function quitar($obra = false)
    {
        $artista = session()->get('artista_id');

        if (! $obra) {
            return redirect()->back()->withInput();
        }

        if ($this->GaleriaModel->delete($obra)) {
            $destino = "galerias/$artista/$obra.jpg";
            if (file_exists($destino)) {
                unlink($destino);
            }
        }

        return redirect()->route('galeristas/lista');
    }

    public function editar($obra_id = false)
    {
        if (! $obra_id) {
            return redirect()->back()->withInput();
        }

        $regObra = $this->GaleriaModel->find($obra_id);
        if (! $regObra) {
            return redirect()->back()->withInput();
        }

        $regArtista = $this->UsuarioModel->find($regObra->id_user);
        if (! $regArtista) {
            return redirect()->back()->withInput();
        }

        return view('galeristas/obras/editar', array_merge([
            'usuario' => $regArtista,
            'obra' => $regObra,
            'titulo' => 'Edición de Obra en Galería',
        ], $this->getYearRange()));
    }

    public function modificar($obra_id)
    {
        $validation = \Config\Services::validation();
        $id = session()->get('artista_id');

        if ($this->request->getMethod() === 'PUT') {
            $rules = [
                'titulo' => 'required',
                'tecnica' => 'required',
                'soporte' => 'required',
                'medidas' => 'required',
                'imagen' => [
                    'permit_empty',
                    'uploaded[imagen]',
                    'mime_in[imagen,image/jpg,image/jpeg]',
                    'max_size[imagen,2048]',
                ],
            ];

            if (! $this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $data = [
                'id_user' => $id,
                'autores' => $this->request->getPost('autor'),
                'titulo' => $this->request->getPost('titulo'),
                'tecnica' => $this->request->getPost('tecnica'),
                'soporte' => $this->request->getPost('soporte'),
                'medidas' => $this->request->getPost('medidas'),
                'premios' => $this->request->getPost('premios'),
                'comentarios' => $this->request->getPost('comentarios'),
                'ano' => $this->request->getPost('ano'),
                'precio' => $this->request->getPost('precio'),
            ];

            if ($this->GaleriaModel->update($obra_id, $data)) {
                return $this->guardarImagenObra($id, $obra_id);
            }
        }

        return redirect()->route('galeristas/lista');
    }

    private function guardarImagenObra(int $artistaId, int $obraId)
    {
        $imagen = $this->request->getFile('imagen');
        if (! $imagen->isValid() || $imagen->hasMoved()) {
            return redirect()->route('galeristas/lista');
        }

        $rutaDestino = 'galerias/' . $artistaId;
        $nombreObra = $obraId . '.jpg';

        if (! is_dir($rutaDestino)) {
            mkdir($rutaDestino, 0777, true);
        }

        if ($imagen->move($rutaDestino, $nombreObra, true)) {
            return redirect()->route('galeristas/lista');
        }

        return redirect()->back()->with('error', 'Error al subir la imagen.');
    }
}

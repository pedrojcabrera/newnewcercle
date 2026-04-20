<?php

namespace App\Models;

use CodeIgniter\Model;

class EventosModel extends Model
{
    protected $table            = 'neventos';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    protected $protectFields    = true;
    protected $allowedFields    = [
        'titulo', 'eventotipo', 'desde',
        'hasta', 'texto', 'visible', 'socio',
        'alumno', 'pdalumno', 'pintor', 'dtaller',
        'amigo', 'inscripcion', 'desde_inscripcion',
        'hasta_inscripcion', 'inscripcion_invitacion',
        'aforo_completo', 'texto_carta', 'pdf_adjunto',
        'evento_cerrado',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    // protected $useTimestamps = true;
    // protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    private function getCardSelect(): string
    {
        return 'eventos.id, eventos.titulo, eventos.desde, eventos.hasta, tiposeventos.eventonombre AS grupo';
    }

    /**
     * Obtiene los últimos eventos finalizados
     * @param int $limit Cantidad de eventos a retornar
     * @return array
     */
    public function getUltimosEventos(int $limit = 3): array
    {
        $hoy = \CodeIgniter\I18n\Time::createFromDate()->toDateString();

        $db = \Config\Database::connect();
        $result = $db->table('neventos AS eventos')
            ->select($this->getCardSelect())
            ->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo')
            ->where('eventos.visible', '1')
            ->where('eventos.hasta <', $hoy)
            ->orderBy('eventos.hasta', 'DESC')
            ->limit($limit)
            ->get()
            ->getResult();

        return $result ? $result : [];
    }

    /**
     * Obtiene lista de eventos visibles
     * @return array
     */
    public function getListaEventos(): array
    {
        $db = \Config\Database::connect();
        $result = $db->table('neventos AS eventos')
            ->select($this->getCardSelect())
            ->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo')
            ->where('eventos.visible', 1)
            ->orderBy('eventos.hasta', 'DESC')
            ->get()
            ->getResult();

        return $result ? $result : [];
    }

    /**
     * Obtiene lista paginada de eventos visibles para frontend.
     * @param int $page Página actual
     * @param int $perPage Cantidad por página
     * @return array{eventos: array, total: int, totalPages: int, page: int, perPage: int}
     */
    public function getListaEventosPaginada(int $page = 1, int $perPage = 24): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);

        $db = \Config\Database::connect();

        $baseBuilder = $db->table('neventos AS eventos')
            ->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo')
            ->where('eventos.visible', 1);

        $total = (clone $baseBuilder)->countAllResults();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;

        $eventos = $baseBuilder
            ->select($this->getCardSelect())
            ->orderBy('eventos.hasta', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResult();

        return [
            'eventos' => $eventos ?: [],
            'total' => $total,
            'totalPages' => $totalPages,
            'page' => $page,
            'perPage' => $perPage,
        ];
    }

    /**
     * Obtiene los próximos eventos visibles para portada.
     * @return array
     */
    public function getProximosEventos(): array
    {
        $hoy = \CodeIgniter\I18n\Time::createFromDate()->toDateString();

        $db = \Config\Database::connect();
        $result = $db->table('neventos AS eventos')
            ->select($this->getCardSelect())
            ->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo')
            ->where('eventos.visible', 1)
            ->where('eventos.hasta >=', $hoy)
            ->orderBy('eventos.hasta', 'DESC')
            ->get()
            ->getResult();

        return $result ? $result : [];
    }

    /**
     * Obtiene un evento por ID con su tipo asociado
     * @param int $id ID del evento
     * @return object|null
     */
    public function getEventoConTipo(int $id)
    {
        $db = \Config\Database::connect();
        $result = $db->table('neventos AS eventos')
            ->select('eventos.*, tiposeventos.eventonombre AS grupo')
            ->join('tiposeventos', 'tiposeventos.eventotipo = eventos.eventotipo')
            ->where('eventos.id', $id)
            ->get()
            ->getRow();

        return $result;
    }

    /**
     * Obtiene las fotos asociadas a un evento
     * @param int $id ID del evento
     * @return array
     */
    public function getEventoFotos(int $id): array
    {
        $dirPath = FCPATH . 'imgEventos/ev_' . $id;

        if (!is_dir($dirPath)) {
            return [];
        }

        $imgs = scandir($dirPath);
        $fotos = [];
        $excludedFiles = ['.', '..', 'Cartel.jpg', 'cartel.jpg'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        foreach ($imgs as $img) {
            if (in_array($img, $excludedFiles, true)) {
                continue;
            }

            $filePath = $dirPath . DIRECTORY_SEPARATOR . $img;
            if (!is_file($filePath)) {
                continue;
            }

            $extension = strtolower(pathinfo($img, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions, true)) {
                continue;
            }

            $fotos[] = $img;
        }

        return $fotos;
    }

    /**
     * Obtiene la ruta del PDF del evento si existe
     * @param int $id ID del evento
     * @return string|false
     */
    public function getEventoPdf(int $id)
    {
        $pdfPath = 'pdfEventos/pdf_' . $id . '.pdf';

        return file_exists($pdfPath) ? $pdfPath : false;
    }

    /**
     * Valida si el evento admite inscripciones
     * @param object $evento Objeto del evento
     * @return bool
     */
    public function admiteInscripcion($evento): bool
    {
        if ($evento->inscripcion <= 0) {
            return false;
        }

        $hoy = date('Y-m-d');
        $evento_state = $this->getEstadoEvento($evento->desde, $evento->hasta);
        $permite_inscripcion = ($evento_state === 'PROXIMAMENTE' || $evento_state === 'EN CURSO');

        return ($hoy >= $evento->desde_inscripcion && $hoy <= $evento->hasta_inscripcion && $permite_inscripcion);
    }

    /**
     * Obtiene el estado actual del evento
     * @param string $desde Fecha de inicio
     * @param string $hasta Fecha de fin
     * @return string Estado del evento
     */
    protected function getEstadoEvento(string $desde, string $hasta): string
    {
        // Aquí usamos la función helpers existente si está disponible
        if (function_exists('uti_estado_evento')) {
            return uti_estado_evento($desde, $hasta);
        }

        $hoy = date('Y-m-d');
        if ($hoy < $desde) {
            return 'PROXIMAMENTE';
        } elseif ($hoy >= $desde && $hoy <= $hasta) {
            return 'EN CURSO';
        } else {
            return 'FINALIZADO';
        }
    }

}

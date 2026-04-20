<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<?php
$sort = $sort ?? 'nombre';
$dir = $dir ?? 'asc';
$q = $q ?? '';
$calidad = $calidad ?? '';
$perPage = $perPage ?? 25;
$page = $page ?? 1;
$totalPaginas = $totalPaginas ?? 1;
$totalFiltrado = $totalFiltrado ?? 0;
$desde = $desde ?? 0;
$hasta = $hasta ?? 0;
$calidades = $calidades ?? [];

$buildUrl = static function (array $params = []) use ($sort, $dir, $q, $calidad, $perPage, $page): string {
    $base = [
        'sort' => $sort,
        'dir' => $dir,
        'q' => $q,
        'calidad' => $calidad,
        'perPage' => $perPage,
        'page' => $page,
    ];

    return base_url('control/contactos') . '?' . http_build_query(array_merge($base, $params));
};

$sortArrow = static function (string $column) use ($sort, $dir): string {
    if ($sort !== $column) {
        return ' ⇅';
    }

    return $dir === 'asc' ? ' ▲' : ' ▼';
};

$nextDir = static function (string $column) use ($sort, $dir): string {
    if ($sort !== $column) {
        return 'asc';
    }

    return $dir === 'asc' ? 'desc' : 'asc';
};
?>

<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Nuevo Contacto" class="btn btn-primary btn-sm"
                href="<?php echo base_url('control/contactos/nuevo'); ?>" role="button"><i class="bi bi-check-lg"></i> Agregar nuevo contacto</a>
        </div>
    </div>

    <form method="get" action="<?= base_url('control/contactos') ?>" class="row g-2 align-items-end mb-2" id="contactos-filter-form">
        <div class="col-12 col-md-6 col-lg-4">
            <label for="q" class="form-label mb-1">Buscar</label>
            <input type="search" id="q" name="q" class="form-control form-control-sm" value="<?= esc($q) ?>"
                placeholder="Nombre, email, DNI o teléfono">
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <label for="perPage" class="form-label mb-1">Filas</label>
            <select id="perPage" name="perPage" class="form-select form-select-sm">
                <?php foreach ([10, 15, 25, 50, 100] as $rows): ?>
                <option value="<?= $rows ?>" <?= $perPage == $rows ? 'selected' : '' ?>><?= $rows ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-3 col-lg-3">
            <label for="calidad" class="form-label mb-1">Calidad</label>
            <select id="calidad" name="calidad" class="form-select form-select-sm">
                <option value="">Todas las calidades</option>
                <?php foreach ($calidades as $calidadKey => $calidadLabel): ?>
                <option value="<?= esc($calidadKey) ?>" <?= $calidad === $calidadKey ? 'selected' : '' ?>>
                    <?= esc($calidadLabel) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="hidden" name="sort" value="<?= esc($sort) ?>">
        <input type="hidden" name="dir" value="<?= esc($dir) ?>">
    </form>

    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="contactos-table" data-native-skip="1">
                <thead>
                    <tr>
                        <th scope="col">
                            <a href="<?= esc($buildUrl(['sort' => 'nombre', 'dir' => $nextDir('nombre'), 'page' => 1])) ?>" class="text-decoration-none text-reset">
                                Contacto<?= $sortArrow('nombre') ?>
                            </a>
                        </th>
                        <th scope="col">
                            <a href="<?= esc($buildUrl(['sort' => 'email', 'dir' => $nextDir('email'), 'page' => 1])) ?>" class="text-decoration-none text-reset">
                                Contactar<?= $sortArrow('email') ?>
                            </a>
                        </th>
                        <th scope="col">Calidad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($contactos ?? []) as $contacto): ?>
                    <?php
                        $calidades = [];
                        if (!empty($contacto->mailing)) {
                            $calidades[] = 'Correos';
                        }
                        if (!empty($contacto->invitaciones)) {
                            $calidades[] = 'Invitaciones';
                        }
                        if (!empty($contacto->socio)) {
                            $calidades[] = 'Socio';
                        }
                        if (!empty($contacto->alumno)) {
                            $calidades[] = 'Alumno';
                        }
                        if (!empty($contacto->pdalumno)) {
                            $calidades[] = 'Padre/Madre';
                        }
                        if (!empty($contacto->pintor)) {
                            $calidades[] = 'Pintor';
                        }
                        if (!empty($contacto->dtaller)) {
                            $calidades[] = 'Talleres';
                        }
                        if (!empty($contacto->amigo)) {
                            $calidades[] = 'Amigo';
                        }
                    ?>
                    <tr>
                        <td>
                            <small>
                                <?= esc(trim(($contacto->nombre ?? '') . ' ' . ($contacto->apellidos ?? ''))) ?>
                                <?php if (!empty($contacto->dni)): ?>
                                <br>DNI: <?= esc($contacto->dni) ?>
                                <?php endif; ?>
                            </small>
                        </td>
                        <td>
                            <small>
                                <?= esc(trim($contacto->email ?? '')) ?>
                                <br>Tel: <?= esc(trim($contacto->telefono ?? '')) ?>
                            </small>
                        </td>
                        <td>
                            <?php if (!empty($calidades)): ?>
                            <ul>
                                <?php foreach ($calidades as $calidad): ?>
                                <li class="calidades"><small class="form-text text-muted"><?= esc($calidad) ?></small></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="text-end ico-acciones">
                                <a title="Editar" aria-label="Editar" class="btn btn-success btn-sm bi-pencil" href="<?= base_url('control/contactos/editar/' . $contacto->id) ?>"><span class="visually-hidden">Editar</span></a>
                                <form style="display: inline;" action="<?= base_url('control/contactos/' . $contacto->id) ?>" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" title="Borrar" aria-label="Borrar" class="btn btn-danger btn-sm bi-eraser" onclick="return confirm('¿ Confirma el borrado ?');"><span class="visually-hidden">Borrar</span></button>
                                </form>
                                <a title="Historia" aria-label="Historia" class="btn btn-secondary btn-sm bi-clock-history" href="<?= base_url('control/contactos/historia/' . $contacto->id) ?>"><span class="visually-hidden">Historia</span></a>
                                <a title="Inscribir" aria-label="Inscribir" class="btn btn-warning btn-sm bi-pencil-square" href="<?= base_url('control/inscripcionManual/' . $contacto->id) ?>"><span class="visually-hidden">Inscribir</span></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2">
            <small class="text-muted">
                <?= $totalFiltrado > 0 ? 'Mostrando ' . $desde . '-' . $hasta . ' de ' . $totalFiltrado : 'Sin resultados' ?>
            </small>
            <div class="btn-group btn-group-sm" role="group" aria-label="Paginación contactos">
                <a class="btn btn-outline-secondary <?= $page <= 1 ? 'disabled' : '' ?>" href="<?= esc($buildUrl(['page' => 1])) ?>">&laquo;</a>
                <a class="btn btn-outline-secondary <?= $page <= 1 ? 'disabled' : '' ?>" href="<?= esc($buildUrl(['page' => max(1, $page - 1)])) ?>">&lsaquo;</a>
                <span class="btn btn-light disabled">Página <?= (int) $page ?> / <?= (int) $totalPaginas ?></span>
                <a class="btn btn-outline-secondary <?= $page >= $totalPaginas ? 'disabled' : '' ?>" href="<?= esc($buildUrl(['page' => min($totalPaginas, $page + 1)])) ?>">&rsaquo;</a>
                <a class="btn btn-outline-secondary <?= $page >= $totalPaginas ? 'disabled' : '' ?>" href="<?= esc($buildUrl(['page' => $totalPaginas])) ?>">&raquo;</a>
            </div>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('masJS'); ?>
<script>
(() => {
    const form = document.getElementById('contactos-filter-form');
    if (!form) {
        return;
    }

    const perPage = document.getElementById('perPage');
    const calidad = document.getElementById('calidad');
    const qInput = document.getElementById('q');

    if (perPage) {
        perPage.addEventListener('change', () => {
            form.submit();
        });
    }

    if (calidad) {
        calidad.addEventListener('change', () => {
            form.submit();
        });
    }

    if (qInput) {
        qInput.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            form.submit();
        });

        qInput.addEventListener('input', () => {
            if ((qInput.value || '').trim() !== '') {
                return;
            }

            form.submit();
        });
    }
})();
</script>
<?php echo $this->endSection(); ?>

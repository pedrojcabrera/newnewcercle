<?= $this->extend('admin/plantillas/layout'); ?>
<?= $this->section('contenido'); ?>
<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Nuevo Evento" class="btn btn-primary btn-sm"
                href="<?= base_url('control/eventos/nuevo'); ?>" role="button"><i class="bi bi-check-lg"></i> Agregar nuevo evento</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th>Cartel</th>
                        <th>Evento</th>
                        <th class="text-center">Desde/Hasta</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                    <?php $texto  = substr(trim($evento->texto), 0, 225) . "..."; ?>
                    <tr class="align-middle">
                        <td class="pe-4">
                            <img class="cartel_lista"
                                src="<?= base_url("imgEventos/ev_" . $evento->id . "/cartel.jpg") ?>"
                                onerror="this.onerror=null;this.src='<?= base_url("imgEventos/eventos.jpg") ?>'"
                                width="100" alt="">
                        </td>
                        <td>
                            <h5><?php echo strtoupper($evento->titulo); ?></h5>
                            <h6 class="text-center"><?php echo ucfirst(strtolower($evento->grupo)); ?></h6>
                            <?php if ($evento->evento_cerrado): ?>
                            <h6 class="text-center text-danger">Cerrado</h6>
                            <?php endif; ?>
                            <p><?php echo $texto; ?></p>
                        </td>
                        <td class="text-center">
                            <small>
                                <?php echo date('d/m/Y', strtotime($evento->desde)); ?>
                                <hr class="mt-0 mb-0">
                                <?php echo date('d/m/Y', strtotime($evento->hasta)); ?>
                            </small>
                        </td>
                        <td class="text-end enLista ico-acciones">
                            <a name="" title="Editar" id="" class="btn btn-success btn-sm"
                                aria-label="Editar"
                                href="<?= base_url('control/eventos/editar/' . $evento->id); ?>"><i class="bi bi-pencil-fill"></i></a>
                            <form style="display: inline;" action="<?= base_url('control/eventos/' . $evento->id); ?>"
                                method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Borrar" class="btn btn-danger btn-sm"
                                    aria-label="Borrar"
                                    onclick="return confirm('¿ Confirma el borrado ?');"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                            <a name="" title="Ver Fotos" id="" class="btn btn-secondary btn-sm"
                                aria-label="Ver fotos"
                                href="<?php echo base_url('control/fotos/' . $evento->id); ?>"><i class="bi bi-image-fill"></i></a>

                            <?php if ($evento->inscripcion_invitacion): ?>
                            <?php if(!$evento->evento_cerrado): ?>
                            <a title="Invitación masiva" class="btn btn-info btn-sm"
                                aria-label="Invitación masiva"
                                href="<?= base_url('control/eventos/cartear/' . $evento->id); ?>"><i class="bi bi-envelope-fill"></i></a>
                            <?php endif; ?>
                            <a title="Lista de Invitados" class="btn btn-secondary btn-sm"
                                aria-label="Lista de invitados"
                                href="<?= base_url('control/eventos/invitados/' . $evento->id); ?>"><i class="bi bi-people-fill"></i></a>
                            <?php endif; ?>


                            <?php if ($evento->inscripcion || ($evento->inscripcion_invitacion && ($evento->alumno or $evento->amigo or $evento->pdalumno or $evento->pintor or $evento->socio or $evento->dtaller))) : ?>
                            <a title="Lista de Inscritos" class="btn btn-primary btn-sm"
                                aria-label="Lista de inscritos"
                                href="<?php echo base_url('control/inscritos/' . $evento->id); ?>"><i class="bi bi-card-checklist"></i></a>
                            <a title="Lista de Espera" class="btn btn-warning btn-sm"
                                aria-label="Lista de espera"
                                href="<?php echo base_url('control/enEspera/' . $evento->id); ?>"><i class="bi bi-hourglass-split"></i></a>
                            <?php endif; ?>


                            <?php if ($evento->inscripcion): ?>
                            <?php if(!$evento->evento_cerrado): ?>
                            <?php if (strtotime($hoy) >= strtotime($evento->desde_inscripcion) and strtotime($hoy) <= strtotime($evento->hasta_inscripcion)): ?>
                            <a title="Inscripción manual" class="btn btn-primary btn-sm"
                                aria-label="Inscripción manual"
                                href="<?php echo base_url('control/inscripcionManual'); ?>"><i class="bi bi-person-plus-fill"></i></a>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="d-flex justify-content-center gap-2 mt-3">
        <?php if ($page > 1): ?>
        <a href="<?= base_url('control/eventos?page=1') ?>" class="btn btn-sm btn-outline-secondary">Primero</a>
        <a href="<?= base_url('control/eventos?page=' . ($page - 1)) ?>" class="btn btn-sm btn-outline-secondary">Anterior</a>
        <?php endif; ?>

        <span class="btn btn-sm btn-light" disabled>Página <?= $page ?> de <?= $totalPages ?> (<?= $total ?> eventos)</span>

        <?php if ($page < $totalPages): ?>
        <a href="<?= base_url('control/eventos?page=' . ($page + 1)) ?>" class="btn btn-sm btn-outline-secondary">Siguiente</a>
        <a href="<?= base_url('control/eventos?page=' . $totalPages) ?>" class="btn btn-sm btn-outline-secondary">Último</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>

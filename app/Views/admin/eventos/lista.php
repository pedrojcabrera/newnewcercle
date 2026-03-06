<?= $this->extend('admin/plantillas/layout'); ?>
<?= $this->section('contenido'); ?>
<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Crear" class="btn btn-primary btn-sm bi-person-plus"
                href="<?= base_url('control/eventos/nuevo'); ?>" role="button">
                Nuevo Evento</a>
        </div>
        <div class="boton-cancelar">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
                href="<?php echo base_url('dashboard');?>" role="button" title="Cancelar">
                Cancelar</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cartel</th>
                        <th>Evento</th>
                        <th class="text-center">Desde/Hasta</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                    <?php $cartel = base_url("imgEventos/ev_" . $evento->id . "/cartel.jpg"); ?>
                    <?php $cartel = (file_exists("imgEventos/ev_$evento->id/cartel.jpg") ? $cartel : base_url("imgEventos/eventos.jpg")); ?>
                    <?php $texto  = substr(trim($evento->texto), 0, 225) . "..."; ?>
                    <tr class="align-middle">
                        <td class="id_lista"><span><?php echo $evento->id; ?></span></td>
                        <td>
                            <img class="cartel_lista" src="<?php echo $cartel; ?>" width="100" alt="">
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
                            <a name="" title="Editar" id="" class="btn btn-success btn-sm bi-pencil mi-boton"
                                href="<?= base_url('control/eventos/editar/' . $evento->id); ?>"> Editar</a>
                            <form style="display: inline;" action="<?= base_url('control/eventos/' . $evento->id); ?>"
                                method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Borrar" class="btn btn-danger btn-sm bi-eraser mi-boton"
                                    onclick="return confirm('¿ Confirma el borrado ?');"> Borrar</button>
                            </form>
                            <a name="" title="Ver Fotos" id="" class="btn btn-secondary btn-sm bi-camera mi-boton"
                                href="<?php echo base_url('control/fotos/' . $evento->id); ?>"> Fotos</a>

                            <?php if ($evento->inscripcion_invitacion): ?>
                            <?php if(!$evento->evento_cerrado): ?>
                            <a title="Invitación masiva" class="btn btn-info btn-sm bi-envelope mi-boton"
                                href="<?= base_url('control/eventos/cartear/' . $evento->id); ?>"> Cartear</a>
                            <?php endif; ?>
                            <a title="Lista de Invitados" class="btn btn-secondary btn-sm bi-people mi-boton"
                                href="<?= base_url('control/eventos/invitados/' . $evento->id); ?>"> Invitados</a>
                            <?php endif; ?>


                            <?php if ($evento->inscripcion || ($evento->inscripcion_invitacion && ($evento->alumno or $evento->amigo or $evento->pdalumno or $evento->pintor or $evento->socio or $evento->dtaller))) : ?>
                            <a title="Lista de Inscritos" class="btn btn-primary btn-sm bi-card-checklist mi-boton"
                                href="<?php echo base_url('control/inscritos/' . $evento->id); ?>">
                                Inscritos</a>
                            <a title="Lista de Espera" class="btn btn-warning btn-sm bi-hourglass mi-boton"
                                href="<?php echo base_url('control/enEspera/' . $evento->id); ?>">
                                En Espera</a>
                            <?php endif; ?>


                            <?php if ($evento->inscripcion): ?>
                            <?php if(!$evento->evento_cerrado): ?>
                            <?php if (strtotime($hoy) >= strtotime($evento->desde_inscripcion) and strtotime($hoy) <= strtotime($evento->hasta_inscripcion)): ?>
                            <a title="Inscripción manual" class="btn btn-primary btn-sm bi-person-plus mi-boton"
                                href="<?php echo base_url('control/inscripcionManual'); ?>">
                                Inscribir</a>
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
</div>


<?= $this->endSection(); ?>
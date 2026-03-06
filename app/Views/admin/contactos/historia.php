<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>



<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
        </div>
        <div class="boton-cancelar">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
                href="<?= base_url('control/contactos'); ?>" role="button" title="Cancelar">
                Cancelar</a>
        </div>
    </div>

    <h1 class="text-center mt-2 mb-3 text-success"><?= $contacto ?></h1>

    <div class="card-body">
        <div class="table-responsive-sm">
            <h3 class="text-center">Actualmente en Lista de Espera</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable"> -->
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Evento</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($esperando as $enespera): ?>
                    <tr class="align-middle">
                        <td><small><?= $enespera['id'] ?></small></td>
                        <td class="text-wrap"><?= strtoupper($enespera['evento']) ?></td>
                        <td class="text-nowrap"><small><?= $enespera['inicio'] ?></small></td>
                        <td class="text-nowrap"><small><?= $enespera['fin'] ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body mt-3">
        <div class="table-responsive-sm">
            <h3 class="text-center">Invitaciones a Eventos</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable"> -->
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Evento</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Invitación</th>
                        <th class="text-center" scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $evento): ?>
                    <tr class="align-middle">
                        <td><small><?= $evento['id'] ?></small></td>
                        <td class="text-wrap"><?= strtoupper($evento['evento']) ?></td>
                        <td class="text-nowrap"><small><?= $evento['inicio'] ?></small></td>
                        <td class="text-nowrap"><small><?= $evento['fin'] ?></small></td>
                        <td class="text-center"><small><?= $evento['invitacion'] ?></small></td>
                        <td class="text-center fw-bold text-<?=$evento['color']?>"><?=$evento['estado']?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body mt-3">
        <div class="table-responsive-sm">
            <h3 class="text-center">Inscripciones a Eventos</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable"> -->
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Evento</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Inscripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscritos as $inscrito): ?>
                    <tr class="align-middle">
                        <td><small><?= $inscrito['id'] ?></small></td>
                        <td class="text-wrap"><?= strtoupper($inscrito['evento']) ?></td>
                        <td class="text-nowrap"><small><?= $inscrito['inicio'] ?></small></td>
                        <td class="text-nowrap"><small><?= $inscrito['fin'] ?></small></td>
                        <td class="text-center"><small><?= $inscrito['inscripcion'] ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body mt-3">
        <div class="table-responsive-sm">
            <h3 class="text-center">Correos genéricos</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable2"> -->
                <thead>
                    <tr>
                        <th scope="col">Asunto</th>
                        <th scope="col">Fecha</th>
                        <th class="text-center" scope="col">Destinos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($correos as $correo): ?>
                    <tr class="align-middle">
                        <td class="text-nowrap"><?= strtoupper($correo['asunto']) ?></td>
                        <td class="text-nowrap"><?= $correo['fecha'] ?></td>
                        <td class="text-center"><?= $correo['destinos'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>
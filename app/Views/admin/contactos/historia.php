<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>



<div class="container">

    <h1 class="text-center mt-2 mb-3 text-success"><?= esc($contacto) ?></h1>

    <div class="card-body">
        <div class="table-responsive-sm">
            <h3 class="text-center">Actualmente en Lista de Espera</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable"> -->
                <thead class="table-info">
                    <tr>
                        <th scope="col">Evento</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($esperando as $enespera): ?>
                    <tr class="align-middle">
                        <td class="text-wrap"><?= esc(strtoupper($enespera['evento'])) ?></td>
                        <td class="text-nowrap"><small><?= esc($enespera['inicio']) ?></small></td>
                        <td class="text-nowrap"><small><?= esc($enespera['fin']) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body mt-5">
        <div class="table-responsive-sm">
            <h3 class="text-center">Invitaciones a Eventos</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable"> -->
                <thead class="table-warning">
                    <tr>
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
                        <td class="text-wrap"><?= esc(strtoupper($evento['evento'])) ?></td>
                        <td class="text-nowrap"><small><?= esc($evento['inicio']) ?></small></td>
                        <td class="text-nowrap"><small><?= esc($evento['fin']) ?></small></td>
                        <td class="text-center"><small><?= esc($evento['invitacion']) ?></small></td>
                        <td class="text-center fw-bold text-<?=esc($evento['color'])?>"><?=esc($evento['estado'])?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body mt-5">
        <div class="table-responsive-sm">
            <h3 class="text-center">Inscripciones a Eventos</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable"> -->
                <thead class="table-success">
                    <tr>
                        <th scope="col">Evento</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col">Inscripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscritos as $inscrito): ?>
                    <tr class="align-middle">
                        <td class="text-wrap"><?= esc(strtoupper($inscrito['evento'])) ?></td>
                        <td class="text-nowrap"><small><?= esc($inscrito['inicio']) ?></small></td>
                        <td class="text-nowrap"><small><?= esc($inscrito['fin']) ?></small></td>
                        <td class="text-center"><small><?= esc($inscrito['inscripcion']) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body mt-5">
        <div class="table-responsive-sm">
            <h3 class="text-center">Correos genéricos</h3>
            <table class="table table-striped mt-3">
                <!-- id="datatable2"> -->
                <thead class="table-secondary">
                    <tr>
                        <th scope="col">Asunto</th>
                        <th scope="col">Fecha</th>
                        <th class="text-center" scope="col">Destinos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($correos as $correo): ?>
                    <tr class="align-middle">
                        <td class="text-nowrap"><?= esc(strtoupper($correo['asunto'])) ?></td>
                        <td class="text-nowrap"><?= esc($correo['fecha']) ?></td>
                        <td class="text-center"><?= esc($correo['destinos']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

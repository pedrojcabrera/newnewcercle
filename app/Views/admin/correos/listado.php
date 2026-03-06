<?= $this->extend('admin/plantillas/layout') ?>
<?= $this->section('contenido') ?>

<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
        </div>
        <div class="boton-cancelar">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
            href="<?= base_url('control/correos'); ?>" role="button"
            title="Cancelar"> Cancelar</a>
        </div>
    </div>
</div>
<div class="container mt-0 card">
    <h2 class="w-100 text-center mb-3"><?=$correo->asunto?></h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Enviados</th>
                <th class="text-center">Errores</th>
                <th class="text-center">Destinos</th>
                <th class="text-center">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumenes as $resumen): ?>
            <tr>
                <td class="text-center"><?= $resumen->id ?></td>
                <td class="text-center"><?= $resumen->fechahora ?></td>
                <td class="text-center"><?= $resumen->enviados ?></td>
                <td class="text-center"><?= $resumen->errores ?></td>
                <td class="text-center"><?= $destinos[$resumen->id] ?></td>
                <td class="text-center">
                    <button class="btn btn-primary btn-sm bi-eyeglasses" type="button" data-bs-toggle="collapse" data-bs-target="#resumen<?= $resumen->id ?>" aria-expanded="false" 
             aria-controls="resumen<?= $resumen->id ?>"> detalles</button>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <div id="accordion">
                        <div class="collapse" id="resumen<?= $resumen->id ?>" data-bs-parent="#accordion">
                            <table class="table table-sm table-bordered mt-2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Contacto</th>
                                        <th>Correo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($detalles[$resumen->id] as $detalle): ?>
                                    <tr>
                                        <td><?= $detalle->id ?></td>
                                        <td><?= $nombres[$detalle->id] ?></td>
                                        <td><?= $emails[$detalle->id] ?></td>
                                        <td><?= $detalle->error ? 'error' : 'ok' ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="container mt-0">
    <div class="botones-superiores">
        <div class="boton-agregar">
        </div>
        <div class="boton-cancelar">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
            href="<?= base_url('control/correos'); ?>" role="button"
            title="Cancelar"> Cancelar</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const botones = document.querySelectorAll("[data-bs-toggle='collapse']");

        botones.forEach(boton => {
            boton.addEventListener("click", function () {
                const target = document.querySelector(this.getAttribute("data-bs-target"));

                // Cierra todos los acordeones excepto el actual
                document.querySelectorAll(".collapse.show").forEach(collapse => {
                    if (collapse !== target) {
                        new bootstrap.Collapse(collapse, { toggle: false }).hide();
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>
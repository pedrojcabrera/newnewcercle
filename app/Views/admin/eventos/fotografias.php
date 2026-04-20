<?= $this->extend('admin/plantillas/layout') ?>

<?= $this->section('contenido') ?>

<?php
 $evento_nombre = $evento->titulo;
?>
<div class="grupo-fotos">
    <h2 class="titulo">
        <?= $evento->titulo ?>
    </h2>

    <?php if (session()->getFlashdata('errors')): ?>
    <div style="color: red;">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="container subir-fotos">
        <form action="<?= base_url('control/fotos/' . $evento->id) ?>" method="post"
            enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group">
                <input class="form-control" type="file" name="images[]" id="archivoInput" multiple>
            </div>
            <hr>
            <button class="btn btn-primary btn-sm" id="btnEnviar" disabled type="submit"><i class="bi bi-camera-fill"></i> Subir imagen seleccionada</button>
        </form>
    </div>
</div>
<div class="btn-regresar">
    <a class="btn btn-success btn-sm"
        href="<?= base_url('control/eventos') ?>" title="Regresar a la lista de Eventos"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
</div>
<div class="container contenedor-4">
    <!-- Modal -->
    <div id="modal" class="modal">
        <span class="close">&times;</span>
        <img id="imagenAmpliada">
    </div>

    <?php foreach ($fotos as $foto): ?>
    <?php if (preg_match('/\.(jpg)$/i', $foto)) : ?>

    <div class="card item-0 item-4">
        <figure>
            <img src="<?= base_url('imgEventos/ev_' . $evento->id . "/" . $foto) ?>" alt="">
            <hr>
            <figcaption>
                <form action="<?= base_url('control/fotos/' . $evento->id . '/' . rawurlencode($foto)) ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('\u00bf Confirma el borrado ?');"><i class="bi bi-trash3-fill"></i> Eliminar Imagen</button>
                </form>
            </figcaption>
        </figure>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

</div>

<script>
document.getElementById("archivoInput").addEventListener("change", function() {
    let boton = document.getElementById("btnEnviar");
    // Habilita el botón solo si hay archivos seleccionados
    boton.disabled = this.files.length === 0;
});
</script>


<?= $this->endSection() ?>

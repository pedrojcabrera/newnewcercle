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
            <div class="form-group">
                <input class="form-control" type="file" name="images[]" id="archivoInput" multiple>
            </div>
            <hr>
            <button class="btn btn-primary btn-sm bi-camera" id="btnEnviar" disabled type="submit"> Añadir
                Fotografías</button>
        </form>
    </div>
</div>
<div class="btn-regresar">
    <a class="btn btn-success btn-sm bi-card-list"
        href="<?= base_url('control/eventos') ?>"> Regresar a la lista de Eventos</a>
</div>
<div class="container contenedor-4">
    <!-- Modal -->
    <div id="modal" class="modal">
        <span class="close">&times;</span>
        <img id="imagenAmpliada">
    </div>

    <?php foreach ($fotos as $foto): ?>
    <div class="card item-0 item-4">
        <figure>
            <img src="<?= base_url('imgEventos/ev_' . $evento->id . "/" . $foto) ?>" alt="">
            <hr>
            <figcaption>
                <a href="<?= base_url('control/fotos/' . $evento->id . '/' . $foto) ?>"
                    class="btn btn-danger btn-sm bi-trash3" onclick="return confirm('¿ Confirma el borrado ?');"> Quitar
                    Foto</a>
            </figcaption>
        </figure>
    </div>
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
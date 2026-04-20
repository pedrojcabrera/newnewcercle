<?= $this->extend('galeristas/layout') ?>
<?= $this->section('contenido') ?>

<?php
    $foto = 'sinfoto.jpg';
    $fotoUser = $data->user . '.jpg';
    if (file_exists('fotosUsuarios/' . $fotoUser)) {
        $foto = $fotoUser;
    }
?>

<div class="galerista-header">
    <img src="<?= base_url('fotosUsuarios/' . $foto) ?>" alt="<?= esc($data->nombre) ?>">
    <div>
        <h4><?= esc($data->nombre) ?></h4>
        <small class="text-muted">Dashboard de obras</small>
    </div>
</div>

<div class="container lista_cuadros">
   <?php foreach ($obras as $obra): ?>

   <div class=" card tarjeta_cuadro" style="width: 18rem;">
      <img src="<?= base_url('galerias/' . $obra->id_user . '/' . $obra->id . '.jpg') ?>"
         class="card-img-top obra-preview-trigger"
         alt="<?= esc($obra->titulo) ?>"
         data-bs-toggle="modal"
         data-bs-target="#obraImageModal"
         data-obra-src="<?= base_url('galerias/' . $obra->id_user . '/' . $obra->id . '.jpg') ?>"
         data-obra-alt="<?= esc($obra->titulo) ?>">
      <div class="card-body cuerpo-tarjeta">
         <h5 class="card-title">
            <?= $obra->titulo ?>
         </h5>
         <h6><?= ($obra->ano > 0) ? '(Año ' . $obra->ano . ') ' : ''; ?></h6>
         <?php if (! empty($obra->comentarios)): ?>
         <p class="comentarios">
            <?= nl2br($obra->comentarios) ?>
         </p>
         <hr>
         <?php endif; ?>
         <p>
            <span class="datosCuadro">Técnica: </span> <?= $obra->tecnica ?><br>
            <span class="datosCuadro">Soporte: </span> <?= $obra->soporte ?><br>
            <span class="datosCuadro">Medidas: </span> <?= $obra->medidas ?>
         </p>
         <?php if ($obra->precio > 0): ?>
         <p>
            <span class="datosCuadro">Precio: </span> <?= $obra->precio ?> <small>€</small>
         </p>
         <?php if (! empty($obra->premios)): ?>
         <hr>
         <p><span class="datosCuadro">Premios:<br></span>
            <?= nl2br($obra->premios) ?>
         </p>
         <?php endif; ?>
         <?php endif; ?>
         <div class="botones-pie">
            <a class="boton-nuevo btn btn-success btn-sm bi-pencil"
               href="<?= base_url('galeristas/editar/' . $obra->id) ?>">
               Editar</a>
            <a class="boton-nuevo btn btn-danger btn-sm bi-trash3" href="<?= base_url('galeristas/quitar/' . $obra->id) ?>"
               onclick="return confirm('¿Estás seguro de quitar esta obra de tu galería?')">
               Quitar</a>
         </div>
      </div>
   </div>

   <?php endforeach; ?>

</div>

<div class="modal fade" id="obraImageModal" tabindex="-1" aria-labelledby="obraImageModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-fullscreen modal-dialog-centered">
      <div class="modal-content obra-modal-content">
         <div class="modal-header border-0">
            <h5 class="modal-title" id="obraImageModalLabel">Vista ampliada</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
         </div>
         <div class="modal-body obra-modal-body">
            <img id="obraImageModalImg" class="obra-modal-img" src="" alt="">
         </div>
      </div>
   </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('masJS') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
   const obraModal = document.getElementById('obraImageModal');
   if (!obraModal) {
      return;
   }

   const modalImg = document.getElementById('obraImageModalImg');
   const modalTitle = document.getElementById('obraImageModalLabel');

   obraModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      if (!trigger) {
         return;
      }

      const src = trigger.getAttribute('data-obra-src') || '';
      const alt = trigger.getAttribute('data-obra-alt') || 'Obra';
      modalImg.src = src;
      modalImg.alt = alt;
      modalTitle.textContent = alt;
   });

   obraModal.addEventListener('hidden.bs.modal', function () {
      modalImg.src = '';
      modalImg.alt = '';
   });
});
</script>
<?= $this->endSection() ?>

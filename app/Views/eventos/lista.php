<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container contenedor-4">
   <?php foreach($eventos as $evento): ?>
   <a href="<?= base_url('eventos/' . $evento->id) ?>" class="item-0 item-4">
      <img src="<?= base_url('imgEventos/ev_' . $evento->id . '/cartel.jpg') ?>"
         onerror="this.onerror=null;this.src='<?= base_url('imgEventos/eventos.jpg') ?>'"
         alt="Cartel del evento <?= esc($evento->titulo) ?>">
      <h6 class="evento-titulo-4"><?= esc(strtoupper($evento->titulo)) ?></h6>
      <p class="desde-hasta-4">Desde el <?= esc(uti_fecha($evento->desde)) ?> hasta el <?= esc(uti_fecha($evento->hasta)) ?></p>
      <p class="estado-4 <?= uti_quita_(uti_estado_evento($evento->desde,$evento->hasta))?>">
         <?= esc(strtoupper($evento->grupo) . '   -   ' . uti_estado_evento($evento->desde, $evento->hasta)) ?>
      </p>
   </a>
   <?php endforeach; ?>
</div>

<?php if (($totalPages ?? 1) > 1): ?>
<div class="container mt-4">
   <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
      <?php if (($page ?? 1) > 1): ?>
      <a href="<?= base_url('eventos?page=1') ?>" class="btn btn-sm btn-outline-secondary">Primera</a>
      <a href="<?= base_url('eventos?page=' . (($page ?? 1) - 1)) ?>" class="btn btn-sm btn-outline-secondary">Anterior</a>
      <?php endif; ?>

      <span class="btn btn-sm btn-light disabled">Página <?= (int) ($page ?? 1) ?> de <?= (int) ($totalPages ?? 1) ?> (<?= (int) ($total ?? 0) ?> eventos)</span>

      <?php if (($page ?? 1) < ($totalPages ?? 1)): ?>
      <a href="<?= base_url('eventos?page=' . (($page ?? 1) + 1)) ?>" class="btn btn-sm btn-outline-secondary">Siguiente</a>
      <a href="<?= base_url('eventos?page=' . ($totalPages ?? 1)) ?>" class="btn btn-sm btn-outline-secondary">Última</a>
      <?php endif; ?>
   </div>
</div>
<?php endif; ?>

<?= $this->endSection()?>

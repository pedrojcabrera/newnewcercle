<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container contenedor-4">
   <!--"container_ultimos">-->
   <?php foreach($eventos as $evento): ?>
   <a href="<?= base_url('eventos/' . $evento->id) ?>" class="item-0 item-3">
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

<?= $this->endSection()?>

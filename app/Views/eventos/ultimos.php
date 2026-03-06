<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container contenedor-4">
   <!--"container_ultimos">-->
   <?php foreach($eventos as $evento): ?>
   <a href="/eventos/<?=$evento->id?>" class="item-0 item-3">
      <?php $cartel = file_exists('imgEventos/ev_'.$evento->id.'/cartel.jpg') ? 'imgEventos/ev_'.$evento->id.'/cartel.jpg' : 'imgEventos/eventos.jpg'?>
      <img src="<?=base_url($cartel)?>" alt="">
      <h6 class="evento-titulo-4"><?=strtoupper($evento->titulo)?></h6>
      <p class="desde-hasta-4">Desde el <?=uti_fecha($evento->desde)?> hasta el <?=uti_fecha($evento->hasta)?></p>
      <p class="estado-4 <?= uti_quita_(uti_estado_evento($evento->desde,$evento->hasta))?>">
         <?=strtoupper($evento->grupo) . "   -   " . uti_estado_evento($evento->desde,$evento->hasta)?>
      </p>
   </a>
   <?php endforeach; ?>
</div>

<?= $this->endSection()?>
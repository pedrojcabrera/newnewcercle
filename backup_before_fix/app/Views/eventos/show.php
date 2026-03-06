<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>
<div class="container contenedor-4">
    <div class="item-0 item-3">
        <?php $cartel = file_exists('imgEventos/ev_'.$evento->id.'/cartel.jpg') ? 'imgEventos/ev_'.$evento->id.'/cartel.jpg' : 'imgEventos/eventos.jpg'?>
        <img src="<?=base_url($cartel,$_SERVER['REQUEST_SCHEME']).'?v='.filemtime($cartel)?>" alt="">
        <h6 class="evento-titulo"><?=strtoupper($evento->titulo)?></h6>
        <p class="desde-hasta">Desde el <?=uti_fecha($evento->desde)?> hasta el <?=uti_fecha($evento->hasta)?></p>
        <p class="estado-4 <?= uti_quita_(uti_estado_evento($evento->desde,$evento->hasta))?>">
            <?=strtoupper($evento->grupo) . "   -   " . uti_estado_evento($evento->desde,$evento->hasta)?>
        </p>
        <?= ($pdf or $inscripcion) ? "<div class='botones'>" : "" ?>

        <?php if($pdf): ?>
        <a class="bases-4" href="<?=base_url($pdf)?>" target="_blank">Bases</a>
        <?php endif; ?>

        <?php if($evento->aforo_completo): ?>

        <p class="bases-4">Aforo completo</p>

        <?php else: ?>
        <?php if($inscripcion): ?>
        <a class="bases-4" href="<?=base_url('inscripcionManual/'.$evento->id)?>">Inscribirse</a>
        <?php endif; ?>

        <?php endif; ?>
        <?= ($pdf or $inscripcion) ? "</div>" : "" ?>

    </div>
</div>
<article class="sobre_nosotros">
    <?= $evento->texto ?>
</article>
<?php if(count($fotos)>0): ?>
<div class="container contenedor-4">
    <!-- Modal -->
    <div id="modal" class="modal">
        <span class="close">&times;</span>
        <img id="imagenAmpliada">
    </div>

    <?php foreach($fotos as $foto): ?>
    <div class="item-0 item-4 foto-4">
        <img src="<?=base_url('imgEventos/ev_'.$evento->id.'/'.$foto,$_SERVER['REQUEST_SCHEME'])?>" alt=""
            class="card-img-top">
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
const modal = document.getElementById("modal");
const imagenAmpliada = document.getElementById("imagenAmpliada");
const close = document.querySelector(".close");

document.querySelectorAll(".item-0 img").forEach(img => {
    img.addEventListener("click", () => {
        imagenAmpliada.src = img.src;
        modal.style.display = "flex";
    });
});

close.addEventListener("click", () => {
    modal.style.display = "none";
});

modal.addEventListener("click", (e) => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});
</script>


<?= $this->endSection()?>
<?= $this->extend('plantillas/layout'); ?>
<?= $this->section('contenido'); ?>

<?php if($cercle['visible'] ) : ?>
<div class="imagen_cercle">
    <img src="<?=base_url('recursos/imagenes/'.$cercle['banner'])?>" alt="">
</div>
<?php endif; ?>

<div class="container">

    <!-- Sobre Nosotros - Noticias -->
    <?php if (!empty(trim($cercle['noticia']))) : ?>
    <div class="sobre_nosotros enlaces">
        <h1 class="titulo">Noticias</h1>
        <p>
            <?= nl2br($cercle['noticia'])?>
        </p>
    </div>
    <?php endif; ?>

    <!-- Sobre Nosotros - Texto -->

    <div class="sobre_nosotros">
        <p>
            <?= nl2br($cercle['texto'])?>
        </p>
    </div>

    <!-- Aviso de que hay eventos para ver -->

    <?php if(count($eventos)>0) : ?>
    <div class="enlaces">
        <h1 class="titulo">Hoy te proponemos estos eventos</h1>
    </div>
    <?php endif; ?>

    <!-- Eventos en curso o próximos -->

    <div class="container contenedor-4">
        <?php foreach($eventos as $evento): ?>
        <a href="/eventos/<?=$evento->id?>" class="item-0 item-4">
            <?php $cartel = file_exists('imgEventos/ev_'.$evento->id.'/cartel.jpg') ? 'imgEventos/ev_'.$evento->id.'/cartel.jpg' : 'imgEventos/eventos.jpg'?>
            <img src="<?=base_url($cartel).'?v='.filemtime($cartel)?>" alt="">
            <h6 class="evento-titulo-4"><?=strtoupper($evento->titulo)?></h6>
            <p class="desde-hasta-4">Desde el <?=uti_fecha($evento->desde)?> hasta el <?=uti_fecha($evento->hasta)?></p>
            <p class="estado-4 <?= uti_quita_(uti_estado_evento($evento->desde,$evento->hasta))?>">
                <?=strtoupper($evento->grupo) . "   -   " . uti_estado_evento($evento->desde,$evento->hasta)?>
            </p>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Recomendaciones de enlaces de interés -->

    <div class="enlaces">
        <h1 class="titulo">Te recomendamos estos enlaces</h1>
        <ul>
            <?php foreach ($enlaces as $enlace) : ?>
            <li>
                <a href="<?=$enlace->enlace?>" target="_blank"><?=nl2br(trim($enlace->texto))?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?= $this->endSection(); ?>
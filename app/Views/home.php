<?= $this->extend('plantillas/layout'); ?>
<?= $this->section('contenido'); ?>

<?php if($cercle['visible'] ) : ?>
<div class="imagen_cercle">
    <img src="<?= base_url('recursos/imagenes/' . esc($cercle['banner'], 'url')) ?>" alt="Banner del Cercle d'Art de Foios">
</div>
<?php endif; ?>

<div class="container">

    <!-- Sobre Nosotros - Noticias -->
    <?php if (!empty(trim($cercle['noticia']))) : ?>
    <div class="sobre_nosotros enlaces">
        <h1 class="titulo">Noticias</h1>
        <p>
            <?= nl2br(esc((string) $cercle['noticia'])) ?>
        </p>
    </div>
    <?php endif; ?>

    <!-- Aviso de que hay eventos para ver -->

    <?php if(count($eventos)>0) : ?>
    <div class="enlaces">
        <h1 class="titulo">Hoy te proponemos estos eventos</h1>
    </div>

    <!-- Eventos en curso o próximos -->

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

    <?php endif; ?>

    <!-- Sobre Nosotros - Texto -->

    <div class="sobre_nosotros">
        <p>
            <?= nl2br(esc((string) $cercle['texto'])) ?>
        </p>
    </div>

    <!-- Recomendaciones de enlaces de interés -->

    <div class="enlaces">
        <h1 class="titulo">Te recomendamos estos enlaces</h1>
        <ul>
            <?php foreach ($enlaces as $enlace) : ?>
            <li>
                <a href="<?= esc($enlace->enlace, 'attr') ?>" target="_blank" rel="noopener noreferrer"><?= nl2br(esc(trim($enlace->texto))) ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?= $this->endSection(); ?>

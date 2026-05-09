<?= $this->extend('plantillas/layout'); ?>
<?= $this->section('contenido'); ?>

<?php
helper('utiles');

$cercle = (isset($cercle) && is_array($cercle)) ? $cercle : [];
$cercleVisible = !empty($cercle['visible']);
$cercleBanner = (string) ($cercle['banner'] ?? 'anagramaColor.png');
$cercleNoticia = (string) ($cercle['noticia'] ?? '');
$cercleTexto = (string) ($cercle['texto'] ?? '');
$eventos = (isset($eventos) && is_array($eventos))
    ? $eventos
    : ((isset($eventos) && $eventos instanceof Traversable) ? iterator_to_array($eventos, false) : []);
$enlaces = (isset($enlaces) && is_array($enlaces))
    ? $enlaces
    : ((isset($enlaces) && $enlaces instanceof Traversable) ? iterator_to_array($enlaces, false) : []);
?>

<?php if ($cercleVisible) : ?>
<div class="imagen_cercle">
    <img src="<?= base_url('recursos/imagenes/' . esc($cercleBanner, 'url')) ?>" alt="Banner del Cercle d'Art de Foios">
</div>
<?php endif; ?>

<div class="container">

    <!-- Sobre Nosotros - Noticias -->
    <?php if ($cercleNoticia !== '' && trim($cercleNoticia) !== '') : ?>
    <div class="sobre_nosotros enlaces">
        <h1 class="titulo">Noticias</h1>
        <p>
            <?= nl2br(esc($cercleNoticia)) ?>
        </p>
    </div>
    <?php endif; ?>

    <!-- Aviso de que hay eventos para ver -->

    <?php if (count($eventos) > 0) : ?>
    <div class="enlaces">
        <h1 class="titulo">Hoy te proponemos estos eventos</h1>
    </div>

    <!-- Eventos en curso o próximos -->

    <div class="container contenedor-4">
        <?php foreach ($eventos as $evento): ?>
            <?php $eventoData = is_object($evento) ? get_object_vars($evento) : (is_array($evento) ? $evento : []); ?>
            <?php $eventoId = (int) ($eventoData['id'] ?? 0); ?>
            <?php $eventoTitulo = (string) ($eventoData['titulo'] ?? ''); ?>
            <?php $eventoDesde = (string) ($eventoData['desde'] ?? ''); ?>
            <?php $eventoHasta = (string) ($eventoData['hasta'] ?? ''); ?>
            <?php $eventoGrupo = (string) ($eventoData['grupo'] ?? ''); ?>
            <?php if ($eventoId <= 0) { continue; } ?>
            <a href="<?= base_url('eventos/' . $eventoId) ?>" class="item-0 item-4">
                <img src="<?= base_url('imgEventos/ev_' . $eventoId . '/cartel.jpg') ?>"
                onerror="this.onerror=null;this.src='<?= base_url('imgEventos/eventos.jpg') ?>'"
                alt="Cartel del evento <?= esc($eventoTitulo) ?>">
                <h6 class="evento-titulo-4"><?= esc(strtoupper($eventoTitulo)) ?></h6>
                <p class="desde-hasta-4">Desde el <?= esc(uti_fecha($eventoDesde)) ?> hasta el <?= esc(uti_fecha($eventoHasta)) ?></p>
            <p class="estado-4 <?= uti_quita_(uti_estado_evento($eventoDesde, $eventoHasta))?>">
                <?= esc(strtoupper($eventoGrupo) . '   -   ' . uti_estado_evento($eventoDesde, $eventoHasta)) ?>
            </p>
        </a>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

    <!-- Sobre Nosotros - Texto -->

    <div class="sobre_nosotros">
        <p>
            <?= nl2br(esc($cercleTexto)) ?>
        </p>
    </div>

    <!-- Recomendaciones de enlaces de interés -->

    <div class="enlaces">
        <h1 class="titulo">Te recomendamos estos enlaces</h1>
        <ul>
            <?php foreach ($enlaces as $enlace) : ?>
            <?php $enlaceData = is_object($enlace) ? get_object_vars($enlace) : (is_array($enlace) ? $enlace : []); ?>
            <?php $enlaceUrl = (string) ($enlaceData['enlace'] ?? ''); ?>
            <?php $enlaceTexto = (string) ($enlaceData['texto'] ?? ''); ?>
            <li>
                <a href="<?= esc($enlaceUrl, 'attr') ?>" target="_blank" rel="noopener noreferrer"><?= nl2br(esc(trim($enlaceTexto))) ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?= $this->endSection(); ?>

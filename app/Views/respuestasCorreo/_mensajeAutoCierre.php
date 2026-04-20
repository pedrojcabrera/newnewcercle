<?php
$titulo = $titulo ?? 'Atencion';
$parrafos = $parrafos ?? [];
$textoCierre = $textoCierre ?? 'En unos segundos se cerrara este mensaje.';
$permitirCierreClick = $permitirCierreClick ?? true;
$mostrarBotonCerrar = $mostrarBotonCerrar ?? true;
?>

<style>
#x {
    position: absolute;
    top: 5px;
    right: 15px;
    font-size: 2em;
    color: red;
    cursor: pointer;
}
</style>

<div class="container_contactar" style="position: relative;"<?= $permitirCierreClick ? ' onclick="cerrarVentana()"' : '' ?>>
    <?php if ($mostrarBotonCerrar): ?>
        <div id="x"<?= $permitirCierreClick ? ' onclick="cerrarVentana()"' : '' ?>>X</div>
    <?php endif; ?>

    <h2><?= esc($titulo) ?></h2>

    <?php foreach ($parrafos as $parrafo): ?>
        <p><?= $parrafo ?></p>
    <?php endforeach; ?>

    <p><?= esc($textoCierre) ?></p>
</div>

<!doctype html>
<html lang="es">

    <head>
        <title>Galerias</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico');?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lexend+Giga:wght@100..900&
    family=Quicksand:wght@300..700&
    family=Roboto:wght@100;300;400;500;700;900&
    display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" type="text/css"
            href="<?=base_url('recursos/styleGaleristas.css')?>">

        <?= $this->renderSection('masStyle') ?>

    </head>

    <body>
        <?php
        $mostrarCabecera = isset($mostrarCabecera)
            ? (bool) $mostrarCabecera
            : (bool) session()->get('hayGalerias');
        ?>
        <div class="gal-shell<?= $mostrarCabecera ? '' : ' gal-shell-auth' ?>">
            <?php if ($mostrarCabecera): ?>
            <?= $this->include('galeristas/cabecera') ?>
            <?php endif; ?>
            <div class="gal-content">
                <?php if ($mostrarCabecera): ?>
                <header class="gal-actionbar">
                    <h1 class="gal-action-title"><?= esc($titulo ?? 'Galerista') ?></h1>
                </header>
                <?php endif; ?>
                <main class="gal-main container-fluid px-3 px-md-4 pb-4">
                    <?= $this->renderSection('contenido') ?>
                </main>
            </div>
        </div>
        <?= $this->include('galeristas/footer') ?>

        <?= $this->renderSection('masJS') ?>

    </body>

</html>

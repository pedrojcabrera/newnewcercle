<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Cercle d'Art de Foios</title>
        <meta name="description" content="The small framework with powerful features">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico');?>">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="<?= base_url('recursos/style.css'); ?>">
        <script src="<?= base_url('recursos/jQuery.js'); ?>"></script>
        <!-- <script src="https://www.google.com/recaptcha/enterprise.js?render=6Lc279MqAAAAADViohY2ogZrQCDN5xl9rcHGolmH">
   </script> -->
    </head>

    <body>
        <div class="todo">

            <?= $this->include('plantillas/sinmenu'); ?>

            <!-- <h1><?= $titulo; ?></h1> -->

            <?= $this->renderSection('contenido'); ?>

        </div>

        <?= $this->include('plantillas/footer'); ?>

        <?= $this->renderSection('masJS'); ?>

        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
    </body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>

<body style="font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;text-align:center">
    <div style="text-align: center;margin: 1rem auto;width: 100%;">
        <div style="text-align: center;margin: 0 auto;">
            <img
                src="<?= base_url('public/recursos/imagenes/logo_Cercle_125.png') ?>">
        </div>
        <div style="text-align: center;margin: 0 auto;">
            <h1>CERCLE D'ART DE FOIOS</h1>
        </div>
    </div>
    <HR>
    <div style="text-align: left; width: 600px;margin: 3rem auto;font-size:14px">

        <?= $this->renderSection('contenido') ?>

    </div>
    <hr>
</body>

</html>

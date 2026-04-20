<?= $this->extend('plantillas/layoutsinmenu')?>
<?= $this->section('contenido')?>
<?= view('respuestasCorreo/_mensajeAutoCierre', [
    'titulo' => 'Hola',
    'parrafos' => [
        'El envío del correo se realizó corréctamente, es posible que nos pongamos en contacto con Ud.<br>Un saludo cordial.',
    ],
]) ?>

<?= $this->endSection()?>

<?= $this->section('masJS')?>
<script src="<?= base_url('recursos/respuestaCorreoAutoClose.js') ?>"></script>

<?= $this->endSection()?>

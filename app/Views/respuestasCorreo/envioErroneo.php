<?= $this->extend('plantillas/layoutsinmenu')?>
<?= $this->section('contenido')?>
<?= view('respuestasCorreo/_mensajeAutoCierre', [
    'titulo' => 'Atención',
    'parrafos' => [
        'El envío del correo no se pudo realizar corréctamente, por favor, inténtelo de nuevo más adelante.<br>Un saludo cordial.',
    ],
]) ?>

<?= $this->endSection()?>

<?= $this->section('masJS')?>
<script src="<?= base_url('recursos/respuestaCorreoAutoClose.js') ?>"></script>

<?= $this->endSection()?>

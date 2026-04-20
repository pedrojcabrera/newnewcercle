<?= $this->extend('plantillas/layoutsinmenu')?>
<?= $this->section('contenido')?>
<?= view('respuestasCorreo/_mensajeAutoCierre', [
    'titulo' => 'Atención',
    'parrafos' => [
        'La solicitud de inscripción a este evento ya ha sido realizada anteriormente.',
        'En estos momentos se encuentra a la espera de confirmación por nuestra parte.',
        'Para cualquier aclaración o consulta, por favor, no dude en ponerse en contacto con nosotros a través de nuestro correo electrónico: correo@cercledartfoios.com o simplemente clicando <a href="mailto:correo@cercledarfoios.com">Aquí</a>.',
        'Gracias por su interés en nuestros eventos.',
        'Un saludo cordial del Cercle d\'Art de Foios.',
    ],
]) ?>

<?= $this->endSection()?>

<?= $this->section('masJS')?>
<script src="<?= base_url('recursos/respuestaCorreoAutoClose.js') ?>"></script>

<?= $this->endSection()?>

<?= $this->extend('plantillas/layoutsinmenu')?>
<?= $this->section('contenido')?>
<?= view('respuestasCorreo/_mensajeAutoCierre', [
    'titulo' => 'Atención',
    'parrafos' => [
        'En estos momentos no encontramos ninguna invitación a su nombre en este evento.',
        'Es posible que haya sido cancelada por Aforo Completo, finalización del plazo de inscripción o por cualquier otro motivo.',
        'Para cualquier aclaración o consulta, por favor, no dude en ponerse en contacto con nosotros a través de nuestro correo electrónico: correo@cercledartfoios.com o simplemente clicando <a href="mailto:correo@cercledarfoios.com">Aquí</a>.',
        'Gracias por su interés en nuestros eventos.',
        'Un saludo cordial del Cercle d\'Art de Foios.',
    ],
    'textoCierre' => 'Este mensaje se cerrará automáticamente en unos segundos.',
]) ?>

<?= $this->endSection()?>

<?= $this->section('masJS')?>
<script src="<?= base_url('recursos/respuestaCorreoAutoClose.js') ?>"></script>

<?= $this->endSection()?>

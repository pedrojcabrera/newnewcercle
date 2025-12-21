<?= $this->extend('plantillas/layoutsinmenu')?>
<?= $this->section('contenido')?>

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

<div class="container_contactar" style="position: relative;" onclick="cerrarVentana()">
    <div id="x" onclick="cerrarVentana()">X</div>

    <h2>Atención</h2>
    <p>En estos momentos no encontramos ninguna invitación a su nombre en este evento.</p>
    <p>Es posible que haya sido cancelada por Aforo Completo, finalización del plazo de inscripción o por cualquier otro
        motivo.</p>
    <p>Para cualquier aclaración o consulta, por favor, no dude en ponerse en contacto con nosotros a través de nuestro
        correo electrónico: correo@cercledartfoios.com o simplemente clicando <a
            href="mailto:correo@cercledarfoios.com">Aquí</a>.</p>
    <br>
    <p>Gracias por su interés en nuestros eventos.</p>
    <br>
    <p>Un saludo cordial del Cercle d'Art de Foios.</p>
    <br>
    <p>Este mensaje se cerrará automáticamente en unos segundos.</p>

</div>

<?= $this->endSection()?>

<?= $this->section('masJS')?>

<script type="text/javascript">
// Redirige a otra vista después de 5 segundos
setTimeout(function() {
    window.close();
}, 15000); // 15000 milisegundos = 5 segundos

// Otro cierre de ventana
function cerrarVentana() {
    window.close();
}
</script>

<?= $this->endSection()?>
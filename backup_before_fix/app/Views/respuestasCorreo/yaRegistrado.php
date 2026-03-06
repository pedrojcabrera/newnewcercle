<?= $this->extend('plantillas/layoutsinmenu')?>
<?= $this->section('contenido')?>

<style>
x {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 1.5em;
    color: red;
    cursor: pointer;
}
</style>

<div class="container_contactar">
    <div class="x">X</div>
    <h2>Atención</h2>
    <p>Ya se encuentra inscrito en este evento.</p>

    <p>Si cree que ha sido inscrito por error o desea cancelar este registro, por favor, póngase en contacto con
        nosotros para corregir esto.
    </p>

    <p>Para cualquier aclaración o consulta, por favor, no dude en ponerse en contacto con nosotros a través de nuestro
        correo electrónico: correo@cercledartfoios.com o simplemente clicando <a
            href="mailto:correo@cercledarfoios.com">Aquí</a>.</p>
    <br>
    <p>Gracias por su interés en nuestros eventos.</p>
    <br>
    <p>Un saludo cordial del Cercle d'Art de Foios.</p>
    <p>En unos segundos se cerrará este mensaje.</p>

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
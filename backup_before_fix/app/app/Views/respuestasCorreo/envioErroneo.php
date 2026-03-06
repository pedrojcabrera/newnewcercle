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
    <p>
        El envío del correo no se pudo realizar corréctamente, por favor, inténtelo de nuevo más adelante.
        <br>
        Un saludo cordial.
    </p>
    <p>
        En unos segundos se cerrará este mensaje.
    </p>

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
<!-- FOOTER: DEBUG INFO + COPYRIGHTS -->

<footer>
   <div class="environment">
      <address>
         Ubicado en la Casa de la Cultura<br>
         Plaza de España, 1<br>
         Pabellón Derecho<br>
         46134 - Foios<br>
         <br>
         Contactar: <a class="mailto" href="mailto:correo@cercledartfoios.com">correo@cercledartfoios.com</a>
      </address>
   </div>

   <div class="copyrights">
      <p>
         &copy; <?= date('Y') ?> Cercle d'Art de Foios.
      </p>
   </div>

</footer>

<!-- SCRIPTS -->

<script {csp-script-nonce}>
document.getElementById("menuToggle").addEventListener('click', toggleMenu);

function toggleMenu() {
   var menuItems = document.getElementsByClassName('menu-item');
   for (var i = 0; i < menuItems.length; i++) {
      var menuItem = menuItems[i];
      menuItem.classList.toggle("hidden");
   }
}
</script>
<!-- ---------------------------------------------------- -->
<script>
// Agregar una entrada en el historial para evitar el retroceso
window.history.pushState(null, "", window.location.href);
window.onpopstate = function() {
   window.history.pushState(null, "", window.location.href);
};
</script>
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
         &copy; <?= date('Y') ?> Cercle d'Art de Foios. &mdash;
         <a href="<?= base_url('privacidad') ?>">Política de privacidad</a>
      </p>
   </div>

</footer>

<!-- Aviso de cookies -->
<div id="cookie-banner" style="position:fixed;bottom:0;left:0;right:0;background:#222;color:#fff;padding:.75rem 1.25rem;z-index:9999;display:flex;align-items:center;gap:1rem;flex-wrap:wrap;" role="dialog" aria-label="Aviso de cookies">
   <p style="margin:0;flex:1;min-width:200px;">Esta web utiliza Google reCAPTCHA en sus formularios para protegerse del spam. Consulta nuestra <a href="<?= base_url('privacidad') ?>" style="color:#9ecfff;">política de privacidad</a>.</p>
   <button id="cookie-accept" class="btn btn-sm btn-light" type="button">Aceptar</button>
</div>

<!-- SCRIPTS -->

<script {csp-script-nonce}>
(function() {
   var cookieBanner = document.getElementById('cookie-banner');
   if (cookieBanner) {
      if (localStorage.getItem('cookiesAceptadas')) {
         cookieBanner.style.display = 'none';
      }
      var btnAceptar = document.getElementById('cookie-accept');
      if (btnAceptar) {
         btnAceptar.addEventListener('click', function() {
            localStorage.setItem('cookiesAceptadas', '1');
            cookieBanner.style.display = 'none';
         });
      }
   }
})();

const menuToggle = document.getElementById('menuToggle');

if (menuToggle) {
   menuToggle.addEventListener('click', toggleMenu);
}

function toggleMenu() {
   var menuItems = document.getElementsByClassName('menu-item');
   var expanded = menuToggle ? menuToggle.getAttribute('aria-expanded') === 'true' : false;

   for (var i = 0; i < menuItems.length; i++) {
      var menuItem = menuItems[i];
      menuItem.classList.toggle('hidden');
   }

   if (menuToggle) {
      menuToggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
   }
}
</script>

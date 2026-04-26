<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container_contactar">

   <div class="container">

      <?php if (isset($errores) && !empty($errores)) : ?>
      <div class="row">
         <div class="col-lg-6 col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
               <?php foreach ($errores as $lineaMensaje) : ?>
               <p><?= $lineaMensaje ?></p>
               <?php endforeach; ?>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
         </div>
      </div>
      <?php endif; ?>

      <?php if (isset($cabeceras) && !empty($cabeceras)) : ?>
      <div class="row">
         <div class="col-lg-6 col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <?php foreach ($cabeceras as $lineaMensaje) : ?>
               <p><?= $lineaMensaje ?></p>
               <?php endforeach; ?>
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
         </div>
      </div>
      <?php endif; ?>

      <?=form_open(base_url("/contactar/enviar"),['id="form-Contactar"'])?>

      <div class="mb-3">
         <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required>
      </div>
      <div class="mb-3">
         <input type="email" class="form-control" name="email" id="email" placeholder="Correo Electrónico / eMail"
            required>
      </div>
      <div class="mb-3">
         <input type="tel" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" required>
      </div>
      <div class="mb-3">
         <textarea class="form-control" name="mensaje" id="mensaje" rows="3" placeholder="Mensaje / Comentarios"
            required><?= esc($mensajeInicial ?? '') ?></textarea>
      </div>
      <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
      <button type="submit" id="enviar" value="submit" class="btn btn-primary">Enviar</button>
      <small class="form-text text-muted d-block mt-2">Este sitio está protegido por reCAPTCHA de Google. Se aplican la <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">Política de Privacidad</a> y los <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">Términos de Servicio</a>.</small>

      <?=form_close()?>

   </div>

</div>

<?= $this->endSection()?>

<?= $this->section('masJS')?>

<script src="https://www.google.com/recaptcha/api.js?render=<?=env('recaptchaSiteKey')?>" async defer></script>
<script>
   (function() {
      var form = document.getElementById('form-Contactar');
      var tokenInput = document.getElementById('g-recaptcha-response');
      var siteKey = '<?=env("recaptchaSiteKey")?>';
      var isSubmitting = false;

      function submitConToken() {
         if (!window.grecaptcha || !siteKey) {
            alert('No se ha podido validar reCAPTCHA. Recarga la página e inténtalo de nuevo.');
            isSubmitting = false;
            return;
         }

         grecaptcha.ready(function() {
            grecaptcha.execute(siteKey, { action: 'submit' }).then(function(token) {
               tokenInput.value = token;
               form.submit();
            }).catch(function() {
               alert('No se ha podido validar reCAPTCHA. Inténtalo de nuevo.');
               isSubmitting = false;
            });
         });
      }

      form.addEventListener('submit', function(e) {
         if (isSubmitting) {
            return;
         }

         e.preventDefault();
         isSubmitting = true;
         tokenInput.value = '';
         submitConToken();
      });
   })();
</script>

<?= $this->endSection()?>

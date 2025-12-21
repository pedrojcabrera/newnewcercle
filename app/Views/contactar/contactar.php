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

      <?=form_open(base_url("/contactar/enviar",$_SERVER['REQUEST_SCHEME']),['id="form-Contactar"'])?>

      <div class="mb-3">
         <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" required>
      </div>
      <div class="mb-3">
         <input type="email" class="form-control" name="email" id="email" placeholder="Correo Electrónico / eMail"
            required>
      </div>
      <div class="mb-3">
         <input type="telefono" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" required>
      </div>
      <div class="mb-3">
         <textarea class="form-control" name="mensaje" id="mensaje" rows="3" placeholder="Mensaje / Comentarios"
            required></textarea>
      </div>
      <div class="mb-3">
         <div class="g-recaptcha" data-sitekey="<?=env("recaptchaSiteKey")?>">
         </div>
      </div>
      <button type="submit" id="enviar" value="submit" class="btn btn-primary">Enviar</button>

      <?=form_close()?>

   </div>

</div>

<?= $this->endSection()?>

<?= $this->section('masJS')?>

<script src='https://www.google.com/recaptcha/api.js' async defer></script>

<?= $this->endSection()?>
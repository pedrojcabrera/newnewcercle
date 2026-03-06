<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>
<?php $session = session(); ?>


<div class="d-flex align-items-center justify-content-center" style="height:90vh;">
   <div class="col-md-4">
      <div class="card">
         <div class="login-sup card-header text-center bg-success" style="--bs-bg-opacity: .3;">
            <img src="<?=base_url('recursos/imagenes/anagrama_circular.png',$_SERVER['REQUEST_SCHEME'])?>" width="100"
               alt="">
            <h4 class="card-title mt-2 text-dark">Acceso a la Galería</h4>
         </div>
         <div class="login-inf card-body">
            <?php if ($session->getFlashdata('msg')) : ?>
            <div class="alert alert-danger" role="alert">
               <strong>Atención:</strong><br><?= $session->getFlashdata('msg') ?>
            </div>
            <?php endif; ?>
            <form action="<?=base_url('galeristas/validar')?>" method="post">
               <?= csrf_field() ?>
               <div class="mb-3">
                  <label for="user" class="form-label">Galerista:</label>
                  <input type="text" class="form-control" name="user" id="user" placeholder="galerista">
               </div>
               <div class="mb-3">
                  <label for="pass" class="form-label">Contraseña:</label>
                  <input type="password" class="form-control" name="pass" id="pass" placeholder="contraseña">
               </div>
               <div class="mb-3 mt-5 text-end">
                  <button type="submit" name="submit" value="1" class="btn btn-primary btn-md bi-box-arrow-in-right"
                     title="Acceder">
                     Acceder</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<?= $this->endSection()?>
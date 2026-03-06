<?php if(session()->hayGalerias): ?>
<div class="cabecera">
   <h4><?=$titulo?></h4>
   <img src="<?= base_url('recursos/imagenes/anagramaColor.png') ?>" alt="">
   <?php if(isset($hayLogout)): ?>
   <a class="btn btn-success btn-sm bi-door-open" title="Logout / Salir"
      href="<?=base_url('galeristas/logout')?>">
      Logout / Salir</a>
   <?php endif ?>
   </li>
   </a>

</div>
<?php endif; ?>
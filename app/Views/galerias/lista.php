<?php echo $this->extend('plantillas/layout')?>
<?php echo $this->section('contenido')?>

<div class="container lista_galerias cuerpo">

   <?php foreach ($artistas as $artista): ?>

   <?php
    $user     = $artista->id_user;
    $fotoUser = $user . '.jpg';
   ?>

   <figure>
      <a href="<?php echo base_url('pinturas/' . $user)?>">
         <img src="<?php echo base_url('fotosUsuarios/' . $fotoUser)?>"
            onerror="this.onerror=null;this.src='<?= base_url('fotosUsuarios/sinfoto.jpg') ?>'"
            title="Ver la exposicion de <?php echo esc($nombres[$user])?>" alt="Foto de <?php echo esc($nombres[$user]) ?>">
      </a>

      <figcaption>
         <?php echo esc($nombres[$user])?>
         <br>
         <?php echo esc($cantidad[$user]) . ' Obras'?>
      </figcaption>
   </figure>

   <?php endforeach; ?>

</div>

<?php echo $this->endSection()?>

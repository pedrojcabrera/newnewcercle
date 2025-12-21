<?php echo $this->extend('plantillas/layout')?>
<?php echo $this->section('contenido')?>

<div class="container lista_galerias cuerpo">

   <?php foreach ($artistas as $artista): ?>

   <?php
    $user     = $artista->id_user;
    $foto     = 'sinfoto.jpg';
    $fotoUser = $user . '.jpg';
    if (file_exists('fotosUsuarios/' . $fotoUser)) {
     $foto = $fotoUser;
    }
   ?>

   <figure>
      <a href="<?php echo base_url('pinturas/' . $user, $_SERVER['REQUEST_SCHEME'])?>">
         <img src=" <?php echo base_url('fotosUsuarios/' . $foto, $_SERVER['REQUEST_SCHEME'])?>"
            title="Ver la exposición de <?php echo $nombres[$user]?>" alt="">
      </a>

      <figcaption>
         <?php echo $nombres[$user]?>
         <br>
         <?php echo $cantidad[$user] . ' Obras'?>
      </figcaption>
   </figure>

   <?php endforeach; ?>

</div>

<?php echo $this->endSection()?>
<?= $this->extend('galeristas/layout')?>
<?= $this->section('contenido')?>

<div class="container lista_galerias">

   <?php
   /*
   $router = service('router');
   dd($data,$router->controllerName(),$router->methodName());
   */   
   //dd($data);
   $foto = 'sinfoto.jpg';
   $fotoUser = $data->user.'.jpg';
   if (file_exists('fotosUsuarios/'.$fotoUser)) {
      $foto = $fotoUser;
   }
   ?>
   <figure>
      <img src="<?=base_url('fotosUsuarios/'.$foto)?>" alt="">

      <figcaption>
         <?= $data->nombre?>
      </figcaption>
      <a class="boton-nuevo btn btn-primary btn-sm bi-easel"
         href="<?=base_url('galeristas/nuevo')?>"> Nueva
         Obra</a>
   </figure>
</div>
<div class="container lista_cuadros">
   <?php foreach ($obras as $obra): ?>

   <div class=" card tarjeta_cuadro" style="width: 18rem;">
      <img src="<?=base_url('galerias/'.$obra->id_user.'/'.$obra->id.'.jpg')?>"
         class="card-img-top" alt="">
      <div class="card-body cuerpo-tarjeta">
         <h5 class="card-title">
            <?=$obra->titulo?>
         </h5>
         <h6><?=($obra->ano > 0) ? '(Año '.$obra->ano.') ' : '';?></h6>
         <?php if(!empty($obra->comentarios)): ?>
         <p class="comentarios">
            <?=nl2br($obra->comentarios)?>
         </p>
         <hr>
         <?php endif;?>
         <p>
            <span class="datosCuadro">Técnica: </span> <?=$obra->tecnica?><br>
            <span class="datosCuadro">Soporte: </span> <?=$obra->soporte?><br>
            <span class="datosCuadro">Medidas: </span> <?=$obra->medidas?>
         </p>
         <?php if($obra->precio > 0): ?>
         <p>
            <span class="datosCuadro">Precio: </span> <?=$obra->precio?> <small>€</small>
         </p>
         <?php if(!empty($obra->premios)): ?>
         <hr>
         <p><span class="datosCuadro">Premios:<br></span>
            <?=nl2br($obra->premios)?>
         </p>
         <?php endif;?>
         <?php endif;?>
         <div class="botones-pie">
            <a class="boton-nuevo btn btn-success btn-sm bi-pencil"
               href="<?=base_url('galeristas/editar/'.$obra->id)?>">
               Editar</a>
            <a class="boton-nuevo btn btn-danger btn-sm bi-trash3" href="<?=base_url('galeristas/quitar/'.$obra->id)?>"
               onclick="return confirm('¿Estás seguro de quitar esta obra de tu galería?')">
               Quitar</a>
         </div>
      </div>
   </div>

   <?php endforeach; ?>

</div>
<?= $this->endSection()?>
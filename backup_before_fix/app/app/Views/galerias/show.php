<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container lista_galerias">

   <?php 
        $user = $artista;
        $foto = 'sinfoto.jpg';
        $fotoUser = $user.'.jpg';
        if (file_exists('fotosUsuarios/'.$fotoUser)) {
            $foto = $fotoUser;
        }
    ?>
   <figure>
      <img src=" <?=base_url('fotosUsuarios/'.$foto,$_SERVER['REQUEST_SCHEME'])?>" alt="">

      <figcaption>
         <?= $nombre?>
      </figcaption>
   </figure>
</div>
<div class="container contenedor-4">
   <!-- Modal -->
   <div id="modal" class="modal">
      <span class="close">&times;</span>
      <img id="imagenAmpliada">
   </div>
   <?php foreach ($obras as $obra): ?>
   <div class="card item-0 item-4">
      <img src="<?=base_url('galerias/'.$artista.'/'.$obra->cuadro.'.jpg',$_SERVER['REQUEST_SCHEME'])?>" alt="">
      <div class="card-body">
         <h5 class="card-title"><?=$obra->titulo?></h5>
         <h6 class="ano"><?=($obra->ano > 0) ? 'Año: '.$obra->ano : ''?></h6>
         <?php if(!empty($obra->comentarios)): ?>
         <hr>
         <p class="comentarios-4">
            <?=nl2br($obra->comentarios)?>
         </p>
         <?php endif;?>
         <hr>
         <p class="datos-tecnicos-4">
            <span class="datosCuadro">Técnica: </span> <?=$obra->tecnica?><br>
            <span class="datosCuadro">Soporte: </span> <?=$obra->soporte?><br>
            <span class="datosCuadro">Medidas: </span> <?=$obra->medidas?>
         </p>
         <?php if($obra->precio > 0): ?>
         <p class="datos-tecnicos-4">
            <span class="datosCuadro">Precio: </span> <?=$obra->precio?><small><small>€</small></small><span> (En
               venta)</span>
         </p>
         <?php if(!empty($obra->premios)): ?>
         <hr>
         <p class="premios-4">
            <?=nl2br($obra->premios)?>
         </p>
         <?php endif;?>
         <?php endif; ?>
      </div>
   </div>

   <?php endforeach; ?>

</div>
<script>
const modal = document.getElementById("modal");
const imagenAmpliada = document.getElementById("imagenAmpliada");
const close = document.querySelector(".close");

document.querySelectorAll(".contenedor-4 img").forEach(img => {
   img.addEventListener("click", () => {
      imagenAmpliada.src = img.src;
      modal.style.display = "flex";
   });
});

close.addEventListener("click", () => {
   modal.style.display = "none";
});

modal.addEventListener("click", (e) => {
   if (e.target === modal) {
      modal.style.display = "none";
   }
});
</script>

<?= $this->endSection()?>
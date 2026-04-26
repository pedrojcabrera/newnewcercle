<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container lista_galerias">

   <?php
        $user = $artista;
      $fotoUser = $user . '.jpg';
    ?>
   <figure>
     <img src="<?= base_url('fotosUsuarios/' . $fotoUser) ?>"
       onerror="this.onerror=null;this.src='<?= base_url('fotosUsuarios/sinfoto.jpg') ?>'"
       alt="Foto de <?= esc($nombre) ?>">

      <figcaption>
         <?= esc($nombre) ?>
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
      <img src="<?=base_url('galerias/'.$artista.'/'.$obra->cuadro.'.jpg')?>" alt="Obra de <?= esc($nombre) ?>">
      <div class="card-body">
         <h5 class="card-title"><?= esc($obra->titulo) ?></h5>
         <h6 class="ano"><?=($obra->ano > 0) ? 'Año: '.esc($obra->ano) : ''?></h6>
         <?php if(!empty($obra->comentarios)): ?>
         <hr>
         <p class="comentarios-4">
            <?= nl2br(esc((string) $obra->comentarios)) ?>
         </p>
         <?php endif;?>
         <hr>
         <p class="datos-tecnicos-4">
            <span class="datosCuadro">Técnica: </span> <?= esc($obra->tecnica) ?><br>
            <span class="datosCuadro">Soporte: </span> <?= esc($obra->soporte) ?><br>
            <span class="datosCuadro">Medidas: </span> <?= esc($obra->medidas) ?>
         </p>
         <?php if($obra->precio > 0): ?>
         <p class="datos-tecnicos-4">
            <span class="datosCuadro">Precio: </span> <?= esc($obra->precio) ?><small><small>€</small></small><span> (En
               venta)</span>
         </p>
         <p>
            <a href="<?= base_url('contactar?obra=' . rawurlencode($obra->titulo . ' de ' . $nombre)) ?>" class="btn btn-sm btn-outline-primary">Contactar sobre esta obra</a>
         </p>
         <?php if(!empty($obra->premios)): ?>
         <hr>
         <p class="premios-4">
            <?= nl2br(esc((string) $obra->premios)) ?>
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

document.addEventListener("keydown", (e) => {
   if (e.key === "Escape") {
      modal.style.display = "none";
   }
});
   }
});
</script>

<?= $this->endSection()?>

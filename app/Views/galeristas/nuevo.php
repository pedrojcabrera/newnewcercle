<?= $this->extend('galeristas/layout')?>
<?= $this->section('contenido')?>
<?php
/*
   $router = service('router');
   dd($router->controllerName(),$router->methodName());
*/
?>
<form action="<?=base_url('galeristas/crear')?>" method="post"
   enctype="multipart/form-data">
   <h4 style="text-align: center; margin-top: .5rem;"><?= $usuario->nombre ?></h4>
   <div class="card" style="margin-left: 1rem; margin-right: 1rem;">
      <div class="container-p">
         <div class="card-body">
            <div>
               <label for="autor" class="form-label">Autor:</label>
               <input type="text" class="form-control" name="autor" value="<?=old('autor')?>" id="autor"
                  placeholder="Rellenar solamente si el autor no es el galerista">
            </div>
            <div>
               <label for="titulo" class="form-label">Título:</label>
               <input type="text" class="form-control" name="titulo" value="<?=old('titulo')?>" id="titulo"
                  placeholder="Título de la obra">
               <span class="text-danger">
                  <?= (session('errors.titulo')) ? session('errors.titulo') : '' ?>
               </span>
            </div>
            <div>
               <label for="tecnica" class="form-label">Técnica:</label>
               <input type="text" class="form-control" name="tecnica" value="<?=old('tecnica')?>" id="tecnica"
                  placeholder="Técnica usada">
               <span class="text-danger">
                  <?= (session('errors.tecnica')) ? session('errors.tecnica') : '' ?>
               </span>
            </div>
            <div>
               <label for="soporte" class="form-label">Soporte:</label>
               <input type="text" class="form-control" name="soporte" value="<?=old('soporte')?>" id="soporte"
                  placeholder="Soporte empleado">
               <span class="text-danger">
                  <?= (session('errors.soporte')) ? session('errors.soporte') : '' ?>
               </span>
            </div>
            <div>
               <label for="medidas" class="form-label">Medidas</label>
               <input type="text" class="form-control" name="medidas" value="<?=old('medidas')?>" id="medidas"
                  placeholder="Medidas">
               <span class="text-danger">
                  <?= (session('errors.medidas')) ? session('errors.medidas') : '' ?>
               </span>
            </div>
            <div>
               <label for="imagen" class="form-label">Seleccione la Imagen</label>
               <input type="file" class="form-control" name="imagen" value="<?=old('titulo')?>" imagen id="imagen"
                  placeholder="Seleccione la imagen">
               <span class="text-danger">
                  <?= (session('errors.imagen')) ? session('errors.imagen') : '' ?>
               </span>
            </div>
         </div>
         <div class="card-body">
            <div>
               <label for="ano" class="form-label">Año de creación de la Obra</label>
               <select class="form-select form-select-sm" name="ano" id="ano" value="<?=old('ano')?>">
                  <?php for ($i = $year_inicio; $i <= $year_hoy; $i++) : ?>
                  <option <?= $i == $year_hoy ? 'selected' : '' ?>><?= str_pad($i, 4, '0', STR_PAD_LEFT) ?>
                  </option>
                  <?php endfor; ?>
               </select>
            </div>
            <div>
               <label for="premios" class="form-label">Premios obtenidos</label>
               <textarea class="form-control" name="premios" id="premios" rows="5"><?=old('premios')?></textarea>
            </div>
            <div>
               <label for="comentarios" class="form-label">Comentarios</label>
               <textarea class="form-control" name="comentarios" id="comentarios"
                  rows="5"><?=old('comentarios')?></textarea>
            </div>
            <div>
               <label for="precio" class="form-label">Poner precio, solo si está en venta</label>
               <input type="number" class="form-control" name="precio" value="<?=old('precio') ? old('precio') : 0 ?>"
                  id="precio" min="0" max="9999999" required>
               <span class="text-danger">
                  <?= (session('errors.precio')) ? session('errors.precio') : '' ?>
               </span>
            </div>
         </div>
      </div>
   </div>
   <div class="mt-3 text-end" style="margin-right: 1rem;">
      <a href="<?=base_url('galeristas/cancelar')?>"
         class="btn btn-success btn-sm bi-box-arrow-left" name="btn_cancelar" title="Cancelar"> Cancelar</a>
      <input type="hidden" name="txtID" value="<?= $usuario->id ?>">
      <button type="submit" class="btn btn-primary btn-sm bi-check-lg" name="btn_grabar" title="Grabar Obra">
         Grabar</button>
   </div>
</form>
<?= $this->endSection() ?>
<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container my-3">
   <div class="container my-1 mx-auto px-2">
      <h2 class='text-center'><?= esc(strtoupper($correo->asunto)) ?></h2>
   </div>
   <div class="card-header fs-5">Recuerda que puedes usar los siguientes distintivos en el correo...</div>
   <div class="card-body fst-italic py-3 text-center">{{nombre}}, {{email}}, {{telefono}}, {{direccion}}, {{codpostal}},
      {{poblacion}}</div>
   <div class="card-footer text-center fs-5">...y que estos serán sustituidos por sus valores reales en el momento de la
      émisión del mismo.</div>
</div>
<div class="container mx-auto" style="max-width: 520px;">
   <div class="card-header fs-5">Seleccione grupos de destinatarios</div>
   <form action="<?=base_url('control/correos/enviomasivo/'.$id)?>" method="post">
      <?= csrf_field() ?>
      <div class="card-body">
         <div class="form-check">
            <input class="form-check-input" name="socio" type="checkbox" value="0" id="socio">
            <label class="form-check-label" for="socio">
               Socios
            </label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="alumno" type="checkbox" value="0" id="alumno">
            <label class="form-check-label" for="alumno">
               Alumnos
            </label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="pdalumno" type="checkbox" value="0" id="pdalumno">
            <label class="form-check-label" for="pdalumno">
               Padres o Madres de Alumnos
            </label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="pintor" type="checkbox" value="0" id="pintor">
            <label class="form-check-label" for="pintor">
               Pintores o Artistas
            </label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="dtaller" type="checkbox" value="0" id="dtaller">
            <label class="form-check-label" for="dtaller">
               Asistentes de Talleres
            </label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="amigo" type="checkbox" value="0" id="amigo">
            <label class="form-check-label" for="amigo">
               Amigos o Simpatizantes
            </label>
         </div>
      </div>
      <div class="card-footer text-end">
         <a href="<?=base_url('control/correos')?>" type="button" title="Regresar"
            class="btn btn-md btn-success"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
         <button type="submit" class="btn btn-primary btn-md" title="Enviar correo"><i class="bi bi-send-fill"></i> Enviar</button>
      </div>
   </form>
</div>
<?php if(isset($mensaje_error)): ?>
<div class="container mt-3">
   <div class="mx-auto alert alert-danger text-center" role="alert" style="max-width: 520px;">
   <?= $mensaje_error ?>
   </div>
</div>
<?php endif ?>


<?= $this->endSection() ?>

<?= $this->section('masJS')?>

<?= $this->endSection() ?>

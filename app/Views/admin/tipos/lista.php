<?php echo $this->extend('admin/plantillas/layout');?>
<?php echo $this->section('contenido');?>

<div class="container col-6 mx-auto">
   <div class="botones-superiores">
      <div class="boton-agregar">
         <a name="" id="" title="Nuevo Tipo de Evento" class="btn btn-primary btn-sm"
            href="<?php echo base_url('control/tipos/nuevo');?>" role="button"><i class="bi bi-check-lg"></i> Agregar nuevo tipo de evento</a>
      </div>
   </div>

   <div class="card-body">
      <div class="table-responsive-sm">
         <table class="miTabla mt-3" id="datatable">
            <thead>
               <tr>
                  <th scope="col" class="col-1">Tipo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col" class="col-1">Acciones</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($tipos as $tipo): ?>
               <tr class="align-middle">
                  <td><?php echo esc(trim($tipo->eventotipo));?></td>
                  <td><?php echo esc(trim($tipo->eventonombre));?></td>
                  <td class="text-end ico-acciones">
                     <a name="" title="Editar" id="" class="btn btn-success btn-sm"
                        aria-label="Editar"
                        href="<?php echo base_url('control/tipos/editar/' . $tipo->id);?>"><i class="bi bi-pencil-fill"></i></a>
                     <form style="display: inline;" action="<?php echo base_url('control/tipos/' . $tipo->id);?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" title="Borrar" class="btn btn-danger btn-sm"
                           aria-label="Borrar"
                           onclick="return confirm('\u00bf Confirma el borrado ?');"><i class="bi bi-trash3-fill"></i></button>
                     </form>
                  </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>

<?php echo $this->endSection();?>

<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<div class="container">
   <div class="botones-superiores">
      <div class="boton-agregar">
         <a name="" id="" title="Nuevo Enlace" class="btn btn-primary btn-md"
            href="<?php echo base_url('control/enlaces/nuevo'); ?>" role="button"><i class="bi bi-check-lg"></i> Agregar nuevo enlace</a>
      </div>
   </div>

   <div class="card-body">
      <div class="table-responsive-sm">
         <table class="miTabla mt-3" id="datatable">
            <thead>
               <tr>
                  <th scope="col">Creado</th>
                  <th scope="col">Texto</th>
                  <th scope="col">Enlace</th>
                  <th scope="col">Acciones</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($enlaces as $enlace): ?>
                  <?php $date = new DateTime($enlace->fecha_insercion); ?>
                  <tr class="align-middle">
                     <td><?php echo $date->format('d-m-Y (H:i)') . 'h'; ?></td>
                     <td><?php echo nl2br(trim($enlace->texto)); ?></td>
                     <td><?php echo trim($enlace->enlace); ?></td>
                     <td class="text-end ico-acciones">
                        <a name="" title="Editar" id="" class="btn btn-success btn-sm"
                           aria-label="Editar"
                           href="<?php echo base_url('control/enlaces/editar/' . $enlace->id); ?>"><i class="bi bi-pencil-fill"></i></a>
                        <form style="display: inline;" action="<?php echo base_url('control/enlaces/' . $enlace->id); ?>"
                           method="POST">
                           <?php echo csrf_field(); ?>
                           <input type="hidden" name="_method" value="DELETE">
                           <button type="submit" title="Borrar" class="btn btn-danger btn-sm"
                              aria-label="Borrar"
                              onclick="return confirm('¿ Confirma el borrado ?');"><i class="bi bi-trash3-fill"></i></button>
                        </form>
                     </td>
                  </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>

<?php echo $this->endSection(); ?>

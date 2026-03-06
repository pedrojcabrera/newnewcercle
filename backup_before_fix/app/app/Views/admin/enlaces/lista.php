<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<div class="container">
   <div class="botones-superiores">
      <div class="boton-agregar">
         <a name="" id="" title="Crear" class="btn btn-primary btn-sm bi-person-plus"
            href="<?php echo base_url('control/enlaces/nuevo', $_SERVER['REQUEST_SCHEME']); ?>" role="button">
            Nuevo Enlace</a>
      </div>
      <div class="boton-cancelar">
         <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
            href="<?php echo base_url('dashboard', $_SERVER['REQUEST_SCHEME']); ?>" role="button"
            title="Cancelar"> Cancelar</a>
      </div>
   </div>

   <div class="card-body">
      <div class="table-responsive-sm">
         <table class="miTabla mt-3" id="datatable">
            <thead>
               <tr>
                  <th scope="col">ID</th>
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
                     <td><?php echo $enlace->id; ?></td>
                     <td><?php echo $date->format('d-m-Y (H:i)') . 'h'; ?></td>
                     <td><?php echo nl2br(trim($enlace->texto)); ?></td>
                     <td><?php echo trim($enlace->enlace); ?></td>
                     <td class="text-end ico-acciones">
                        <a name="" title="Editar" id="" class="btn btn-success btn-sm bi-pencil"
                           href="<?php echo base_url('control/enlaces/editar/' . $enlace->id); ?>"> Editar</a>
                        <form style="display: inline;" action="<?php echo base_url('control/enlaces/' . $enlace->id); ?>"
                           method="POST">
                           <input type="hidden" name="_method" value="DELETE">
                           <button type="submit" title="Borrar" class="btn btn-danger btn-sm bi-eraser"
                              onclick="return confirm('¿ Confirma el borrado ?');"> Borrar</button>
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
<?php echo $this->extend('admin/plantillas/layout');?>
<?php echo $this->section('contenido');?>

<div class="container col-6 mx-auto">
   <div class="botones-superiores">
      <div class="boton-agregar">
         <a name="" id="" title="Crear" class="btn btn-primary btn-sm bi-person-plus"
            href="<?php echo base_url('control/tipos/nuevo', $_SERVER['REQUEST_SCHEME']);?>" role="button">
            Nuevo Tipo de Evento</a>
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
                  <th scope="col" class="col-1">ID</th>
                  <th scope="col" class="col-1">Tipo</th>
                  <th scope="col">Nombre</th>
                  <th scope="col" class="col-1">Acciones</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($tipos as $tipo): ?>
               <tr class="align-middle">
                  <td><?php echo $tipo->id;?></td>
                  <td><?php echo trim($tipo->eventotipo);?></td>
                  <td><?php echo trim($tipo->eventonombre);?></td>
                  <td class="text-end ico-acciones">
                     <a name="" title="Editar" id="" class="btn btn-success btn-sm bi-pencil"
                        href="<?php echo base_url('control/tipos/editar/' . $tipo->id);?>"> Editar</a>
                     <form style="display: inline;" action="<?php echo base_url('control/tipos/' . $tipo->id);?>" method="POST">
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

<?php echo $this->endSection();?>
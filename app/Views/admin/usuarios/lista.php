<?php echo $this->extend('admin/plantillas/layout');?>
<?php echo $this->section('contenido');?>

<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Nuevo Usuario" class="btn btn-primary btn-sm bi bi-check-lg"
                href="<?php echo base_url('control/usuarios/nuevo');?>" role="button"> Agregar nuevo usuario</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th scope="col">Foto</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($usuarios as $usuario): ?>
                    <?php if ($usuario->user == 'cercledartfoios' or $usuario->user == 'admin') {
  continue;
}?>
                    <tr class="align-middle">
                        <?php

                         $foto     = 'sinfoto.jpg';
                         $fotoUser = $usuario->id . '.jpg';
                         if (file_exists('fotosUsuarios/' . $fotoUser)) {
                          $foto = $fotoUser;
                         }
                        ?>
                        <td class="text-center">
                            <img src=" <?php echo base_url('fotosUsuarios/' . $foto);?>"
                                width='50' class='img-fluid rounded-circle'>
                            <br><small class="mt-0"><small class="mt-0"><?php echo esc($usuario->user);?></small></small>
                        </td>
                        <td><?php echo esc($usuario->nombre);?><br>
                            <small><?php echo esc($usuario->correo);?></small>
                        </td>
                        <td><?php echo esc($usuario->telefono);?></td>
                        <td><?php echo $usuario->admin ? "Adm." : "Gal.";?></td>
                        <td class="ico-acciones">
                            <a name="" id="" title="Editar" class="btn btn-success btn-sm"
                                aria-label="Editar"
                                href="<?php echo base_url('control/usuarios/editar/' . $usuario->id);?>"><i class="bi bi-pencil-fill"></i></a>
                            <form style="display: inline;"
                                action="<?php echo base_url('control/usuarios/' . $usuario->id);?>" method="POST">
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

<?php echo $this->endSection();?>

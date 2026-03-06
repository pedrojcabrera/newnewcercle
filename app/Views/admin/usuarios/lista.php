<?php echo $this->extend('admin/plantillas/layout');?>
<?php echo $this->section('contenido');?>

<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Crear" class="btn btn-primary btn-sm bi-person-plus"
                href="<?php echo base_url('control/usuarios/nuevo');?>" role="button"> Nuevo
                Usuario:
                Administrador o Galerista</a>
        </div>
    </div>
    <boton-cancelar></boton-cancelar>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
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
                        <td><?php echo $usuario->id;?></td>
                        <td class="text-center">
                            <img src=" <?php echo base_url('fotosUsuarios/' . $foto);?>"
                                width='50' class='img-fluid rounded-circle'>
                            <br><small class="mt-0"><small class="mt-0"><?php echo $usuario->user;?></small></small>
                        </td>
                        <td><?php echo $usuario->nombre;?><br>
                            <small><?php echo $usuario->correo;?></small>
                        </td>
                        <td><?php echo $usuario->telefono;?></td>
                        <td><?php echo $usuario->admin ? "Adm." : "Gal.";?></td>
                        <td class="ico-acciones">
                            <a name="" id="" title="Editar" class="btn btn-success btn-sm bi-pencil"
                                href="<?php echo base_url('control/usuarios/editar/' . $usuario->id);?>"> Editar</a>
                            <form style="display: inline;"
                                action="<?php echo base_url('control/usuarios/' . $usuario->id);?>" method="POST">
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
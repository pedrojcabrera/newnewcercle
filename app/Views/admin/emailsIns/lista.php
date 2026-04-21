<?php echo $this->extend('admin/plantillas/layout');?>
<?php echo $this->section('contenido');?>

<div class="container">
    <h3 class="text-center">Listas de Espera</h3>
    <div class="botones-superiores">
        <div class="boton-agregar">
        </div>
        <div class="boton-cancelar">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-md" href="<?php echo base_url('dashboard');
?>" role="button" title="Cancelar"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Evento</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emails as $email): ?>
                    <tr class="align-middle">
                        <td style="white-space: nowrap;
                              font-size:.8em;
                              text-align: center;
                              ">
                            <?php echo date('d-m-Y', strtotime($email->fecha));?>
                            <br>
                            <?php echo date('H:i', strtotime($email->fecha)) . " h";?>
                        </td>
                        <td><?php echo $email->titulo;?></td>
                        <td class="text-start">
                            <p class="small mb-0"><b><?php echo trim($email->nombre . " " . $email->apellidos);?></b>
                            </p>
                            <p class="small mb-0"><i class="bi bi-envelope-fill"></i> <?php echo esc($email->email);?></p>
                            <p class="small mb-0"><i class="bi bi-telephone-fill"></i> <?php echo esc($email->telefono);?></p>
                        </td>
                        <td class="text-end ico-acciones">
                            <?php if (! $email->inscrito): ?>
                            <form style="display: inline;"
                                action="<?php echo base_url('control/emailsIns/' . $email->id);?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" title="Inscribir" class="btn btn-primary btn-sm"
                                    aria-label="Inscribir"><i class="bi bi-check-circle-fill"></i></button>
                            </form>
                            <?php endif; ?>
                            <form style="display: inline;"
                                action="<?php echo base_url('control/emailsIns/' . $email->id);?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Borrar" class="btn btn-danger btn-sm"
                                    aria-label="Eliminar"
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

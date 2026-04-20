<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<div class="container correos-predefinidos">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Nueva definición de correo" class="btn btn-primary btn-md"
                  href="<?php echo base_url('control/correos/nuevo'); ?>" role="button"><i class="bi bi-check-lg"></i> Agregar nuevo correo</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <!-- <table class="table table-bordered table-striped table-secondary mt-3" id="datatable"> -->
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">Correo</th>
                        <th class="text-center" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($correos as $correo): ?>
<?php $date = new DateTime($correo->fecha); ?>
                    <tr>
                        <td>
                            <p>
                                <b>
                                    <?php echo esc(trim($correo->asunto)); ?>
                                </b>
                                <br>
                                <small id="helpId" class="form-text text-dark">
                                    <?php echo $date->format('d-m-Y (H:i)') . 'h'; ?>
                                </small>
                            </p>
                            <hr>
                            <?php echo $correo->texto_sanitizado ?? ''; ?>
                            <hr class="text-succcess">
                        </td>
                        <td class="ico-acciones">
                            <a title="Lista Envíos" class="btn btn-primary btn-sm"
                                aria-label="Lista de envíos"
                                href="<?php echo base_url('control/correos/listado/' . $correo->id); ?>"><i class="bi bi-card-checklist"></i></a>
                            <a name="" title="Cartear" id="" class="btn btn-warning btn-sm"
                                aria-label="Cartear"
                                href="<?php echo base_url('control/correos/cartear/' . $correo->id); ?>"><i class="bi bi-envelope-paper-fill"></i></a>
                            <a name="" title="Editar" id="" class="btn btn-success btn-sm"
                                aria-label="Editar"
                                href="<?php echo base_url('control/correos/editar/' . $correo->id); ?>"><i class="bi bi-pencil-fill"></i></a>
                            <form action="<?php echo base_url('control/correos/' . $correo->id); ?>" method="POST">
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

<?php echo $this->endSection(); ?>

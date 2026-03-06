<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<div class="container">
    <div class="container">
        <div class="botones-superiores">
            <div class="boton-agregar">
                <a name="" id="" title="Crear" class="btn btn-primary btn-sm bi-person-plus"
                    href="<?php echo base_url('control/correos/nuevo'); ?>" role="button">
                    Nueva definición de correo</a>
            </div>
            <div class="boton-cancelar">
                <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
                    href="<?php echo base_url('dashboard'); ?>" role="button"
                    title="Cancelar">
                    Cancelar</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive-sm">
                <!-- <table class="table table-bordered table-striped table-secondary mt-3" id="datatable"> -->
                <table class="miTabla mt-3" id="datatable">
                    <thead>
                        <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">Correo</th>
                            <th class="text-center" scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($correos as $correo): ?>
<?php $date = new DateTime($correo->fecha); ?>
                        <tr>
                            <td class="id_lista"><span><?php echo $correo->id; ?></span></td>
                            <td>
                                <p>
                                    <b>
                                        <?php echo trim($correo->asunto); ?>
                                    </b>
                                    <br>
                                    <small id="helpId" class="form-text text-dark">
                                        <?php echo $date->format('d-m-Y (H:i)') . 'h'; ?>
                                    </small>
                                </p>
                                <hr>
                                <?php echo trim($correo->texto); ?>
                                <hr class="text-succcess">
                            </td>
                            <td class="ico-acciones">
                                <a title="Lista Envíos" class="btn btn-primary btn-sm bi-card-checklist"
                                    href="<?php echo base_url('control/correos/listado/' . $correo->id); ?>"> E-mails</a>
                                <a name="" title="Cartear" id="" class="btn btn-warning btn-sm bi-envelope-paper"
                                    href="<?php echo base_url('control/correos/cartear/' . $correo->id); ?>"> Cartear</a>
                                <a name="" title="Editar" id="" class="btn btn-success btn-sm bi-pencil"
                                    href="<?php echo base_url('control/correos/editar/' . $correo->id); ?>"> Editar</a>
                                <form action="<?php echo base_url('control/correos/' . $correo->id); ?>" method="POST">
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
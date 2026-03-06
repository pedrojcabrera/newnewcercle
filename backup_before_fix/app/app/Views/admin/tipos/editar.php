<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<form action="<?php echo base_url('control/tipos/modificar/' . $id, $_SERVER['REQUEST_SCHEME']); ?>" method="POST">
    <input type="hidden" name="_method" value="PUT">
    <?php echo csrf_field(); ?>
    <div class="row col-6 my-5 mx-auto">
        <div class="col-sm-10 mb-1 mb-sm-0 mx-auto">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="eventonombre" class="form-label">Evento nombre:</label>
                        <input type="text" class="form-control" name="eventonombre" id="eventonombre"
                            value="<?php echo $tipo->eventonombre; ?>" required></input>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('eventonombre'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="eventotipo" class="form-label">Evento tipo:</label>
                        <input type="text" class="form-control" name="eventotipo" id="eventotipo"
                            value="<?php echo $tipo->eventotipo; ?>" required></input>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('eventotipo'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mx-3 mt-3">
                <a name="cancelar" id="cancelar" class="btn btn-success btn-md bi-box-arrow-left"
                    href="<?php echo base_url('control/tipos', $_SERVER['REQUEST_SCHEME']); ?>" role="button"
                    title="Cancelar">
                    Cancelar</a>
                <button type="submit" class="btn btn-primary btn-md bi-person-check-fill" title="Modificar">
                    Modificar</button>
            </div>
        </div>

    </div>
</form>


<?php echo $this->endSection(); ?>
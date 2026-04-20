<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<?php $errorEventoNombre = validation_show_error('eventonombre'); ?>
<?php $errorEventoTipo = validation_show_error('eventotipo'); ?>

<form action="<?php echo base_url('control/tipos/crear'); ?>" method="POST">
    <!-- <input type="hidden" name="_method" value="PUT"> -->
    <?php echo csrf_field(); ?>
    <div class="row col-6 my-5 mx-auto">
        <div class="col-sm-10 mb-1 mb-sm-0 mx-auto">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="eventonombre" class="form-label">Evento nombre:</label>
                        <input type="text" class="form-control<?php echo $errorEventoNombre ? ' is-invalid' : ''; ?>" name="eventonombre" id="eventonombre"
                            value="<?php echo set_value('eventonombre'); ?>" required></input>
                        <div class="invalid-feedback">
                            <?php echo $errorEventoNombre; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="eventotipo" class="form-label">Evento tipo:</label>
                        <input type="text" class="form-control<?php echo $errorEventoTipo ? ' is-invalid' : ''; ?>" name="eventotipo" id="eventotipo"
                            value="<?php echo set_value('eventotipo'); ?>" required></input>
                        <div class="invalid-feedback">
                            <?php echo $errorEventoTipo; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mx-3 mt-3">
                <a name="cancelar" id="cancelar" class="btn btn-success btn-md"
                    href="<?php echo base_url('control/tipos'); ?>" role="button"
                    title="Cancelar"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
                <button type="submit" class="btn btn-primary btn-md" title="Agregar"><i class="bi bi-check-lg"></i> Grabar un nuevo tipo de evento</button>
            </div>
        </div>

    </div>
</form>


<?php echo $this->endSection(); ?>

<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<?php $errorTexto = validation_show_error('texto'); ?>
<?php $errorEnlace = validation_show_error('enlace'); ?>

<form action="<?php echo base_url('control/enlaces/crear'); ?>" method="POST">
    <!-- <input type="hidden" name="_method" value="PUT"> -->
    <?php echo csrf_field(); ?>
    <div class="row my-5 mx-2">
        <div class="col-sm-10 mb-1 mb-sm-0 mx-auto">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="texto" class="form-label">Texto:</label>
                        <textarea class="form-control<?php echo $errorTexto ? ' is-invalid' : ''; ?>" name="texto" id="texto" value="<?php echo set_value('texto'); ?>"
                            rows="5" placeholder="Texto" required></textarea>
                        <div class="invalid-feedback">
                            <?php echo $errorTexto; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="enlace" class="form-label">Enlace</label>
                        <input type="url" class="form-control<?php echo $errorEnlace ? ' is-invalid' : ''; ?>" name="enlace" id="enlace"
                            value="<?php echo set_value('enlace'); ?>" placeholder="Enlace">
                        <div class="invalid-feedback">
                            <?php echo $errorEnlace; ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mx-3 mt-3">
                        <a name="cancelar" id="cancelar" class="btn btn-success btn-md"
                            href="<?php echo base_url('control/enlaces'); ?>" role="button"
                            title="Cancelar"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary btn-md" title="Grabar"><i class="bi bi-check-lg"></i> Grabar nuevo enlace</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>


<?php echo $this->endSection(); ?>

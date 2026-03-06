<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>
<form action="<?php echo base_url('control/enlaces/modificar/' . $id); ?>" method="POST">
    <input type="hidden" name="_method" value="PUT">
    <?php echo csrf_field(); ?>
    <div class="row my-5 mx-2">
        <div class="col-sm-10 mb-1 mb-sm-0 mx-auto">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="texto" class="form-label">Texto:</label>
                        <textarea class="form-control" name="texto" id="texto" rows="5" placeholder="Texto"
                            required><?php echo $enlace->texto; ?></textarea>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('texto'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="enlace" class="form-label">Enlace</label>
                        <input type="url" class="form-control" name="enlace" id="enlace"
                            value="<?php echo $enlace->enlace; ?>" placeholder="Enlace">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('enlace'); ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mx-3 mt-3">
                        <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
                            href="<?php echo base_url('control/enlaces'); ?>" role="button"
                            title="Cancelar">
                            Cancelar</a>
                        <button type="submit" class="btn btn-primary btn-sm bi-person-check-fill" title="Modificar">
                            Modificar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>


<?php echo $this->endSection(); ?>
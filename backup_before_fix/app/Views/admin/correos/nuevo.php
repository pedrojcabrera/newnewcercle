<?php echo $this->extend('admin/plantillas/layout');?>
<?php echo $this->section('contenido');?>

<form action="<?php echo base_url('control/correos/crear', $_SERVER['REQUEST_SCHEME']);?>" method="POST">
    <!-- <input type="hidden" name="_method" value="PUT"> -->
    <?php echo csrf_field();?>
    <div class="row my-5 mx-2">
        <div class="col-sm-10 mb-1 mb-sm-0 mx-auto">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto:</label>
                        <input type="text" class="form-control" name="asunto" id="asunto" placeholder="Asunto">
                    </div>
                    <div class="mb-3">
                        <label for="editor" class="form-label">Texto</label>
                        <textarea class="form-control" name="texto" id="texto" rows="15"></textarea>
                        
                    </div>
                </div>
                <div class="d-flex justify-content-between mx-3 mt-3">
                    <a name="cancelar" id="cancelar" class="btn btn-success btn-md bi-box-arrow-left"
                        href="<?php echo base_url('control/correos', $_SERVER['REQUEST_SCHEME']);?>" role="button"
                        title="Cancelar"> Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-md bi-person-check-fill" title="Crear">
                        Agregar</button>
                </div>
            </div>
        </div>

</form>

<?php echo $this->endSection();?>

<?php echo $this->section('masJS');?>
<?php echo $this->endSection();?>
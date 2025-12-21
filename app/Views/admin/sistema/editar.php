<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<form action="<?php echo base_url('control/sistema/modificar', $_SERVER['REQUEST_SCHEME']); ?>" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <?php echo csrf_field(); ?>
    <input type="hidden" name='pinterest' value="<?php echo $sistema->pinterest; ?>">

    <div class="container col-10 mx-auto my-2 center">
        <div class="card-body">
            <div class="mb-3">
                <div class="text-center">
                    <?php if (file_exists('recursos/imagenes/' . $banner)): ?>
                    <img src="<?php echo base_url('recursos/imagenes/' . $banner, $_SERVER['REQUEST_SCHEME']); ?>"
                        class="img-fluid">
                    <?php endif; ?>
                </div>
                <label for="banner" class="form-label">Seleccione el Banner de Portada, actualmente es
                    <?php echo $banner; ?></label>
                <input type="file" class="form-control" name="banner" id="banner"
                    accept="image/png, image/jpg, image/jpeg" placeholder="Seleccione el Banner de Portada">
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" value="<?php echo $sistema->visible; ?>" name="visible"
                    id="visible"                                 <?php echo($sistema->visible) ? 'checked' : ''; ?> <label class="form-check-label"
                    for="visible">Visibilizar el
                Banner en Portada</label>
            </div>
            <hr>
            <div class="mb-3">
                <label for="noticia" class="form-label">Noticia de Portada:</label>
                <input type="text" class="form-control" name="noticia" id="noticia"
                    value="<?php echo $sistema->noticia; ?>" placeholder="Noticia de Portada">
            </div>
            <div class="mb-3">
                <label for="texto" class="form-label">Texto de Portada</label>
                <textarea class="form-control" name="texto" id="texto" rows="5"><?php echo $sistema->texto; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" id="direccion"
                    value="<?php echo $sistema->direccion; ?>" placeholder="Dirección">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" id="correo"
                    value="<?php echo $sistema->correo; ?>" placeholder="Correo">
                <div class="linea_msg_error">
                    <?php echo validation_show_error('correo'); ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="facebook" class="form-label">Facebook (url):</label>
                <input type="url" class="form-control" name="facebook" id="facebook"
                    value="<?php echo $sistema->facebook; ?>" placeholder="Facebook (url)">
                <div class="linea_msg_error">
                    <?php echo validation_show_error('facebook'); ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="youtube" class="form-label">Youtube (url):</label>
                <input type="url" class="form-control" name="youtube" id="youtube"
                    value="<?php echo $sistema->youtube; ?>" placeholder="Youtube (url)">
                <div class="linea_msg_error">
                    <?php echo validation_show_error('youtube'); ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="instagram" class="form-label">Instagram (url):</label>
                <input type="url" class="form-control" name="instagram" id="instagram"
                    value="<?php echo $sistema->instagram; ?>" placeholder="Instagram (url)">
                <div class="linea_msg_error">
                    <?php echo validation_show_error('instagram'); ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="elcaballete" class="form-label">El Caballete (url):</label>
                <input type="url" class="form-control" name="elcaballete" id="elcaballete"
                    value="<?php echo $sistema->elcaballete; ?>" placeholder="El Caballete (url)">
                <div class="linea_msg_error">
                    <?php echo validation_show_error('elcaballete'); ?>
                </div>
            </div>

            <div class="d-flex justify-content-between mx-3 mt-3">
                <a name="cancelar" id="cancelar" class="btn btn-success btn-md bi-box-arrow-left"
                    href="<?php echo base_url('dashboard', $_SERVER['REQUEST_SCHEME']); ?>" role="button"
                    title="Cancelar"> Cancelar</a>
                <button type="submit" class="btn btn-primary btn-md bi-person-check-fill" title="Modificar">
                    Modificar</button>
            </div>
        </div>
    </div>
</form>


<?php echo $this->endSection(); ?>
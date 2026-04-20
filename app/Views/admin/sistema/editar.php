<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<?php $errorCorreo = validation_show_error('correo'); ?>
<?php $errorFacebook = validation_show_error('facebook'); ?>
<?php $errorYoutube = validation_show_error('youtube'); ?>
<?php $errorInstagram = validation_show_error('instagram'); ?>
<?php $errorElCaballete = validation_show_error('elcaballete'); ?>
<?php $bannerActual = trim((string) $banner); ?>
<?php $ubicacionBanner = 'recursos/imagenes/'; ?>
<?php $bannerDirFisico = FCPATH . $ubicacionBanner; ?>
<?php $bannerArchivoReal = ''; ?>
<?php $bannerValorFormulario = $sistema->pinterest; ?>
<?php if ($bannerActual !== ''): ?>
<?php $rutasPosibles = []; ?>
<?php $rutasPosibles[] = $bannerActual; ?>
<?php $urlPath = parse_url($bannerActual, PHP_URL_PATH); ?>
<?php if (is_string($urlPath) && $urlPath !== ''): ?>
<?php $rutasPosibles[] = basename($urlPath); ?>
<?php endif; ?>
<?php $rutasPosibles[] = basename($bannerActual); ?>
<?php $rutasPosibles = array_values(array_unique(array_filter(array_map('trim', $rutasPosibles)))); ?>
<?php foreach ($rutasPosibles as $rutaPosible): ?>
<?php if ($rutaPosible !== '' && is_file($bannerDirFisico . $rutaPosible)): ?>
<?php $bannerArchivoReal = $rutaPosible; ?>
<?php break; ?>
<?php endif; ?>
<?php $baseSinExtension = pathinfo($rutaPosible, PATHINFO_FILENAME); ?>
<?php if ($baseSinExtension !== ''): ?>
<?php $coincidencias = glob($bannerDirFisico . $baseSinExtension . '.*'); ?>
<?php if (!empty($coincidencias)): ?>
<?php $bannerArchivoReal = basename($coincidencias[0]); ?>
<?php break; ?>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if ($bannerArchivoReal !== ''): ?>
<?php $bannerValorFormulario = $bannerArchivoReal; ?>
<?php endif; ?>
<?php $rutaBannerActual = $bannerArchivoReal !== '' ? base_url($ubicacionBanner . $bannerArchivoReal) : ''; ?>
<?php $nombreMostrar = $bannerArchivoReal !== '' ? $bannerArchivoReal : ($bannerActual !== '' ? basename((string) (parse_url($bannerActual, PHP_URL_PATH) ?: $bannerActual)) : 'Sin banner asociado'); ?>
<?php $ubicacionMostrar = $bannerArchivoReal !== '' ? $ubicacionBanner . $bannerArchivoReal : ($bannerActual !== '' ? 'Pendiente de reemplazo en ' . $ubicacionBanner : 'Sin ubicación'); ?>
<?php $visibleActual = old('visible'); ?>
<?php $visibleMarcado = $visibleActual === null ? ((int) $sistema->visible === 1) : ((string) $visibleActual === '1'); ?>

<form action="<?php echo base_url('control/sistema/modificar'); ?>" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <?php echo csrf_field(); ?>
    <input type="hidden" name='pinterest' value="<?php echo esc($bannerValorFormulario); ?>">

    <div class="container col-10 mx-auto my-2 center">
        <div class="card-body">
            <div class="mb-3">
                <div class="row g-3 align-items-start border rounded p-2 bg-light-subtle">
                    <div class="col-12 col-md-5 text-center" id="bannerPreviewWrap"
                        style="<?= $rutaBannerActual === '' ? 'display:none;' : '' ?>">
                        <img id="bannerPreview"
                            src="<?= esc($rutaBannerActual) ?>"
                            class="img-fluid rounded border"
                            alt="Banner actual">
                    </div>
                    <div class="col-12 col-md-7">
                        <h6 class="mb-2">Banner asociado</h6>
                        <p class="mb-1"><strong>Nombre:</strong> <span id="bannerNombre"><?= esc($nombreMostrar) ?></span></p>
                        <p class="mb-0"><strong>Ubicación:</strong> <span id="bannerUbicacion"><?= esc($ubicacionMostrar) ?></span></p>
                    </div>
                </div>
                <label for="banner" class="form-label">Seleccione el Banner de Portada, actualmente es
                    <?php echo esc($nombreMostrar); ?></label>
                <input type="file" class="form-control" name="banner" id="banner"
                    accept="image/png, image/jpg, image/jpeg" placeholder="Seleccione el Banner de Portada">
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" value="1" name="visible"
                    id="visible" <?php echo $visibleMarcado ? 'checked' : ''; ?>>
                <label class="form-check-label" for="visible">Visibilizar el
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
                <input type="email" class="form-control<?php echo $errorCorreo ? ' is-invalid' : ''; ?>" name="correo" id="correo"
                    value="<?php echo $sistema->correo; ?>" placeholder="Correo">
                <div class="invalid-feedback">
                    <?php echo $errorCorreo; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="facebook" class="form-label">Facebook (url):</label>
                <input type="url" class="form-control<?php echo $errorFacebook ? ' is-invalid' : ''; ?>" name="facebook" id="facebook"
                    value="<?php echo $sistema->facebook; ?>" placeholder="Facebook (url)">
                <div class="invalid-feedback">
                    <?php echo $errorFacebook; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="youtube" class="form-label">Youtube (url):</label>
                <input type="url" class="form-control<?php echo $errorYoutube ? ' is-invalid' : ''; ?>" name="youtube" id="youtube"
                    value="<?php echo $sistema->youtube; ?>" placeholder="Youtube (url)">
                <div class="invalid-feedback">
                    <?php echo $errorYoutube; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="instagram" class="form-label">Instagram (url):</label>
                <input type="url" class="form-control<?php echo $errorInstagram ? ' is-invalid' : ''; ?>" name="instagram" id="instagram"
                    value="<?php echo $sistema->instagram; ?>" placeholder="Instagram (url)">
                <div class="invalid-feedback">
                    <?php echo $errorInstagram; ?>
                </div>
            </div>
            <div class="mb-3">
                <label for="elcaballete" class="form-label">El Caballete (url):</label>
                <input type="url" class="form-control<?php echo $errorElCaballete ? ' is-invalid' : ''; ?>" name="elcaballete" id="elcaballete"
                    value="<?php echo $sistema->elcaballete; ?>" placeholder="El Caballete (url)">
                <div class="invalid-feedback">
                    <?php echo $errorElCaballete; ?>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary btn-md" title="Modificar"><i class="bi bi-floppy-fill"></i> Guardar cambios</button>
            </div>
        </div>
    </div>
</form>


<?php echo $this->endSection(); ?>

<?php echo $this->section('masJS'); ?>
<script>
(function() {
    var inputBanner = document.getElementById('banner');
    var preview = document.getElementById('bannerPreview');
    var previewWrap = document.getElementById('bannerPreviewWrap');
    var nombre = document.getElementById('bannerNombre');
    var ubicacion = document.getElementById('bannerUbicacion');

    var nombreOriginal = <?= json_encode($nombreMostrar) ?>;
    var ubicacionOriginal = <?= json_encode($ubicacionMostrar) ?>;
    var srcOriginal = <?= json_encode($rutaBannerActual) ?>;
    var baseUbicacion = <?= json_encode($ubicacionBanner) ?>;
    var objectUrl = null;

    if (!inputBanner || !preview || !previewWrap || !nombre || !ubicacion) {
        return;
    }

    inputBanner.addEventListener('change', function() {
        var file = this.files && this.files[0] ? this.files[0] : null;

        if (objectUrl) {
            URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        }

        if (!file) {
            nombre.textContent = nombreOriginal;
            ubicacion.textContent = ubicacionOriginal;

            if (srcOriginal) {
                preview.src = srcOriginal;
                previewWrap.style.display = '';
            } else {
                preview.removeAttribute('src');
                previewWrap.style.display = 'none';
            }

            return;
        }

        nombre.textContent = file.name;
        ubicacion.textContent = baseUbicacion + file.name;
        objectUrl = URL.createObjectURL(file);
        preview.src = objectUrl;
        previewWrap.style.display = '';
    });
})();
</script>
<?php echo $this->endSection(); ?>

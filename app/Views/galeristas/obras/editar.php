<?php echo $this->extend('galeristas/layout') ?>
<?php echo $this->section('contenido') ?>
<?php
 /*
   $router = service('router');
   dd($router->controllerName(),$router->methodName());
*/
?>
<form action="<?php echo base_url('galeristas/modificar/' . $obra->id) ?>" method="post"
    enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <h4 style="text-align: center; margin-top: .5rem;"><?php echo $usuario->nombre ?></h4>

    <input type="hidden" name="_method" value="PUT">

    <div class="card" style="margin-left: 1rem; margin-right: 1rem;">
        <div class="container-p">
            <div class="card-body">
                <div>
                    <label for="autor" class="form-label">Autor:</label>
                    <input type="text" class="form-control" name="autor" value="<?php echo $obra->autores ?>" id="autor"
                        placeholder="Rellenar solamente si el autor no es el galerista">
                </div>
                <div>
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="titulo" value="<?php echo $obra->titulo ?>"
                        id="titulo" placeholder="Título de la obra">
                    <span class="text-danger">
                        <?php echo(session('errors.titulo')) ? session('errors.titulo') : '' ?>
                    </span>
                </div>
                <div>
                    <label for="tecnica" class="form-label">Técnica:</label>
                    <input type="text" class="form-control" name="tecnica" value="<?php echo $obra->tecnica ?>"
                        id="tecnica" placeholder="Técnica usada">
                    <span class="text-danger">
                        <?php echo(session('errors.tecnica')) ? session('errors.tecnica') : '' ?>
                    </span>
                </div>
                <div>
                    <label for="soporte" class="form-label">Soporte:</label>
                    <input type="text" class="form-control" name="soporte" value="<?php echo $obra->soporte ?>"
                        id="soporte" placeholder="Soporte empleado">
                    <span class="text-danger">
                        <?php echo(session('errors.soporte')) ? session('errors.soporte') : '' ?>
                    </span>
                </div>
                <div>
                    <label for="medidas" class="form-label">Medidas</label>
                    <input type="text" class="form-control" name="medidas" value="<?php echo $obra->medidas ?>"
                        id="medidas" placeholder="Medidas">
                    <span class="text-danger">
                        <?php echo(session('errors.medidas')) ? session('errors.medidas') : '' ?>
                    </span>
                </div>
                <div>
                    <?php
                    $rutaImagenRelativa = 'galerias/' . $obra->id_user . '/' . $obra->id . '.jpg';
                    $rutaImagenFisica = FCPATH . $rutaImagenRelativa;
                    ?>
                    <label for="imagen" class="form-label">Imagen de la Obra</label>
                    <label for="imagen" class="d-inline-block mb-2" style="cursor: pointer;">
                        <?php if (is_file($rutaImagenFisica)): ?>
                        <img id="previewImagenObra" src="<?= base_url($rutaImagenRelativa) ?>" alt="Imagen actual de la obra"
                            class="img-thumbnail" style="max-width: 180px; max-height: 180px; object-fit: cover;">
                        <?php else: ?>
                        <div id="previewImagenObra"
                            class="img-thumbnail d-flex align-items-center justify-content-center text-muted"
                            style="width: 180px; height: 180px;">
                            Sin imagen
                        </div>
                        <?php endif; ?>
                    </label>
                    <small class="d-block text-muted mb-2">Haz clic en la imagen para cambiarla.</small>
                    <small id="nombreArchivoImagen" class="d-block text-muted mb-2">Archivo actual: <?= esc(basename($rutaImagenRelativa)) ?></small>
                    <input type="file" class="visually-hidden" name="imagen" id="imagen" accept="image/jpeg,image/jpg"
                        placeholder="Seleccione la imagen">
                    <span class="text-danger">
                        <?php echo(session('errors.imagen')) ? session('errors.imagen') : '' ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <label for="ano" class="form-label">Año de creación de la Obra</label>
                    <select class="form-select form-select-sm" name="ano" id="ano"
                        <?php for ($i = $year_inicio; $i <= $year_hoy; $i++): ?> <option
                        <?php echo($i == $obra->ano) ? 'selected' : '' ?>><?php echo str_pad($i, 4, '0', STR_PAD_LEFT) ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="premios" class="form-label">Premios obtenidos</label>
                    <textarea class="form-control" name="premios" id="premios"
                        rows="5"><?php echo $obra->premios ?></textarea>
                </div>
                <div>
                    <label for="comentarios" class="form-label">Comentarios</label>
                    <textarea class="form-control" name="comentarios" id="comentarios"
                        rows="5"><?php echo $obra->comentarios ?></textarea>
                </div>
                <div>
                    <label for="precio" class="form-label">Poner precio, solo si está en venta</label>
                    <input type="number" class="form-control" name="precio" value="<?php echo $obra->precio ?>"
                        id="precio" min="0" max="9999999" required>
                    <span class="text-danger">
                        <?php echo(session('errors.precio')) ? session('errors.precio') : '' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 text-end" style="margin-right: 1rem;">
        <a href="<?php echo base_url('galeristas/cancelar') ?>"
            class="btn btn-success btn-sm bi-box-arrow-left" name="btn_cancelar" title="Cancelar"> Cancelar</a>
        <button type="submit" class="btn btn-primary btn-sm bi-check-lg" name="btn_grabar" title="Guardar cambios">
            Grabar Cambios</button>
    </div>
</form>
<?php echo $this->endSection() ?>

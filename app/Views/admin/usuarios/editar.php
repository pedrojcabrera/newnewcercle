<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<?php $errorUsuario = validation_show_error('usuario'); ?>
<?php $errorPassword = validation_show_error('password'); ?>
<?php $errorRepetir = validation_show_error('repetir'); ?>
<?php $errorNombre = validation_show_error('nombre'); ?>
<?php $errorCorreo = validation_show_error('correo'); ?>
<?php $errorTelefono = validation_show_error('telefono'); ?>
<?php $errorFoto = validation_show_error('foto'); ?>
<?php $errorTexto = validation_show_error('texto'); ?>
<?php $errorWeb = validation_show_error('web'); ?>
<?php $errorEnlaces = validation_show_error('enlaces'); ?>

<div class="row my-3 mx-3">
    <div class="col-sm-6 mb-3 mb-sm-0">
        <div class="container">
            <form action="<?php echo base_url('control/usuarios/modificar/' . $id); ?>"
                method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">usuario:</label>
                        <input type="text" class="form-control<?php echo $errorUsuario ? ' is-invalid' : ''; ?>" name="usuario" id="usuario"
                            value="<?php echo $usuario->user; ?>" placeholder="clave de acceso" required>
                        <div class="invalid-feedback">
                            <?php echo $errorUsuario; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control<?php echo $errorPassword ? ' is-invalid' : ''; ?>" name="password"
                            value="<?php echo set_value('password'); ?>" id="password" placeholder="Password">
                        <div class="invalid-feedback">
                            <?php echo $errorPassword; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="repetir" class="form-label">Repetir Password</label>
                        <input type="password" class="form-control<?php echo $errorRepetir ? ' is-invalid' : ''; ?>" name="repetir"
                            value="<?php echo set_value('repetir'); ?>" id="repetir" placeholder="Repite Password">
                        <small id="helpId" class="form-text text-muted">Rellenar los campos de password solo si se desea
                            modificarlos</small>
                        <div class="invalid-feedback">
                            <?php echo $errorRepetir; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control<?php echo $errorNombre ? ' is-invalid' : ''; ?>" name="nombre" id="nombre"
                            value="<?php echo $usuario->nombre; ?>" placeholder="Nombre del usuario" required>
                        <div class="invalid-feedback">
                            <?php echo $errorNombre; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control<?php echo $errorCorreo ? ' is-invalid' : ''; ?>" name="correo" id="correo" placeholder="Correo"
                            value="<?php echo $usuario->correo; ?>">
                        <div class="invalid-feedback">
                            <?php echo $errorCorreo; ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control<?php echo $errorTelefono ? ' is-invalid' : ''; ?>" name="telefono" id="telefono"
                            value="<?php echo $usuario->telefono; ?>" placeholder="Teléfono">
                        <div class="invalid-feedback">
                            <?php echo $errorTelefono; ?>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="<?php echo $usuario->admin; ?>"
                            <?php echo $usuario->admin ? 'checked' : ''; ?> name="admin" id="admin">
                        <label class="form-check-label" for="admin">
                            Ese usuario es Administrador<br><span class="fst-italic">Los Administradores pueden ser
                                Galeristas</span>
                        </label>
                    </div>
                </div>
        </div>
    </div>
    <div class="col-sm-6 mb-3 mb-sm-0">
        <div class="container">
            <div class="card-body">
                <?php if (file_exists($fotoUser)): ?>
                <div class="para_borrar_foto">
                    <img src="<?php echo base_url($fotoUser); ?>" width='50' class='img-fluid rounded-circle'>
                    <div class="para_borrar_check">
                        <label class="form-check-label" for="borrar_foto">
                            Borrar Foto<br>
                            <small id="helpId" class="form-text text-muted">Marcar para eliminar foto.</small>
                        </label>
                        <input class="form-check-input" type="checkbox" name="borrar_foto" id="borrar_foto">
                    </div>
                </div>
                <hr>
                <?php endif; ?>
                <div class="mb-3">
                    <?php
                    $rutaFotoActualRelativa = file_exists($fotoUser) ? $fotoUser : 'fotosUsuarios/sinfoto.jpg';
                    $rutaFotoActualFisica = FCPATH . $rutaFotoActualRelativa;
                    ?>
                    <label for="foto" class="form-label">Foto del usuario</label>
                    <label for="foto" class="d-inline-block mb-2" style="cursor: pointer;">
                        <?php if (is_file($rutaFotoActualFisica)): ?>
                        <img id="previewFotoUsuario" src="<?php echo base_url($rutaFotoActualRelativa); ?>" alt="Foto actual del usuario"
                            class="img-thumbnail" style="max-width: 180px; max-height: 180px; object-fit: cover;">
                        <?php else: ?>
                        <div id="previewFotoUsuario" class="img-thumbnail d-flex align-items-center justify-content-center text-muted"
                            style="width: 180px; height: 180px;">
                            Sin foto
                        </div>
                        <?php endif; ?>
                    </label>
                    <small class="d-block text-muted mb-2">Haz clic en la imagen para cambiar la foto.</small>
                    <input type="file" class="visually-hidden" name="foto" id="foto"
                        value="<?php echo set_value('foto'); ?>" accept="image/jpeg,image/jpg" placeholder="Seleccione la Foto">
                    <?php if ($errorFoto): ?>
                    <div class="text-danger small">
                        <?php echo $errorFoto; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="texto" class="form-label">Texto orientativo sobre el usuario</label>
                    <textarea class="form-control<?php echo $errorTexto ? ' is-invalid' : ''; ?>" name="texto" id="texto"
                        rows="5"><?php echo $usuario->texto; ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo $errorTexto; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="web" class="form-label">Web</label>
                    <input type="url" class="form-control<?php echo $errorWeb ? ' is-invalid' : ''; ?>" name="web" id="web" value="<?php echo $usuario->web; ?>"
                        placeholder="Web">
                    <div class="invalid-feedback">
                        <?php echo $errorWeb; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="enlaces" class="form-label">En cada línea un enlace web</label>
                    <textarea class="form-control<?php echo $errorEnlaces ? ' is-invalid' : ''; ?>" name="enlaces" id="enlaces"
                        rows="5"><?php echo $usuario->enlaces; ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo $errorEnlaces; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mx-3 mt-3">
        <a name="cancelar" id="cancelar" class="btn btn-success btn-md"
            href="<?php echo base_url('control/usuarios'); ?>" role="button"
            title="Cancelar"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
        <button type="submit" class="btn btn-primary btn-md" title="Modificar"><i class="bi bi-floppy-fill"></i> Guardar cambios</button>
    </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var inputFoto = document.getElementById('foto');
    var preview = document.getElementById('previewFotoUsuario');

    if (!inputFoto || !preview) {
        return;
    }

    inputFoto.addEventListener('change', function () {
        var archivo = inputFoto.files && inputFoto.files[0];
        if (!archivo) {
            return;
        }

        var urlTemporal = URL.createObjectURL(archivo);

        if (preview.tagName === 'IMG') {
            preview.src = urlTemporal;
            return;
        }

        var img = document.createElement('img');
        img.id = 'previewFotoUsuario';
        img.className = 'img-thumbnail';
        img.style.maxWidth = '180px';
        img.style.maxHeight = '180px';
        img.style.objectFit = 'cover';
        img.alt = 'Foto de usuario seleccionada';
        img.src = urlTemporal;
        preview.replaceWith(img);
        preview = img;
    });
});
</script>

<?php echo $this->endSection(); ?>

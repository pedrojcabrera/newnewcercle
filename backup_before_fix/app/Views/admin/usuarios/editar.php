<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<div class="row my-3 mx-3">
    <div class="col-sm-6 mb-3 mb-sm-0">
        <div class="container">
            <form action="<?php echo base_url('control/usuarios/modificar/' . $id, $_SERVER['REQUEST_SCHEME']); ?>"
                method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">usuario:</label>
                        <input type="text" class="form-control" name="usuario" id="usuario"
                            value="<?php echo $usuario->user; ?>" placeholder="clave de acceso" required>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('usuario'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password"
                            value="<?php echo set_value('password'); ?>" id="password" placeholder="Password">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('password'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="repetir" class="form-label">Repetir Password</label>
                        <input type="password" class="form-control" name="repetir"
                            value="<?php echo set_value('repetir'); ?>" id="repetir" placeholder="Repite Password">
                        <small id="helpId" class="form-text text-muted">Rellenar los campos de password solo si se desea
                            modificarlos</small>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('repetir'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre"
                            value="<?php echo $usuario->nombre; ?>" placeholder="Nombre del usuario" required>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('nombre'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo" id="correo" placeholder="Correo"
                            value="<?php echo $usuario->correo; ?>">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('correo'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono"
                            value="<?php echo $usuario->telefono; ?>" placeholder="Teléfono">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('telefono'); ?>
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
                    <label for="foto" class="form-label">Seleccione la Foto</label>
                    <input type="file" class="form-control" name="foto" id="foto"
                        value="<?php echo set_value('foto'); ?>" placeholder="Seleccione la Foto">
                    <div class="linea_msg_error">
                        <?php echo validation_show_error('foto'); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="texto" class="form-label">Texto orientativo sobre el usuario</label>
                    <textarea class="form-control" name="texto" id="texto"
                        rows="5"><?php echo $usuario->texto; ?></textarea>
                    <div class="linea_msg_error">
                        <?php echo validation_show_error('texto'); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="web" class="form-label">Web</label>
                    <input type="url" class="form-control" name="web" id="web" value="<?php echo $usuario->web; ?>"
                        placeholder="Web">
                    <div class="linea_msg_error">
                        <?php echo validation_show_error('web'); ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="enlaces" class="form-label">En cada línea un enlace web</label>
                    <textarea class="form-control" name="enlaces" id="enlaces"
                        rows="5"><?php echo $usuario->enlaces; ?></textarea>
                    <div class="linea_msg_error">
                        <?php echo validation_show_error('enlaces'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mx-3 mt-3">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-md bi-box-arrow-left"
                href="<?php echo base_url('control/usuarios', $_SERVER['REQUEST_SCHEME']); ?>" role="button"
                title="Cancelar"> Cancelar</a>
            <button type="submit" class="btn btn-primary btn-md bi-person-check-fill" title="Modificar">
                Modificar</button>
        </div>
    </div>
    </form>
</div>

<?php echo $this->endSection(); ?>
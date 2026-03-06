<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<form action="<?php echo base_url('control/contactos/modificar/' . $id); ?>" method="POST">
    <input type="hidden" name="_method" value="PUT">
    <?php echo csrf_field(); ?>
    <div class="row my-3 mx-3">
        <div class="col-sm-6 mb-1 mb-sm-0">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre"
                            value="<?php echo $contacto->nombre; ?>" placeholder="Nombre" required>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('nombre'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos:</label>
                        <input type="text" class="form-control" name="apellidos" id="apellidos"
                            value="<?php echo $contacto->apellidos; ?>" placeholder="Apellidos" required>
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('apellidos'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo" id="correo"
                            value="<?php echo $contacto->email; ?>" placeholder="Correo">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('correo'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI o NIF</label>
                        <input type="text" class="form-control" name="dni" id="dni" value="<?php echo $contacto->dni; ?>"
                            placeholder="DNI o NIF">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('dni'); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono"
                            value="<?php echo $contacto->telefono; ?>" placeholder="Teléfono">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('telefono'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 mb-1 mb-sm-0">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" name="direccion" id="direccion"
                            value="<?php echo $contacto->direccion; ?>" placeholder="Dirección">
                    </div>
                    <div class="mb-3">
                        <label for="codpostal" class="form-label">Código Postal</label>
                        <input type="text" class="form-control" name="codpostal" id="codpostal"
                            value="<?php echo $contacto->codpostal; ?>" placeholder="Código Postal">
                    </div>
                    <div class="mb-3">
                        <label for="poblacion" class="form-label">Población</label>
                        <input type="text" class="form-control" name="poblacion" id="poblacion"
                            value="<?php echo $contacto->poblacion; ?>" placeholder="Población">
                    </div>
                    <div class="mb-3">
                        <label for="provincia" class="form-label">Provincia</label>
                        <input type="text" class="form-control" name="provincia" id="provincia"
                            value="<?php echo $contacto->provincia; ?>" placeholder="Provincia">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="mailing" id="mailing"
                            <?php echo $contacto->mailing ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="mailing">
                            Recibe Correos en General
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="invitaciones" id="invitaciones"
                            <?php echo $contacto->invitaciones ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="invitaciones">
                            Recibe invitaciones a Eventos
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="socio" id="socio"
                            <?php echo $contacto->socio ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="socio">
                            Es socio(a)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="alumno" id="alumno"
                            <?php echo $contacto->alumno ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="alumno">
                            Es alumno(a)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pdalumno" id="pdalumno"
                            <?php echo $contacto->pdalumno ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="pdalumno">
                            Es padre o madre de alumno(a)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pintor" id="pintor"
                            <?php echo $contacto->pintor ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="pintor">
                            Es pintor(a);
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="dtaller" id="dtaller"
                            <?php echo $contacto->dtaller ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="dtaller">
                            Es asistente habitual a talleres
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="amigo"
                            <?php echo $contacto->amigo ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="amigo">
                            Es amigo o simpatizante del Cercle
                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mx-3 mt-3">
                <a name="cancelar" id="cancelar" class="btn btn-success btn-md bi-box-arrow-left"
                    href="<?php echo base_url('control/contactos'); ?>" role="button"
                    title="Cancelar">
                    Cancelar</a>
                <button type="submit" class="btn btn-primary btn-md bi-person-check-fill" title="Modificar">
                    Modificar</button>
            </div>
        </div>

    </div>
</form>


<?php echo $this->endSection(); ?>
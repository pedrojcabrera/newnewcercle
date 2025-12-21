<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>



<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Crear" class="btn btn-primary btn-sm bi-person-plus"
                href="<?php echo base_url('control/contactos/nuevo', $_SERVER['REQUEST_SCHEME']); ?>" role="button">
                Nuevo Contacto</a>
        </div>
        <div class="boton-cancelar">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
                href="<?php echo base_url('dashboard', $_SERVER['REQUEST_SCHEME']); ?>" role="button" title="Cancelar">
                Cancelar</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Contacto</th>
                        <th scope="col">Contactar</th>
                        <th scope="col">Calidad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($contactos as $contacto): ?>
                    <tr class="align-middle">
                        <td class="id_lista"><span><?php echo $contacto->id; ?></span></td>
                        <td>
                            <small>
                                <?php echo trim($contacto->nombre . ' ' . $contacto->apellidos); ?>
                                <?php echo ! empty($contacto->dni) ? "<br>DNI: " . $contacto->dni : ""; ?>
                            </small>
                        </td>
                        <td>
                            <small>
                                <?php echo trim($contacto->email) . "<br>Tel: " . trim($contacto->telefono); ?>
                            </small>
                        </td>
                        <td>
                            <ul>
                                <?php foreach ($calidades as $campo => $calidad): ?>
                                <?php if ($contacto->$campo): ?>
                                <li class"calidades"><small id="helpId"
                                        class="form-text text-muted"><?php echo $calidad; ?></small>
                                </li>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td class="text-end ico-acciones">
                            <a name="" title="Editar" id="" class="btn btn-success btn-sm bi-pencil"
                                href="<?php echo base_url('control/contactos/editar/' . $contacto->id); ?>"> Editar</a>
                            <form style="display: inline;"
                                action="<?php echo base_url('control/contactos/' . $contacto->id); ?>" method="POST">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Borrar" class="btn btn-danger btn-sm bi-eraser"
                                    onclick="return confirm('¿ Confirma el borrado ?');"> Borrar</button>
                            </form>
                            <a name="" title="Historia" id="" class="btn btn-secondary btn-sm bi-clock-history"
                                href="<?php echo base_url('control/contactos/historia/' . $contacto->id); ?>">
                                Historia</a>
                            <a name="" title="Inscribir" id="" class="btn btn-warning btn-sm bi-pencil-square"
                                href="<?php echo base_url('control/inscripcionManual/' . $contacto->id); ?>">
                                Inscribir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>
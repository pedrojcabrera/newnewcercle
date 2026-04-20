<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>
<div class="container_contactar">

    <div class="container">

        <?php if (session()->has('errors')): ?>
        <div style="color: red;">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                <li style="font-size: 1.3em;"><?php echo esc($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>


        <?php if (isset($errores) && ! empty($errores)): ?>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errores as $lineaMensaje): ?>
                    <p><?php echo $lineaMensaje; ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($cabeceras) && ! empty($cabeceras)): ?>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php foreach ($cabeceras as $lineaMensaje): ?>
                    <p><?php echo $lineaMensaje; ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php echo form_open(base_url("control/inscripcionManual"), ['id' => 'form-Inscribirse', 'method' => 'post']); ?>
        <?php echo csrf_field(); ?>

        <div id="titulo">
            <h2>
                <?php echo $titulo ?>
                <?= isset($contacto->id) ? ' de un Contacto' : ''; ?>
            </h2>
            <hr>
            <p>
                <?php if($eventos): ?>
                Inscripción manual al evento indicado, por lo que se debe de realizar por personal del Cercle
                <?php else: ?>
                Lo siento, no hay elementos donde inscribirse.
                <?php endif ?>
            </p>
            <hr>
        </div>

        <div class="botones-superiores">
            <div class="boton-agregar">
                <button type="submit" id="inscribir" value="submit" <?= $eventos ? '' : 'disabled' ?>
                    class="btn btn-md btn-primary"><i class="bi bi-person-plus-fill"></i> Inscribir</button>

            </div>
            <div class="boton-cancelar">
                <a name="cancelar" id="cancelar" class="btn btn-success btn-md"
                    href="<?= base_url(isset($contacto)?'control/contactos':'dashboard') ?>"
                    role="button" title="Cancelar"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
            </div>
        </div>

        <input type="hidden" name="id_contacto" value="<?= esc($contacto->id ?? 0) ?>">

        <div class="mb-3">
            <?php $label = ($eventos) ? 'Seleccione el evento donde inscribirse' : 'No hay eventos donde inscribirse'; ?>
            <label for="eventos"><?php echo $label; ?></label>
            <select id="evento" name="evento" class="form-control" required>
                <?php foreach ($eventos as $evento): ?>
                <option value="<?php echo $evento->id; ?>"><?= strtoupper($evento->titulo); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre"
                value="<?= isset($contacto) ? $contacto->nombre : '' ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos"
                value="<?= isset($contacto) ? $contacto->apellidos : '' ?>" required>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" name="email" id="email" placeholder="Correo electrónico"
                value="<?= isset($contacto) ? $contacto->email : '' ?>">
        </div>
        <div class="mb-3">
            <input type="telefono" class="form-control" name="telefono" id="telefono" placeholder="Teléfono"
                value="<?= isset($contacto) ? $contacto->telefono : '' ?>" required>
        </div>
        <div class="mb-3">
            <input type="checkbox" class="form-check-input" name="avisar" id="avisar" checked>
            <label class="form-check-label" for="avisar">Avisar por email de la inscripción</label>
        </div>
        <?php echo form_close(); ?>

    </div>

</div>

<?php echo $this->endSection(); ?>

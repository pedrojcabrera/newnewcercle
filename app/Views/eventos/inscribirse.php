<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container_contactar">

    <div class="container">

        <?php if (isset($errores) && !empty($errores)) : ?>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php foreach ($errores as $lineaMensaje) : ?>
                    <p><?= $lineaMensaje ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($cabeceras) && !empty($cabeceras)) : ?>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php foreach ($cabeceras as $lineaMensaje) : ?>
                    <p><?= $lineaMensaje ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?=form_open(base_url("inscripcion",$_SERVER['REQUEST_SCHEME']),['id'=>'form-Inscribirse', 'method'=>'post'])?>

        <div id="evento_titulo">
            <h2>
                <?=$evento_titulo?>
            </h2>
            <hr>
            <p>Deseo inscribirme al evento indicado, por lo que les autorizo a ponerse en contacto conmigo si lo
                consideran necesario.</p>
            <hr>
        </div>
        <?php $contacto_id = isset($contacto) ? $contacto->id : '0' ?>
        <?php $invitado_id = isset($invitado) ? $invitado->id : '0' ?>
        <?= form_hidden(['id'=>$evento_id, 'titulo'=>$evento_titulo, 'id_contacto' => $contacto_id, 'id_invitado' => $invitado_id]) ?>

        <div class="mb-3">
            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre"
                value="<?=isset($contacto) ? $contacto->nombre : ''?>" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos"
                value="<?=isset($contacto) ? $contacto->apellidos : ''?>" required>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" name="email" id="email" placeholder="Correo electrónico"
                value="<?=isset($contacto) ? $contacto->email : ''?>" required>
        </div>
        <div class="mb-3">
            <input type="telefono" class="form-control" name="telefono" id="telefono" placeholder="Teléfono"
                value="<?=isset($contacto) ? $contacto->telefono : ''?>" required>
        </div>
        <div class="mb-3">
            <div class="g-recaptcha" data-sitekey="<?=env("recaptchaSiteKey")?>">
            </div>
        </div>
        <button type="submit" id="enviar" value="submit" class="btn btn-primary">Enviar</button>

        <?=form_close()?>

    </div>

</div>

<?= $this->endSection()?>

<?= $this->section('masJS')?>

<script src='https://www.google.com/recaptcha/api.js' async defer></script>

<?= $this->endSection()?>
<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="row col-8 my-3 mx-auto">
    <h1 class='text-center'> <?= $asunto ?> </h1>

    <?php if(count($correctos)>0): ?>
    <div class="card col-8 my-2 mx-auto">
        <h2 class="text-center">Listado de Mailings enviados</h2>
        <table class='table'>
            <thead>
                <tr>
                    <th>Contacto</th>
                    <th>email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($correctos as $correcto): ?>
                <tr>
                    <td><?= $correcto['nombre'] ?></td>
                    <td><?= $correcto['email'] ?></td>
                    <td><?= $correcto['telefono'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if(count($errores)>0): ?>
    <div class="card col-8 my-2 mx-auto">
        <h2 class="text-center">Listado de no enviados por Errores</h2>
        <table class='table'>
            <thead>
                <tr>
                    <th>Contacto</th>
                    <th>email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($errores as $error): ?>
                <tr>
                    <td><?= $error['nombre'] ?></td>
                    <td><?= $error['email'] ?></td>
                    <td><?= $error['telefono'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if(count($repetidos)>0): ?>
    <div class="card col-8 my-2 mx-auto">
        <h2 class="text-center">Omitidos por estar ya invitados</h2>
        <table class='table'>
            <thead>
                <tr>
                    <th>Contacto</th>
                    <th>email</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($repetidos as $repetido): ?>
                <tr>
                    <td><?= $repetido['nombre'] ?></td>
                    <td><?= $repetido['email'] ?></td>
                    <td><?= $repetido['telefono'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="mt-3 text-end">
        <a name="cancelar" id="cancelar" class="btn btn-success btn-md"
            href="<?=base_url('control/eventos')?>" role="button" title="Volver"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
    </div>

</div>

<?= $this->endSection() ?>

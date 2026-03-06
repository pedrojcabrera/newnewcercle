<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="row col-8 my-3 mx-auto">
    <h1 class='text-center'> <?=$asunto?> </h1>

    <?php if(count($correctos)>0): ?>
    <div class="card col-8 my-2 mx-auto">
        <h2 class="text-center">Listado de Envíos</h2>
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
        <h2 class="text-center">Listado de Errores</h2>
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
</div>

<?= $this->endSection() ?>
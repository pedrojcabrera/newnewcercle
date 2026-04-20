<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container my-3">
    <h1 class='text-center'> <?= esc($asunto) ?> </h1>

    <?php if(count($correctos)>0): ?>
    <div class="card my-2 mx-auto col-12 col-xl-10">
        <h2 class="text-center">Listado de Envíos</h2>
        <div class="table-responsive">
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
    </div>
    <?php endif; ?>

    <?php if(count($errores)>0): ?>
    <div class="card my-2 mx-auto col-12 col-xl-10">
        <h2 class="text-center">Listado de Errores</h2>
        <div class="table-responsive">
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
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

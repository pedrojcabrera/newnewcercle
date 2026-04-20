<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container">
    <div class="card-body">
        <h4 class="text-center"><?= esc($evento->titulo) ?></h4>
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Contacto</th>
                        <?php if(!$evento->evento_cerrado) : ?>
                        <th scope="col">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($esperando as $enEspera) : ?>
                    <tr class="align-middle">
                        <td style="white-space: nowrap;
                              font-size:.8em;
                              text-align: center;
                              ">
                            <?= date('d-m-Y', strtotime($enEspera->fecha)) ?>
                            <br>
                            <?= date('H:i', strtotime($enEspera->fecha))." h" ?>
                        </td>
                        <td><?= esc(trim($enEspera->nombre." ".$enEspera->apellidos)) ?></td>
                        <td><?= esc($enEspera->email) ?>
                            <br>Tel: <?= esc($enEspera->telefono) ?>
                        </td>
                        <?php if(!$evento->evento_cerrado) : ?>
                        <td class="ico-acciones">
                            <form style="display: inline;"
                                action="<?php echo base_url('control/inscribirDeEspera/' . $enEspera->id);?>"
                                method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" title="Inscribir" class="btn btn-primary btn-sm"
                                    aria-label="Inscribir"><i class="bi bi-check-circle-fill"></i></button>
                            </form>

                            <form style="display: inline;"
                                action="<?=base_url('control/quitarDeEspera/'.$enEspera->id) ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Borrar" class="btn btn-danger btn-sm"
                                    aria-label="Quitar"
                                    onclick="return confirm('\u00bf Confirma el borrado ?');"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mx-3 mt-3">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm"
                href="<?=base_url('control/eventos')?>" role="button" title="Volver"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
            <a name="listar" id="listar" class="btn btn-primary btn-sm"
                href="<?=base_url('control/listarEnEspera/'.$evento->id)?>" role="button"
                title="Generar pdf" target="_blank"><i class="bi bi-file-pdf-fill"></i> Generar PDF</a>
        </div>
    </div>
</div>

<?= $this->endSection()?>

<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container">

    <div class="card-body">
        <h4 class="text-center mt-3 mb-3"><?=strtoupper($evento->titulo)?></h4>
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="datatable">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Contacto</th>
                        <?php if(!$evento->evento_cerrado) : ?>
                        <th scope="col">Estado</th>
                        <?php else: ?>
                        <th scope="col">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach($invitados as $invitado) : ?>
                    <tr class="align-middle">
                        <td style="white-space: nowrap;
                              font-size:.8em;
                              text-align: center;
                              ">
                            <?= date('d-m-Y', strtotime($invitado->fecha)) ?>
                            <br>
                            <?= date('H:i', strtotime($invitado->fecha))." h" ?>
                        </td>
                        <td><?= esc(trim($invitado->nombre." ".$invitado->apellidos)) ?></td>
                        <td><?= esc($invitado->email) ?>
                            <br>Tel: <?= esc($invitado->telefono) ?>
                        </td>
                        <td class="ico-acciones">
                            <?php if($invitado->enespera): ?>
                            <a class="w100 text-decoration-none text-center bg-warning text-primary p-1 rounded">Lista
                                de Espera</a>
                            <?php endif ?>

                            <?php if($invitado->inscrito): ?>
                            <a class="w100 text-decoration-none text-center bg-info text-black p-1 rounded">Inscrito</a>
                            <?php endif ?>

                            <?php if(!$evento->evento_cerrado) : ?>
                            <?php if(!$invitado->enespera && !$invitado->inscrito): ?>
                            <form style="display: inline;"
                                action="<?=base_url('control/inscribirse/'.$invitado->id.'/'.$invitado->id_evento) ?>" method="POST">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success btn-sm" aria-label="Inscribir"
                                    title="Inscribir"><i class="bi bi-check-circle-fill"></i></button>
                            </form>
                            <?php endif ?>

                            <form style="display: inline;"
                                action="<?=base_url('control/quitarInvitado/'.$invitado->id) ?>" method="POST">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Eliminar" class="btn btn-danger btn-sm"
                                    aria-label="Eliminar"
                                    onclick="return confirm('¿ Confirma el borrado de la Invitación y si las hubiera, reservas e inscripciones referentes a esta ?');"><i class="bi bi-trash3-fill"></i></button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mx-3 mt-3">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm"
                href="<?=base_url('control/eventos')?>" role="button" title="Volver"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
            <a name="listar" id="listar" class="btn btn-primary btn-sm"
                href="<?=base_url('control/listarInvitados/'.$evento->id)?>" role="button"
                title="Generar pdf" target="_blank"><i class="bi bi-file-pdf-fill"></i> Generar PDF</a>
        </div>
    </div>
</div>

<?= $this->endSection()?>

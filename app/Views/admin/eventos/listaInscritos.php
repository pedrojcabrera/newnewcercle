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
						<th scope="col">Vía</th>
						<?php if(!$evento->evento_cerrado) : ?>
						<th scope="col">Acciones</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach($inscritos as $inscrito) : ?>
					<tr class="align-middle">
						<td style="white-space: nowrap;
                              font-size:.8em;
                              text-align: center;
                              ">
							<?= date('d-m-Y', strtotime($inscrito->fecha)) ?>
							<br>
							<?= date('H:i', strtotime($inscrito->fecha))." h" ?>
						</td>
						<td><?= esc(trim($inscrito->nombre." ".$inscrito->apellidos)) ?></td>
						<td><?= esc($inscrito->email) ?>
							<br>Tel: <?= esc($inscrito->telefono) ?>
						</td>
						<td><?= esc(trim($inscrito->via)) ?></td>
						<?php if(!$evento->evento_cerrado) : ?>
						<td class="h-100 ico-acciones">
							<form class="align-middle" action="<?=base_url('control/quitarInscrito/'.$inscrito->id) ?>"
								method="POST">
								<?= csrf_field() ?>
								<input type="hidden" name="_method" value="DELETE">
								<button type="submit" title="Borrar" class="btn btn-danger btn-sm"
									aria-label="Borrar"
									onclick="return confirm('\u00bf Confirma el borrado y si procede el regreso a la Lista de Espera ?');"><i class="bi bi-trash3-fill"></i></button>
							</form>
							<?php if($inscrito->id_contacto == 0): ?>
							<form class="d-inline" action="<?= base_url('control/completaContactoDesdeInscrito/'.$inscrito->id) ?>" method="GET">
							<button type="submit" class="btn btn-secondary btn-sm"
								title="Crear un contacto a partir de este" aria-label="Crear contacto"><i class="bi bi-person-plus-fill"></i></button>
							</form>
							<?php endif; ?>
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
				href="<?=base_url('control/listarInscritos/'.$evento->id)?>" role="button"
				title="Generar pdf" target="_blank"><i class="bi bi-file-pdf-fill"></i> Generar PDF</a>
		</div>
	</div>
</div>

<?= $this->endSection()?>

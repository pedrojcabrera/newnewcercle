<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container">
	<div class="d-flex justify-content-between mb-3">
		<a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
			href="<?=base_url('control/eventos', $_SERVER['REQUEST_SCHEME'])?>" role="button" title="Cancelar">
			Volver</a>
		<a name="listar" id="listar" class="btn btn-primary btn-sm bi-filetype-pdf"
			href="<?=base_url('control/listarInscritos/'.$evento->id, $_SERVER['REQUEST_SCHEME'])?>" role="button"
			title="Generar pdf" target="_blank"> Imprimir PDF</a>
	</div>
	<div class="card-body">
		<h4 class="text-center"><?=$evento->titulo?></h4>
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
						<td><?= trim($inscrito->nombre." ".$inscrito->apellidos) ?></td>
						<td><?= $inscrito->email ?>
							<br>Tel: <?= $inscrito->telefono ?>
						</td>
						<td><?= trim($inscrito->via) ?></td>
						<?php if(!$evento->evento_cerrado) : ?>
						<td class="h-100 ico-acciones">
							<form class="align-middle" action="<?=base_url('control/quitarInscrito/'.$inscrito->id) ?>"
								method="POST">
								<input type="hidden" name="_method" value="DELETE">
								<button type="submit" title="Borrar" class="btn btn-danger btn-sm bi-eraser"
									onclick="return confirm('¿ Confirma el borrado y si procede el regreso a la Lista de Espera ?');">
									Eliminar</button>
							</form>
							<?php if($inscrito->id_contacto == 0): ?>
							<a href="<?= base_url('control/completaContactoDesdeInscrito/'.$inscrito->id) ?>"
								class="btn btn-secondary btn-sm bi bi-person-plus"
								title="Crear un contacto a partir de este"> Crear</a>
							<?php endif; ?>
						</td>
						<?php endif; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="d-flex justify-content-between mx-3 mt-3">
			<a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
				href="<?=base_url('control/eventos', $_SERVER['REQUEST_SCHEME'])?>" role="button" title="Cancelar">
				Volver</a>
			<a name="listar" id="listar" class="btn btn-primary btn-sm bi-filetype-pdf"
				href="<?=base_url('control/listarInscritos/'.$evento->id, $_SERVER['REQUEST_SCHEME'])?>" role="button"
				title="Generar pdf" target="_blank"> Imprimir PDF</a>
		</div>
	</div>
</div>

<?= $this->endSection()?>
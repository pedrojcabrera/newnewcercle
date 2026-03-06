<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container col-8 my-3 mx-auto">
	<div class="container col-8 my-1 mx-auto px-2">
		<h2 class='text-center'><?= trim(strtoupper($inscrito->nombre.' '.$inscrito->apellidos)) ?></h2>
	</div>
</div>
<div class="container col-4 mx-auto">
	<div class="card-header fs-5">Marcar los grupos de pertenencia</div>
	<form action="<?=base_url('control/contactoDesdeInscrito')?>" method="post">
		<div class="card-body">
			<div class="form-check">
				<input class="form-check-input" name="socio" type="checkbox" id="socio">
				<label class="form-check-label" for="socio">
					Socios
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" name="alumno" type="checkbox" id="alumno">
				<label class="form-check-label" for="alumno">
					Alumnos
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" name="pdalumno" type="checkbox" id="pdalumno">
				<label class="form-check-label" for="pdalumno">
					Padres o Madres de Alumnos
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" name="pintor" type="checkbox" id="pintor">
				<label class="form-check-label" for="pintor">
					Pintores o Artistas
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" name="dtaller" type="checkbox" id="dtaller">
				<label class="form-check-label" for="dtaller">
					Asistentes de Talleres
				</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" name="amigo" type="checkbox" id="amigo">
				<label class="form-check-label" for="amigo">
					Amigos o Simpatizantes
				</label>
			</div>
		</div>
		<div class="card-footer text-center mt-3">
			<a href="<?=base_url('control/inscritos/'.$inscrito->id_evento)?>" type="button"
				title="Regresar" class="btn btn-md btn-success bi-box-arrow-left"> Cancelar</a>
			<button type="submit" class="btn btn-primary btn-md bi-send" title="Crear Contacto"> Crear Contacto</button>
		</div>
	</form>
</div>
<?php if(isset($mensaje_error)): ?>
<div class="mt-3 col-6 mx-auto alert alert-danger text-center" role="alert">
	<?= $mensaje_error ?>
</div>
<?php endif ?>


<?= $this->endSection() ?>

<?= $this->section('masJS')?>

<?= $this->endSection() ?>
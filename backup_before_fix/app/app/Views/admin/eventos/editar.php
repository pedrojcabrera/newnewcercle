<?php echo $this->extend('admin/plantillas/layout'); ?>

<?php echo $this->section('contenido'); ?>

<div class="linea_msg_error">
    <?php if (isset($erroresFecha)) {
      echo $erroresFecha;
     }
    ?>
</div>

<form action="<?php echo base_url('control/eventos/modificar/' . $id, $_SERVER['REQUEST_SCHEME']); ?>" method="post"
    enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <?php echo csrf_field(); ?>
    <div class="container col-10 mx-auto card-body mb-3">
        <!-- ---------------------- cartel -------------------- -->
        <div class="row mb-3 mb-2 mt-2">
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="visible" name="visible"
                        value="<?php echo $evento->visible; ?>" <?php echo $evento->visible ? 'checked' : ''; ?>
                        <?php echo set_checkbox('visible'); ?>>
                    <label class="form-check-label" for="visible">
                        Seleccionar si el evento es visible en la Web
                    </label>
                </div>
            </div>
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="evento_cerrado" name="evento_cerrado"
                        value="<?php echo $evento->evento_cerrado; ?>"
                        <?php echo $evento->evento_cerrado ? 'checked' : ''; ?>
                        <?php echo set_checkbox('evento_cerrado'); ?>>
                    <label class="form-check-label" for="evento_cerrado">
                        Seleccionar si el evento está cerrado
                    </label>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="titulo" class="form-label">Título:</label>
            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título" required
                value="<?php echo set_value('titulo', $evento->titulo); ?>">
            <div class="linea_msg_error">
                <?php echo validation_show_error('titulo'); ?>
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-6">
                <!-- cartel -->
                <div class="mt-2">
                    <div class="form-check">
                        <?php $cartel = FCPATH ."imgEventos/ev_" . $id . "/cartel.jpg"; ?>
                        <?php $cartel = file_exists($cartel) ? $cartel : FCPATH . "imgEventos/eventos.jpg"; ?>
                        <input class="form-check-input" type="checkbox" id="noTieneCartel" name="noTieneCartel"
                            <?= str_contains($cartel,'eventos.jpg') ? 'checked' : '' ?>>
                        <label class=" form-check-label" for="noTieneCartel">
                            Marcar si el evento no tiene Cartel y usará el cartel Genérico.
                        </label>
                    </div>
                </div>
                <div class="row mt-3 mb-3 cartel">
                    <div class="col-2 vista_edit_cartel">
                        <img src="<?=base_url()?>imgEventos/ev_<?=$id?>/cartel.jpg ?>" alt="">
                    </div>

                    <div class="col-10 mb-3" id="input_cartel">
                        <label for="cartel" class="form-label">Seleccione el Cartel</label>
                        <input type="file" class="form-control" name="cartel" id="cartel" accept=".jpg, .jpeg"
                            placeholder=" Seleccione el Cartel" value="<?php echo set_value('cartel'); ?>">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('cartel'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--  pdf -->
            <div class="col-6">
                <div class="mt-2">
                    <div class="form-check">
                        <?php $pdf = FCPATH . 'pdfEventos/pdf_' . $id . '.pdf'; ?>
                        <input class="form-check-input" type="checkbox" id="noTienePdf" name="noTienePdf"
                            <?php echo file_exists($pdf) ? '' : 'checked'; ?>>
                        <label class="form-check-label" for="noTienePdf">
                            Marcar si el evento no adjunta documento pdf.
                        </label>
                    </div>
                </div>

                <div class="row mt-3 mb-3 pdf">
                    <div class="mb-3" id="input_pdf">
                        <label for="pdf" class="form-label">Seleccione el documento a adjuntar
                            (<?=file_exists(basename($pdf))?basename($pdf):'---'?>)</label>
                        <input type="file" class="form-control" name="pdf" id="pdf" accept=".pdf"
                            placeholder=" Seleccione el documento" value="<?php echo set_value('pdf'); ?>">
                        <div class="linea_msg_error">
                            <?php echo validation_show_error('pdf'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="mb-3 col-6">
                <label for="eventotipo" class="form-label">Tipo de Evento</label>
                <select class="form-select form-select-sm" name="eventotipo" id="eventotipo" required>
                    <option selected disabled>Selecciona un tipo</option>
                    <?php foreach ($lista as $tipo): ?>
                    <option value="<?php echo $tipo->eventotipo; ?>"
                        <?php echo set_select('eventotipo', $tipo->eventotipo); ?>
                        <?php echo $evento->eventotipo == $tipo->eventotipo ? 'selected' : ''; ?>>
                        <?php echo strtoupper($tipo->eventonombre); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="linea_msg_error">
                    <?php echo validation_show_error('eventotipo'); ?>
                </div>
            </div>
            <div class="pb-3 mb-3 row">
                <div class="col-6">
                    <label for="desde" class="form-label">Desde (Inicio del Evento):</label>
                    <input type="date" class="form-control" name="desde" id="desde"
                        placeholder="Desde (Fecha de inicio)" required
                        value="<?php echo set_value('desde', $evento->desde); ?>">
                    <div class="linea_msg_error">
                        <?php echo validation_show_error('desde'); ?>
                    </div>
                </div>
                <div class="col-6">
                    <label for="hasta" class="form-label">Hasta (Finalización del Evento):</label>
                    <input type="date" class="form-control" name="hasta" id="hasta"
                        placeholder="Hasta (Fecha de finalización)" required
                        value="<?php echo set_value('hasta', $evento->hasta); ?>">
                    <div class="linea_msg_error">
                        <?php if (session()->has('error_hasta')): ?>
                        <?php echo session()->error_hasta; ?>
                        <?php session()->remove('error_hasta'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row pb-3 mb-3">
                <div class="mt-1 mb-3 mx-auto col-6">
                    <div class="form-check">
                        <input class="form-check-input check-oculta" type="checkbox" id="inscripcion" name="inscripcion"
                            value="<?php echo $evento->inscripcion; ?>"
                            <?php echo $evento->inscripcion ? 'checked' : ''; ?>
                            <?php echo set_checkbox('inscripcion'); ?>>
                        <label class="form-check-label" for="inscripcion">
                            Permite inscripciones
                        </label>
                    </div>
                </div>
                <div class="mt-1 mb-3 mx-auto col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="inscripcion_invitacion"
                            value="<?php echo $evento->inscripcion_invitacion; ?>"
                            <?php echo $evento->inscripcion_invitacion ? 'checked' : ''; ?>
                            <?php echo set_checkbox('inscripcion_invitacion'); ?>>
                        <label class="form-check-label" for="inscripcion_invitacion">
                            Permite realizar invitaciones por e-mail
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <label for="desde_inscripcion" class="form-label">Desde (Inicio del periodo de
                        Inscripción):</label>
                    <input type="date" class="form-control date-oculta" name="desde_inscripcion" id="desde_inscripcion"
                        placeholder="Desde (Fecha de inicio)"
                        value="<?php echo set_value('desde_inscripcion', $evento->desde_inscripcion); ?>">
                    <div class="linea_msg_error">
                        <?php echo validation_show_error('desde_inscripcion'); ?>
                    </div>
                </div>
                <div class="col-6">
                    <label for="hasta_inscripcion" class="form-label">Hasta (Final del periodo de
                        Inscripción):</label>
                    <input type="date" class="form-control date-oculta" name="hasta_inscripcion" id="hasta_inscripcion"
                        placeholder="Hasta (Fecha de finalización)"
                        value="<?php echo set_value('hasta_inscripcion', $evento->hasta_inscripcion); ?>">
                    <div class="linea_msg_error">
                        <?php if (session()->has('error_hasta_inscripcion')): ?>
                        <?php echo session()->error_hasta_inscripcion; ?>
                        <?php session()->remove('error_hasta_inscripcion'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row pb-3 mt-3">


                <div class="card col-6 mx-auto">
                    <div class="card-header fs-5">Seleccione grupos de destinatarios</div>
                    <form action="" method="post">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" name="socio" type="checkbox"
                                    value="<?php echo $evento->socio; ?>" <?php echo $evento->socio ? 'checked' : ''; ?>
                                    <?php echo set_checkbox('socio'); ?>>
                                <label class="form-check-label" for="socio">
                                    Socios
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="alumno" type="checkbox"
                                    value="<?php echo $evento->alumno; ?>"
                                    <?php echo $evento->alumno ? 'checked' : ''; ?>
                                    <?php echo set_checkbox('alumno'); ?>>
                                <label class="form-check-label" for="alumno">
                                    Alumnos
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="pdalumno" type="checkbox"
                                    value="<?php echo $evento->pdalumno; ?>"
                                    <?php echo $evento->pdalumno ? 'checked' : ''; ?>
                                    <?php echo set_checkbox('pdalumno'); ?>>
                                <label class="form-check-label" for="pdalumno">
                                    Padres o Madres de Alumnos
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="pintor" type="checkbox"
                                    value="<?php echo $evento->pintor; ?>"
                                    <?php echo $evento->pintor ? 'checked' : ''; ?>
                                    <?php echo set_checkbox('pintor'); ?>>
                                <label class="form-check-label" for="pintor">
                                    Pintores o Artistas
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="dtaller" type="checkbox"
                                    value="<?php echo $evento->dtaller; ?>"
                                    <?php echo $evento->dtaller ? 'checked' : ''; ?>
                                    <?php echo set_checkbox('dtaller'); ?>>
                                <label class="form-check-label" for="dtaller">
                                    Asistentes de Talleres
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="amigo" type="checkbox"
                                    value="<?php echo $evento->amigo; ?>" <?php echo $evento->amigo ? 'checked' : ''; ?>
                                    <?php echo set_checkbox('amigo'); ?>>
                                <label class="form-check-label" for="amigo">
                                    Amigos o Simpatizantes
                                </label>
                            </div>
                        </div>
                </div>


                <div class="mb-3">
                    <label for="texto" class="form-label">Texto para la Web</label>
                    <textarea class="form-control" name="texto" id="texto"
                        rows="4"><?php echo set_value('texto', $evento->texto); ?></textarea>
                    
                </div>
                <div class="mb-3">
                    <label for="texto_carta" class="form-label">Cuerpo del texto para invitación por
                        correo</label>
                    <textarea class="form-control" name="texto_carta" id="texto_carta"
                        rows="3"><?php echo set_value('texto_carta', $evento->texto_carta); ?></textarea>
                    
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
                        href="<?php echo base_url('control/eventos', $_SERVER['REQUEST_SCHEME']); ?>" role="button"
                        title="Cancelar"> Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-sm bi-person-check-fill" title="Modificar">
                        Modificar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php echo $this->endSection(); ?>

<?php echo $this->section('masJS'); ?>
<script>
$('#inscripcion').on('change', function() {
    if (this.checked) {
        $('.date-oculta').removeAttr('disabled');
        $('.date-oculta').color('black');
    } else {
        $('.date-oculta').attr('disabled', 'disabled');
        $('.date-oculta').color('grey');
    }
});
$('#noTieneCartel').on('change', function() {
    if (this.checked) {
        $('#cartel').attr('disabled', 'disabled');
        $('#input_cartel').css('visibility', 'hidden');
        $('#cartel').color('grey');
    } else {
        $('#cartel').removeAttr('disabled');
        $('#input_cartel').css('visibility', 'visible');
        $('#cartel').color('black');
    }
});
$('#noTienePdf').on('change', function() {
    if (this.checked) {
        $('#pdf').attr('disabled', 'disabled');
        $('#input_pdf').css('visibility', 'hidden');
        $('#pdf').color('grey');
    } else {
        $('#pdf').removeAttr('disabled');
        $('#input_pdf').css('visibility', 'visible');
        $('#pdf').color('black');
    }
});
</script>
<?php echo $this->endSection(); ?>
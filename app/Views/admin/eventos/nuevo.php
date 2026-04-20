<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>

<div class="linea_msg_error">
    <?php if (isset($erroresFecha)) {
      echo $erroresFecha;
     }
    ?>
</div>

<?php $errorTitulo = validation_show_error('titulo'); ?>
<?php $errorCartel = validation_show_error('cartel'); ?>
<?php $errorPdfAdjunto = validation_show_error('pdf_adjunto'); ?>
<?php $errorTipoEvento = validation_show_error('eventotipo'); ?>
<?php $errorDesde = validation_show_error('desde'); ?>
<?php $errorHasta = validation_show_error('hasta'); ?>
<?php if (!$errorHasta && session()->has('error_hasta')) {
    $errorHasta = session()->error_hasta;
    session()->remove('error_hasta');
} ?>
<?php $errorDesdeInscripcion = validation_show_error('desde_inscripcion'); ?>
<?php $errorHastaInscripcion = validation_show_error('hasta_inscripcion'); ?>
<?php if (!$errorHastaInscripcion && session()->has('error_hasta_inscripcion')) {
    $errorHastaInscripcion = session()->error_hasta_inscripcion;
    session()->remove('error_hasta_inscripcion');
} ?>


<form action="<?php echo base_url('control/eventos/crear'); ?>" method="post"
    enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="container col-10 mx-auto">
        <div class="card-body">
            <div class="row border mb-3">
                <div class="mb-2 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="visible" name="visible" checked
                            <?php echo set_checkbox('visible'); ?>>
                        <label class="form-check-label" for="visible">
                            Seleccionar si el evento es visible en la Web
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control<?php echo $errorTitulo ? ' is-invalid' : ''; ?>" name="titulo" id="titulo" placeholder="Título" required
                        value="<?php echo set_value('titulo'); ?>">
                    <div class="invalid-feedback">
                        <?php echo $errorTitulo; ?>
                    </div>
                </div>
            </div>
            <div class="row mb-3 border">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="cartel" class="form-label">Seleccione el Cartel (.jpg)</label>
                        <input type="file" class="form-control<?php echo $errorCartel ? ' is-invalid' : ''; ?>" name="cartel" id="cartel" accept=".jpg, .jpeg"
                            placeholder=" Seleccione el Cartel" value="<?php echo set_value('cartel'); ?>">
                        <div class="invalid-feedback">
                            <?php echo $errorCartel; ?>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label for="pdf_adjunto" class="form-label">Seleccione el documento (.pdf) adjunto</label>
                        <input type="file" class="form-control<?php echo $errorPdfAdjunto ? ' is-invalid' : ''; ?>" name="pdf_adjunto" id="pdf_adjunto"
                            accept="application/pdf" placeholder=" Seleccione el PDF adjunto"
                            value="<?php echo set_value('pdf_adjunto'); ?>">
                        <div class="invalid-feedback">
                            <?php echo $errorPdfAdjunto; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-6">
                <label for="eventotipo" class="form-label">Tipo de Evento</label>
                <select class="form-select form-select-sm<?php echo $errorTipoEvento ? ' is-invalid' : ''; ?>" name="eventotipo" id="eventotipo" required>
                    <option selected disabled>Selecciona un tipo</option>
                    <?php foreach ($lista as $tipo): ?>
                    <option value="<?php echo $tipo->eventotipo; ?>"
                        <?php echo set_select('eventotipo', $tipo->eventotipo); ?>>
                        <?php echo strtoupper($tipo->eventonombre); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                    <?php echo $errorTipoEvento; ?>
                </div>
            </div>
            <div class="pb-3 mb-3 row border">
                <div class="col-6">
                    <label for="desde" class="form-label">Desde (Inicio del Evento):</label>
                    <input type="date" class="form-control<?php echo $errorDesde ? ' is-invalid' : ''; ?>" name="desde" id="desde"
                        placeholder="Desde (Fecha de inicio)" required value="<?php echo set_value('desde'); ?>">
                    <div class="invalid-feedback">
                        <?php echo $errorDesde; ?>
                    </div>
                </div>
                <div class="col-6">
                    <label for="hasta" class="form-label">Hasta (Finalización del Evento):</label>
                    <input type="date" class="form-control<?php echo $errorHasta ? ' is-invalid' : ''; ?>" name="hasta" id="hasta"
                        placeholder="Hasta (Fecha de finalización)" required value="<?php echo set_value('hasta'); ?>">
                    <div class="invalid-feedback">
                        <?php echo $errorHasta; ?>
                    </div>
                </div>
            </div>
            <div class="row border pb-3 mb-3">
                <div class="mt-1 mb-3 mx-auto col-6">
                    <div class="form-check">
                        <input class="form-check-input check-oculta" type="checkbox" id="inscripcion" name="inscripcion"
                            <?php echo set_checkbox('inscripcion'); ?>>
                        <label class="form-check-label" for="inscripcion">
                            Permite inscripciones
                        </label>
                    </div>
                </div>
                <div class="mt-1 mb-3 mx-auto col-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="inscripcion_invitacion"
                            <?php echo set_checkbox('inscripcion_invitacion'); ?>>
                        <label class="form-check-label" for="inscripcion_invitacion">
                            Permite realizar invitaciones por e-mail
                        </label>
                    </div>
                </div>
                <div class="col-6">
                    <label for="desde_inscripcion" class="form-label">Desde (Inicio del periodo de
                        Inscripción):</label>
                    <input type="date" class="form-control date-oculta<?php echo $errorDesdeInscripcion ? ' is-invalid' : ''; ?>" name="desde_inscripcion" id="desde_inscripcion"
                        placeholder="Desde (Fecha de inicio)" value="<?php echo set_value('desde_inscripcion'); ?>"
                        disabled>
                    <div class="invalid-feedback">
                        <?php echo $errorDesdeInscripcion; ?>
                    </div>
                </div>
                <div class="col-6">
                    <label for="hasta_inscripcion" class="form-label">Hasta (Final del periodo de Inscripción):</label>
                    <input type="date" class="form-control date-oculta<?php echo $errorHastaInscripcion ? ' is-invalid' : ''; ?>" name="hasta_inscripcion" id="hasta_inscripcion"
                        placeholder="Hasta (Fecha de finalización)" value="<?php echo set_value('hasta_inscripcion'); ?>"
                        disabled>
                    <div class="invalid-feedback">
                        <?php echo $errorHastaInscripcion; ?>
                    </div>
                </div>
            </div>
            <div class="row pb-3 mt-3">


                <div class="card col-6 mx-auto">
                    <div class="card-header fs-5">Seleccione grupos de destinatarios</div>
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" name="socio" type="checkbox"
                                    <?php echo set_checkbox('socio'); ?>>
                                <label class="form-check-label" for="socio">
                                    Socios
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="alumno" type="checkbox"
                                    <?php echo set_checkbox('alumno'); ?>>
                                <label class="form-check-label" for="alumno">
                                    Alumnos
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="pdalumno" type="checkbox"
                                    <?php echo set_checkbox('pdalumno'); ?>>
                                <label class="form-check-label" for="pdalumno">
                                    Padres o Madres de Alumnos
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="pintor" type="checkbox"
                                    <?php echo set_checkbox('pintor'); ?>>
                                <label class="form-check-label" for="pintor">
                                    Pintores o Artistas
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="dtaller" type="checkbox"
                                    <?php echo set_checkbox('dtaller'); ?>>
                                <label class="form-check-label" for="dtaller">
                                    Asistentes de Talleres
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="amigo" type="checkbox"
                                    <?php echo set_checkbox('amigo'); ?>>
                                <label class="form-check-label" for="amigo">
                                    Amigos o Simpatizantes
                                </label>
                            </div>
                        </div>
                </div>


                <div class="mb-3">
                    <label for="texto" class="form-label">Texto para la Web</label>
                    <div class="border rounded">
                        <div class="d-flex flex-wrap gap-1 p-2 border-bottom bg-light" role="toolbar" aria-label="Editor HTML para texto web">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="bold" title="Negrita"><i class="bi bi-type-bold"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="italic" title="Cursiva"><i class="bi bi-type-italic"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="underline" title="Subrayado"><i class="bi bi-type-underline"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="insertUnorderedList" title="Lista con viñetas"><i class="bi bi-list-ul"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="insertOrderedList" title="Lista numerada"><i class="bi bi-list-ol"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="formatBlock" data-value="<h2>" title="Título H2">H2</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="formatBlock" data-value="<h3>" title="Título H3">H3</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="formatBlock" data-value="<p>" title="Párrafo">P</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="createLink" title="Insertar enlace"><i class="bi bi-link-45deg"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoEditor" data-command="unlink" title="Quitar enlace"><i class="bi bi-link-45deg"></i><i class="bi bi-x"></i></button>
                        </div>
                        <div id="textoEditor" class="form-control border-0 rounded-0" contenteditable="true" style="min-height: 16rem; overflow-y: auto;"></div>
                    </div>
                    <textarea class="d-none" name="texto" id="texto" rows="4"><?php echo set_value('texto'); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="texto_carta" class="form-label">Cuerpo del texto para invitación por
                        correo</label>
                    <div class="border rounded">
                        <div class="d-flex flex-wrap gap-1 p-2 border-bottom bg-light" role="toolbar" aria-label="Editor HTML para invitación por correo">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="bold" title="Negrita"><i class="bi bi-type-bold"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="italic" title="Cursiva"><i class="bi bi-type-italic"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="underline" title="Subrayado"><i class="bi bi-type-underline"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="insertUnorderedList" title="Lista con viñetas"><i class="bi bi-list-ul"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="insertOrderedList" title="Lista numerada"><i class="bi bi-list-ol"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="formatBlock" data-value="<h2>" title="Título H2">H2</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="formatBlock" data-value="<h3>" title="Título H3">H3</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="formatBlock" data-value="<p>" title="Párrafo">P</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="createLink" title="Insertar enlace"><i class="bi bi-link-45deg"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-editor-target="textoCartaEditor" data-command="unlink" title="Quitar enlace"><i class="bi bi-link-45deg"></i><i class="bi bi-x"></i></button>
                        </div>
                        <div id="textoCartaEditor" class="form-control border-0 rounded-0" contenteditable="true" style="min-height: 14rem; overflow-y: auto;"></div>
                    </div>
                    <textarea class="d-none" name="texto_carta" id="texto_carta" rows="3"><?php echo set_value('texto_carta'); ?></textarea>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <a name="cancelar" id="cancelar" class="btn btn-success btn-md"
                        href="<?php echo base_url('control/eventos'); ?>" role="button"
                        title="Cancelar"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-md" title="Crear"><i class="bi bi-check-lg"></i> Grabar un nuevo evento</button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php echo $this->endSection(); ?>

<?php echo $this->section('masJS'); ?>
<script>
const inscripcionCheckbox = document.getElementById('inscripcion');

if (inscripcionCheckbox) {
    inscripcionCheckbox.addEventListener('change', function() {
        document.querySelectorAll('.date-oculta').forEach((input) => {
            input.disabled = !this.checked;
            input.style.color = this.checked ? 'black' : 'grey';
        });
    });
}

const initHtmlEditor = (editorId, textareaId) => {
    const editor = document.getElementById(editorId);
    const textarea = document.getElementById(textareaId);
    if (!editor || !textarea) {
        return;
    }

    editor.innerHTML = textarea.value.trim() !== '' ? textarea.value : '<p></p>';

    const syncToTextarea = () => {
        textarea.value = editor.innerHTML.trim();
    };

    document.querySelectorAll('[data-editor-target="' + editorId + '"]').forEach((button) => {
        button.addEventListener('click', () => {
            const command = button.dataset.command;
            const value = button.dataset.value || null;

            editor.focus();

            if (command === 'createLink') {
                const url = window.prompt('URL del enlace (incluye https://):', 'https://');
                if (!url) {
                    return;
                }
                document.execCommand('createLink', false, url);
            } else {
                document.execCommand(command, false, value);
            }

            syncToTextarea();
        });
    });

    editor.addEventListener('input', syncToTextarea);

    const form = textarea.closest('form');
    if (form) {
        form.addEventListener('submit', syncToTextarea);
    }

    syncToTextarea();
};

initHtmlEditor('textoEditor', 'texto');
initHtmlEditor('textoCartaEditor', 'texto_carta');
</script>
<?php echo $this->endSection(); ?>

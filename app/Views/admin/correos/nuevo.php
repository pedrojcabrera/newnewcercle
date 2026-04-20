<?php echo $this->extend('admin/plantillas/layout');?>
<?php echo $this->section('contenido');?>

<form action="<?php echo base_url('control/correos/crear');?>" method="POST">
    <!-- <input type="hidden" name="_method" value="PUT"> -->
    <?php echo csrf_field();?>
    <div class="row my-5 mx-2">
        <div class="col-sm-10 mb-1 mb-sm-0 mx-auto">
            <div class="container">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto:</label>
                        <input type="text" class="form-control" name="asunto" id="asunto" placeholder="Asunto">
                    </div>
                    <div class="mb-3">
                        <label for="editor" class="form-label">Texto</label>
                        <div class="border rounded">
                            <div class="d-flex flex-wrap gap-1 p-2 border-bottom bg-light" role="toolbar" aria-label="Editor de texto HTML">
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
                            <div id="textoEditor" class="form-control border-0 rounded-0" contenteditable="true" style="min-height: 22rem; overflow-y: auto;"><p></p></div>
                        </div>
                        <textarea class="d-none" name="texto" id="texto" rows="15"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between mx-3 mt-3">
                    <a name="cancelar" id="cancelar" class="btn btn-success btn-md"
                        href="<?php echo base_url('control/correos');?>" role="button"
                        title="Cancelar"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
                        <button type="submit" class="btn btn-primary btn-md" title="Crear"><i class="bi bi-check-lg"></i> Grabar un nuevo correo</button>
                </div>
            </div>
        </div>

</form>

<?php echo $this->endSection();?>

<?php echo $this->section('masJS');?>

<script>
(() => {
    const editor = document.getElementById('textoEditor');
    const textarea = document.getElementById('texto');
    if (!editor || !textarea) {
        return;
    }

    const syncToTextarea = () => {
        textarea.value = editor.innerHTML.trim();
    };

    document.querySelectorAll('[data-editor-target="textoEditor"]').forEach((button) => {
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
})();
</script>

<?php echo $this->endSection();?>

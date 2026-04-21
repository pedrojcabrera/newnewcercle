<?= $this->extend('admin/plantillas/layout')?>
<?= $this->section('contenido')?>

<div class="container my-3">
   <div class="container my-1 mx-auto px-2">
      <h2 class='text-center'><?= esc(strtoupper($correo->asunto)) ?></h2>
   </div>
   <div class="card-header fs-5">Recuerda que puedes usar los siguientes distintivos en el correo...</div>
   <div class="card-body fst-italic py-3 text-center">{{nombre}}, {{email}}, {{telefono}}, {{direccion}}, {{codpostal}},
      {{poblacion}}</div>
   <div class="card-footer text-center fs-5">...y que estos serán sustituidos por sus valores reales en el momento de la
      émisión del mismo.</div>
</div>
<div class="container mx-auto" style="max-width: 520px;">
   <div class="card-header fs-5">Seleccione grupos de destinatarios</div>
   <form id="formCartear" action="<?=base_url('control/correos/enviomasivo/'.$id)?>" method="post">
      <?= csrf_field() ?>
      <div class="card-body">
         <div class="form-check">
            <input class="form-check-input" name="socio" type="checkbox" value="1" id="socio">
            <label class="form-check-label" for="socio">Socios</label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="alumno" type="checkbox" value="1" id="alumno">
            <label class="form-check-label" for="alumno">Alumnos</label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="pdalumno" type="checkbox" value="1" id="pdalumno">
            <label class="form-check-label" for="pdalumno">Padres o Madres de Alumnos</label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="pintor" type="checkbox" value="1" id="pintor">
            <label class="form-check-label" for="pintor">Pintores o Artistas</label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="dtaller" type="checkbox" value="1" id="dtaller">
            <label class="form-check-label" for="dtaller">Asistentes de Talleres</label>
         </div>
         <div class="form-check">
            <input class="form-check-input" name="amigo" type="checkbox" value="1" id="amigo">
            <label class="form-check-label" for="amigo">Amigos o Simpatizantes</label>
         </div>
      </div>
      <div class="card-footer text-end">
         <a id="btnCancelar" href="<?=base_url('control/correos')?>" type="button" title="Cancelar"
            class="btn btn-md btn-success"><i class="bi bi-box-arrow-left"></i> Cancelar</a>
         <button id="btnEnviar" type="submit" class="btn btn-primary btn-md" title="Enviar correo">
            <i class="bi bi-send-fill"></i> Enviar
         </button>
      </div>
   </form>

   <div id="progressWrap" class="mt-3" style="display:none;">
      <div class="progress mb-2" style="height: 26px;">
         <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
              role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
         </div>
      </div>
      <p id="progressText" class="text-center small text-muted mb-0">Iniciando envío...</p>
   </div>
</div>

<?php if(isset($mensaje_error)): ?>
<div class="container mt-3">
   <div class="mx-auto alert alert-danger text-center" role="alert" style="max-width: 520px;">
   <?= $mensaje_error ?>
   </div>
</div>
<?php endif ?>

<?= $this->endSection() ?>

<?= $this->section('masJS')?>
<script>
document.getElementById('formCartear').addEventListener('submit', function (e) {
    e.preventDefault();

    const gruposValidos = ['socio', 'alumno', 'pdalumno', 'pintor', 'dtaller', 'amigo'];
    const grupos = gruposValidos.filter(g => {
        const cb = document.getElementById(g);
        return cb && cb.checked;
    });

    if (grupos.length === 0) {
        alert('Selecciona al menos un grupo de destinatarios.');
        return;
    }

    const form        = this;
    const btnEnviar   = document.getElementById('btnEnviar');
    const btnCancelar = document.getElementById('btnCancelar');
    const progressWrap = document.getElementById('progressWrap');
    const progressBar  = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');

    btnEnviar.disabled = true;
    btnCancelar.classList.add('disabled', 'pe-none');
    form.querySelectorAll('input[type=checkbox]').forEach(cb => cb.disabled = true);
    progressWrap.style.display = 'block';

    const urlLote     = '<?= base_url('control/correos/lote/' . $id) ?>';
    const urlResultado = '<?= base_url('control/correos/resultado/') ?>';

    let offset   = 0;
    let enviosID = 0;
    let total    = 0;
    let csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    async function enviarLote() {
        const body = new URLSearchParams();
        grupos.forEach(g => body.append(g, '1'));
        body.append('offset',   offset);
        body.append('enviosID', enviosID);
        body.append('total',    total);
        body.append(csrfName,   csrfHash);

        const resp = await fetch(urlLote, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: body.toString(),
        });

        const data = await resp.json();

        if (!data.ok) {
            progressText.textContent = 'Error: ' + (data.error ?? 'desconocido');
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-danger');
            btnEnviar.disabled = false;
            return;
        }

        enviosID = data.enviosID;
        total    = data.total;
        offset   = data.procesados;
        csrfName = data.csrf_name;
        csrfHash = data.csrf_hash;

        const pct = total > 0 ? Math.round(offset / total * 100) : 100;
        progressBar.style.width = pct + '%';
        progressBar.setAttribute('aria-valuenow', pct);
        progressText.textContent = offset + ' de ' + total + ' correos procesados…';

        if (!data.done) {
            await enviarLote();
        } else {
            progressBar.style.width = '100%';
            progressBar.classList.remove('progress-bar-animated');
            progressText.textContent = 'Envío completado. Cargando resultados…';
            window.location.href = urlResultado + enviosID;
        }
    }

    enviarLote().catch(err => {
        progressText.textContent = 'Error de conexión: ' + err.message;
        progressBar.classList.remove('progress-bar-animated');
        progressBar.classList.add('bg-danger');
        btnEnviar.disabled = false;
    });
});
</script>
<?= $this->endSection() ?>


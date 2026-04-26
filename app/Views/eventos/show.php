<?= $this->extend('plantillas/layout')?>
<?= $this->section('contenido')?>
<?php
$textoEventoHtml = trim((string) ($evento->texto ?? ''));
if ($textoEventoHtml !== '') {
    $textoEventoHtml = html_entity_decode($textoEventoHtml, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $textoEventoHtml = strip_tags($textoEventoHtml, '<p><br><strong><b><em><i><u><ul><ol><li><a><span><div><h1><h2><h3><h4><h5><h6><blockquote><hr><img>');
    $textoEventoHtml = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $textoEventoHtml) ?? $textoEventoHtml;
    $textoEventoHtml = preg_replace('/\s(href|src)\s*=\s*("|\')\s*javascript:[^"\']*("|\')/i', ' $1="#"', $textoEventoHtml) ?? $textoEventoHtml;
}
?>
<div class="container contenedor-4">
    <div class="item-0 item-3">
        <img src="<?= base_url('imgEventos/ev_' . $evento->id . '/cartel.jpg') ?>"
            onerror="this.onerror=null;this.src='<?= base_url('imgEventos/eventos.jpg') ?>'"
            alt="Cartel del evento <?= esc($evento->titulo) ?>">
        <h6 class="evento-titulo"><?= esc(strtoupper($evento->titulo)) ?></h6>
        <p class="desde-hasta">Desde el <?= esc(uti_fecha($evento->desde)) ?> hasta el <?= esc(uti_fecha($evento->hasta)) ?></p>
        <p class="estado-4 <?= uti_quita_(uti_estado_evento($evento->desde,$evento->hasta))?>">
            <?= esc(strtoupper($evento->grupo) . '   -   ' . uti_estado_evento($evento->desde, $evento->hasta)) ?>
        </p>
        <?= ($pdf or $inscripcion) ? "<div class='botones'>" : "" ?>

        <?php if($pdf): ?>
        <a class="bases-4" href="<?=base_url($pdf)?>" target="_blank" rel="noopener noreferrer">Bases</a>
        <?php endif; ?>

        <?php if($evento->aforo_completo): ?>

        <p class="bases-4">Aforo completo</p>

        <?php else: ?>
        <?php if($inscripcion): ?>
        <a class="bases-4" href="<?=base_url('inscripcionManual/'.$evento->id)?>">Inscribirse</a>
        <?php endif; ?>

        <?php endif; ?>
        <?= ($pdf or $inscripcion) ? "</div>" : "" ?>

    </div>
</div>

<!-- Botones de compartir -->
<div class="container mt-2 mb-3">
    <div class="d-flex justify-content-center gap-2 flex-wrap align-items-center">
        <span class="fw-semibold">Compartir:</span>
        <a href="https://twitter.com/intent/tweet?text=<?= rawurlencode(esc($evento->titulo)) ?>&url=<?= rawurlencode(current_url()) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">Twitter / X</a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode(current_url()) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">Facebook</a>
        <a href="https://api.whatsapp.com/send?text=<?= rawurlencode(esc($evento->titulo) . ' - ' . current_url()) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">WhatsApp</a>
    </div>
</div>
<article class="sobre_nosotros">
    <?= $textoEventoHtml ?>
</article>
<?php if(count($fotos)>0): ?>
<div class="container contenedor-4">
    <!-- Modal -->
    <div id="modal" class="modal">
        <span class="close">&times;</span>
        <img id="imagenAmpliada">
    </div>

    <?php foreach($fotos as $foto): ?>
    <div class="item-0 item-4 foto-4">
        <img src="<?= base_url('imgEventos/ev_' . $evento->id . '/' . rawurlencode($foto)) ?>" alt="Imagen del evento <?= esc($evento->titulo) ?>"
            onerror="this.closest('.foto-4').style.display='none';"
            class="card-img-top">
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
const modal = document.getElementById("modal");
const imagenAmpliada = document.getElementById("imagenAmpliada");
const close = document.querySelector(".close");

document.querySelectorAll(".item-0 img").forEach(img => {
    img.addEventListener("click", () => {
        imagenAmpliada.src = img.src;
        modal.style.display = "flex";
    });
});

close.addEventListener("click", () => {
    modal.style.display = "none";
});

modal.addEventListener("click", (e) => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        modal.style.display = "none";
    }
});
</script>


<?= $this->endSection()?>

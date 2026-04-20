<?php if (session()->logueado): ?>
<header class="admin-topbar sticky-top shadow-sm">
    <div class="container-fluid px-3 px-md-4">
        <div class="d-flex align-items-center justify-content-between gap-3 py-2">
            <div class="d-flex align-items-center gap-3 min-w-0">
                <img class="admin-brand" src="<?php echo base_url('recursos/imagenes/anagramaColor.png'); ?>"
                    alt="Cercle d'Art de Foios">
                <div class="min-w-0">
                    <div class="admin-eyebrow">Backoffice</div>
                    <h1 class="admin-title mb-0"><?php echo esc($titulo); ?></h1>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-light btn-sm" title="Ir al Menú"
                    href="<?php echo base_url('dashboard'); ?>">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    <span class="ms-1 d-none d-sm-inline">Menú</span>
                </a>
            </div>
        </div>
    </div>
</header>
<?php endif; ?>

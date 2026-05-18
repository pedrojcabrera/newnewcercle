<?php
/** @var string $dashboardLogo */
/** @var array<string, int> $dashboardCounts */
/** @var array<string, array<string, mixed>> $dashboardExtras */
/** @var array<string, int> $dashboardHistory */
?>
<?= $this->extend('admin/plantillas/layout'); ?>
<?= $this->section('contenido'); ?>

<?php if (session()->logueado): ?>
<div class="container dashboard-admin">
    <div class="dashboard-hero text-center">
        <img class="dashboard-logo" src="<?= (string) esc($dashboardLogo ?? base_url('recursos/imagenes/anagramaColor.png')); ?>"
            alt="Cercle d'Art de Foios">
        <p class="dashboard-subtitle mb-0">Bienvenido al panel de administración</p>
    </div>

    <div class="row g-3 g-lg-4 mt-1 justify-content-center">
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-1" href="<?= base_url('control/usuarios'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['usuarios'] ?? 0)) ?></span>
                <i class="bi bi-people-fill"></i>
                <span>Usuarios</span>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['usuarios']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['usuarios']['value'] ?? 0)) ?></small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-2" href="<?= base_url('control/sistema'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['sistema'] ?? 0)) ?></span>
                <i class="bi bi-gear-fill"></i>
                <span>Sistema</span>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['sistema']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['sistema']['value'] ?? 0)) ?></small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-3" href="<?= base_url('control/enlaces'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['enlaces'] ?? 0)) ?></span>
                <i class="bi bi-link-45deg"></i>
                <span>Enlaces</span>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-4" href="<?= base_url('control/contactos'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['contactos'] ?? 0)) ?></span>
                <i class="bi bi-person-lines-fill"></i>
                <span>Contactos</span>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['contactos']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['contactos']['value'] ?? 0)) ?></small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-5" href="<?= base_url('control/correos'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['correos'] ?? 0)) ?></span>
                <i class="bi bi-envelope-paper-fill"></i>
                <span>Correos Genéricos</span>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['correos']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['correos']['value'] ?? 0)) ?></small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-6" href="<?= base_url('control/tipos'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['tipos'] ?? 0)) ?></span>
                <i class="bi bi-tags-fill"></i>
                <span>Tipos de Evento</span>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['tipos']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['tipos']['value'] ?? 0)) ?></small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-7" href="<?= base_url('control/eventos'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['eventos'] ?? 0)) ?></span>
                <i class="bi bi-calendar-event-fill"></i>
                <span>Eventos</span>
                <?php if (!empty($dashboardExtras['eventos']['items']) && is_array($dashboardExtras['eventos']['items'])): ?>
                <?php foreach ($dashboardExtras['eventos']['items'] as $eventoExtra): ?>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($eventoExtra['label'] ?? '')) ?>: <?= (string) esc((string) ($eventoExtra['value'] ?? 0)) ?></small>
                <?php endforeach; ?>
                <?php else: ?>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['eventos']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['eventos']['value'] ?? 0)) ?></small>
                <?php endif; ?>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-8" href="<?= base_url('control/inscripcionManual'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['inscripcionManual'] ?? 0)) ?></span>
                <i class="bi bi-person-plus-fill"></i>
                <span>Inscripción Manual</span>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['inscripcionManual']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['inscripcionManual']['value'] ?? 0)) ?></small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <a class="dashboard-card dashboard-card-9" href="<?= base_url('control/emailsIns'); ?>">
                <span class="dashboard-pill"><?= (string) esc((string) ($dashboardCounts['emailsIns'] ?? 0)) ?></span>
                <i class="bi bi-envelope-check-fill"></i>
                <span>Solicitudes por Correo</span>
                <small class="dashboard-pill-secondary"><?= (string) esc((string) ($dashboardExtras['emailsIns']['label'] ?? '')) ?>: <?= (string) esc((string) ($dashboardExtras['emailsIns']['value'] ?? 0)) ?></small>
            </a>
        </div>
        <div class="col-12">
            <div class="dashboard-card dashboard-card-static dashboard-card-history" role="group" aria-label="Resumen histórico">
                <div class="dashboard-history-title-wrap">
                    <i class="bi bi-clock-history"></i>
                    <span class="dashboard-history-title">Histórico global</span>
                </div>
                <div class="dashboard-history-grid">
                    <span class="dashboard-history-chip">Campañas: <?= (string) esc((string) ($dashboardHistory['campanas'] ?? 0)) ?></span>
                    <span class="dashboard-history-chip">Envíos: <?= (string) esc((string) ($dashboardHistory['envios'] ?? 0)) ?></span>
                    <span class="dashboard-history-chip">Errores: <?= (string) esc((string) ($dashboardHistory['errores'] ?? 0)) ?></span>
                    <span class="dashboard-history-chip">Inscritos: <?= (string) esc((string) ($dashboardHistory['inscritos'] ?? 0)) ?></span>
                    <span class="dashboard-history-chip">Lista espera: <?= (string) esc((string) ($dashboardHistory['espera'] ?? 0)) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection(); ?>

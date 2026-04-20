<?php if(session()->hayGalerias): ?>
<?php
    $__autor = isset($data) ? $data : (isset($usuario) ? $usuario : null);
    $__nombre = $__autor ? $__autor->nombre : '';
    $currentPath = trim(service('request')->getUri()->getPath(), '/');
    $isActiveGal = static function (string $prefix) use ($currentPath): bool {
        return $currentPath === $prefix || str_starts_with($currentPath, $prefix . '/');
    };
?>
<aside class="gal-sidebar">

    <a class="gal-sidebar-brand" href="<?= base_url('galeristas/lista') ?>" title="Mis obras">
        <img src="<?= base_url('recursos/imagenes/anagramaColor.png') ?>" alt="Cercle d'Art de Foios">
    </a>

    <?php if ($__nombre): ?>
    <div class="gal-sidebar-autor">
        <?= esc($__nombre) ?>
    </div>
    <?php endif; ?>

    <nav class="gal-sidebar-nav" aria-label="Menú galerista">
        <a class="gal-nav-link<?= $isActiveGal('galeristas/lista') ? ' is-active' : '' ?>"
           href="<?= base_url('galeristas/lista') ?>"
           <?= $isActiveGal('galeristas/lista') ? 'aria-current="page"' : '' ?>>
            <i class="bi bi-grid-fill"></i><span>Mis Obras</span>
        </a>
        <a class="gal-nav-link<?= $isActiveGal('galeristas/nuevo') || $isActiveGal('galeristas/editar') ? ' is-active' : '' ?>"
           href="<?= base_url('galeristas/nuevo') ?>"
           <?= $isActiveGal('galeristas/nuevo') || $isActiveGal('galeristas/editar') ? 'aria-current="page"' : '' ?>>
            <i class="bi bi-easel-fill"></i><span>Nueva Obra</span>
        </a>
    </nav>

    <div class="gal-sidebar-footer">
        <a class="btn btn-danger btn-sm w-100" href="<?= base_url('galeristas/logout') ?>" title="Cerrar sesión">
            <i class="bi bi-door-open-fill"></i>
            <span class="ms-1">Salir</span>
        </a>
    </div>

</aside>
<?php endif; ?>

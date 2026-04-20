<!doctype html>
<html lang="es">

    <head>
        <title>Administración</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico');?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lexend+Giga:wght@100..900&
    family=Quicksand:wght@300..700&
    family=Roboto:wght@100;300;400;500;700;900&
    display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
        <link rel="stylesheet" type="text/css"
            href="<?=base_url('recursos/styleAdmin.css')?>">

        <?= $this->renderSection('masStyle') ?>

    </head>

    <body class="admin-body">
        <?php if (session()->logueado): ?>
        <?php
        $currentPath = trim(service('request')->getUri()->getPath(), '/');
        $isActiveAdminLink = static function (array $prefixes) use ($currentPath): bool {
            foreach ($prefixes as $prefix) {
                if ($prefix === '') {
                    if ($currentPath === '') {
                        return true;
                    }

                    continue;
                }

                if ($currentPath === $prefix || str_starts_with($currentPath, $prefix . '/')) {
                    return true;
                }
            }

            return false;
        };
        ?>
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <a class="admin-sidebar-brand" href="<?= base_url('dashboard') ?>" title="Inicio administración">
                    <img src="<?= base_url('recursos/imagenes/anagramaColor.png'); ?>" alt="Cercle d'Art de Foios">
                </a>

                <nav class="admin-sidebar-nav" aria-label="Menú administración">
                    <a class="admin-nav-link<?= $isActiveAdminLink(['dashboard']) ? ' is-active' : '' ?>" href="<?= base_url('dashboard') ?>"<?= $isActiveAdminLink(['dashboard']) ? ' aria-current="page"' : '' ?>><i class="bi bi-house-door-fill"></i><span>Inicio</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/usuarios']) ? ' is-active' : '' ?>" href="<?= base_url('control/usuarios') ?>"<?= $isActiveAdminLink(['control/usuarios']) ? ' aria-current="page"' : '' ?>><i class="bi bi-people-fill"></i><span>Usuarios</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/sistema']) ? ' is-active' : '' ?>" href="<?= base_url('control/sistema') ?>"<?= $isActiveAdminLink(['control/sistema']) ? ' aria-current="page"' : '' ?>><i class="bi bi-gear-fill"></i><span>Sistema</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/enlaces']) ? ' is-active' : '' ?>" href="<?= base_url('control/enlaces') ?>"<?= $isActiveAdminLink(['control/enlaces']) ? ' aria-current="page"' : '' ?>><i class="bi bi-link-45deg"></i><span>Enlaces</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/contactos']) ? ' is-active' : '' ?>" href="<?= base_url('control/contactos') ?>"<?= $isActiveAdminLink(['control/contactos']) ? ' aria-current="page"' : '' ?>><i class="bi bi-person-lines-fill"></i><span>Contactos</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/correos']) ? ' is-active' : '' ?>" href="<?= base_url('control/correos') ?>"<?= $isActiveAdminLink(['control/correos']) ? ' aria-current="page"' : '' ?>><i class="bi bi-envelope-paper-fill"></i><span>Correos</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/tipos']) ? ' is-active' : '' ?>" href="<?= base_url('control/tipos') ?>"<?= $isActiveAdminLink(['control/tipos']) ? ' aria-current="page"' : '' ?>><i class="bi bi-tags-fill"></i><span>Tipos de evento</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/eventos', 'control/inscritos', 'control/invitados', 'control/enEspera', 'control/listarInscritos']) ? ' is-active' : '' ?>" href="<?= base_url('control/eventos') ?>"<?= $isActiveAdminLink(['control/eventos', 'control/inscritos', 'control/invitados', 'control/enEspera', 'control/listarInscritos']) ? ' aria-current="page"' : '' ?>><i class="bi bi-calendar-event-fill"></i><span>Eventos</span></a>
                    <a class="admin-nav-link<?= $isActiveAdminLink(['control/inscripcionManual']) ? ' is-active' : '' ?>" href="<?= base_url('control/inscripcionManual') ?>"<?= $isActiveAdminLink(['control/inscripcionManual']) ? ' aria-current="page"' : '' ?>><i class="bi bi-person-plus-fill"></i><span>Inscripción manual</span></a>
                </nav>

                <div class="admin-sidebar-footer">
                    <a class="btn btn-danger btn-sm w-100" href="<?= base_url('logout') ?>" title="Cerrar sesión">
                        <i class="bi bi-door-open-fill"></i>
                        <span class="ms-1">Salir</span>
                    </a>
                </div>
            </aside>

            <div class="admin-content">
                <header class="admin-actionbar">
                    <h1 class="admin-action-title"><?= esc($titulo ?? 'Administración') ?></h1>
                </header>

                <main class="admin-main container-fluid px-3 px-md-4 pb-4">
                    <div class="admin-surface mx-auto">
                        <?= $this->renderSection('contenido') ?>
                    </div>
                </main>
            </div>
        </div>
        <?php else: ?>
        <main class="admin-auth-main container-fluid px-3 px-md-4 pb-4">
            <div class="admin-surface mx-auto">
                <?= $this->renderSection('contenido') ?>
            </div>
        </main>
        <?php endif; ?>

        <?= $this->include('admin/plantillas/footer') ?>

        <?= $this->renderSection('masJS') ?>

    </body>

</html>

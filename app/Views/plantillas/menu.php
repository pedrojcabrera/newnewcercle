<!-- HEADER: MENU + HEROE SECTION -->
<header>

    <div class="menu">
        <ul id="mainNav">
            <li class="logo">
                <h1><?=$titulo?></h1>
            </li>
            <li class="menu-toggle">
                <button id="menuToggle" type="button" aria-label="Abrir o cerrar menu" aria-expanded="false" aria-controls="mainNav">&#9776;</button>
            </li>
            <li class="menu-item hidden">
                <a href="<?= base_url('/') ?>">Inicio</a>
            </li>
            <li class="menu-item hidden">
                <a href="<?= base_url('ultimos_eventos') ?>">Ultimos Eventos</a>
            </li>
            <li class="menu-item hidden">
                <a href="<?= base_url('eventos') ?>">Histórico</a>
            </li>
            <li class="menu-item hidden">
                <a href="<?= base_url('pinturas') ?>">Galerías</a>
            </li>
            <li class="menu-item hidden">
                <a href="<?= base_url('contactar') ?>">Contactar</a>
            </li>
        </ul>
    </div>

    <div class="heroe">
        <img src="<?= base_url('recursos/imagenes/anagramaColor.png')?>" alt="Logo de Cercle d'Art de Foios">
    </div>

</header>

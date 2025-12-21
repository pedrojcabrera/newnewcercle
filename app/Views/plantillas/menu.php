<!-- HEADER: MENU + HEROE SECTION -->
<header>

    <div class="menu">
        <ul>
            <li class="logo">
                <h1><?=$titulo?></h1>
            </li>
            <li class="menu-toggle">
                <button id="menuToggle">&#9776;</button>
            </li>
            <li class="menu-item hidden">
                <a href="/">Inicio</a>
            </li>
            <li class="menu-item hidden">
                <a href="/ultimos_eventos">Ultimos Eventos</a>
            </li>
            <li class="menu-item hidden">
                <a href="/eventos">Histórico</a>
            </li>
            <li class="menu-item hidden">
                <a href="/pinturas">Galerías</a>
            </li>
            <li class="menu-item hidden">
                <a href="/contactar">Contactar</a>
            </li>
        </ul>
    </div>

    <div class="heroe">
        <img src="<?= base_url('recursos/imagenes/anagramaColor.png',$_SERVER['REQUEST_SCHEME'])?>" alt="">
    </div>

</header>
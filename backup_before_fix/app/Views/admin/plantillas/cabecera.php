<?php if (session()->logueado): ?>
<div class="cabecera">
    <h5><?php echo $titulo; ?></h5>
    <img src="<?php echo base_url('recursos/imagenes/anagramaColor.png'); ?>" alt="">
    <a class="btn btn-success btn-sm bi-door-open" title="Ir al Menú"
        href="<?php echo base_url('dashboard', $_SERVER['REQUEST_SCHEME']); ?>"> Menú</a>
    </li>
    </a>

</div>
<?php endif; ?>
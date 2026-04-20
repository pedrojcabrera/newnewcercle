   <html lang="es">

       <head>
           <title>Administración</title>

           <meta charset="utf-8">
           <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
           <link rel="preconnect" href="https://fonts.googleapis.com">
           <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
           <link href="https://fonts.googleapis.com/css2?family=Lexend+Giga:wght@100..900&
    family=Quicksand:wght@300..700&
    family=Roboto:wght@100;300;400;500;700;900&
    display=swap" rel="stylesheet">
           <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
               integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
               crossorigin="anonymous">
           <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
           <link rel="stylesheet" type="text/css"
               href="<?php echo base_url('recursos/DataTables/datatables.min.css', $_SERVER['REQUEST_SCHEME']); ?>">
           <link rel="stylesheet" type="text/css"
               href="<?php echo base_url('recursos/styleMenu.css', $_SERVER['REQUEST_SCHEME']); ?>">
           <?php echo
       "<style>
            body {
               background: url('" . base_url('recursos/imagenes/miroconpajaroygato.webp') . "') no-repeat center center fixed;
               height: 100vh;
               background-size: cover;
               opacity: 0.9;
               }
       </style>";
      ?>

           <?php echo $this->renderSection('masStyle'); ?>

       </head>

       <body>
           <?php if (session()->logueado): ?>
           <div class="container">
               <div class="cabecera">
                   <img src="<?php echo base_url('recursos/imagenes/anagramaColor.png'); ?>" alt="">
               </div>
               <div class="gallery">
                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/usuarios', $_SERVER['REQUEST_SCHEME']); ?>">Usuarios</a>
                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/sistema', $_SERVER['REQUEST_SCHEME']); ?>">Sistema</a>

                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/enlaces', $_SERVER['REQUEST_SCHEME']); ?>">Enlaces</a>

                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/contactos', $_SERVER['REQUEST_SCHEME']); ?>">Contactos</a>
                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/correos', $_SERVER['REQUEST_SCHEME']); ?>">Correos
                       Genéricos</a>

                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/tipos', $_SERVER['REQUEST_SCHEME']); ?>">Tipos
                       de
                       Evento</a>
                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/eventos', $_SERVER['REQUEST_SCHEME']); ?>">Eventos</a>
                   <!-- 
                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/emailsIns', $_SERVER['REQUEST_SCHEME']); ?>">Listas
                       <br>de<br>Espera
                   </a>
 -->
                   <a class="gallery-item elemento"
                       href="<?php echo base_url('control/inscripcionManual', $_SERVER['REQUEST_SCHEME']); ?>">Inscripción
                       manual a
                       un evento</a>
                   <!-- <a class="gallery-item elemento"
               href="<?php echo base_url('control/galerias', $_SERVER['REQUEST_SCHEME']); ?>">Galerías</a> -->
               </div>
               <div class="gallery">
                   <a class="elemento salir bi-door-open" title="Cerrar sesión"
                       href="<?php echo base_url('logout', $_SERVER['REQUEST_SCHEME']); ?>">
                       Salir</a>
               </div>
           </div>
           <!-- ---------------------------------------------------- -->
           <script>
           // Agregar una entrada en el historial para evitar el retroceso
           window.history.pushState(null, "", window.location.href);
           window.onpopstate = function() {
               window.history.pushState(null, "", window.location.href);
           };
           </script>
       </body>


   </html>
   <?php endif; ?>
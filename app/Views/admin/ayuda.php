<?= $this->extend('admin/plantillas/layout'); ?>
<?= $this->section('contenido'); ?>

<div class="ayuda-dashboard">

    <div class="ayuda-back-row">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>

    <div class="ayuda-intro">
        <div class="ayuda-intro-icon"><i class="bi bi-question-circle-fill"></i></div>
        <div>
            <h2 class="ayuda-section-title">Guía del Panel de administración</h2>
            <p class="ayuda-intro-text">El Panel muestra de un vistazo los datos más importantes de la aplicación. Esta guía explica qué representa cada tarjeta, qué mide cada número y qué significan los conceptos que aparecen.</p>
        </div>
    </div>

    <!-- ═══ BLOQUE: estructura de tarjeta ═══ -->
    <section class="ayuda-block">
        <h3 class="ayuda-block-title"><i class="bi bi-grid-1x2-fill me-2"></i>Estructura de una tarjeta</h3>
        <p class="ayuda-block-lead">Cada tarjeta enlaza con una sección del panel y contiene hasta dos indicadores visuales denominados <strong>píldoras</strong>:</p>
        <div class="ayuda-pills-legend">
            <div class="ayuda-pills-legend-item">
                <span class="ayuda-pill-demo ayuda-pill-top">12</span>
                <div>
                    <strong>Píldora principal</strong> — aparece en la esquina superior derecha de la tarjeta.<br>
                    <span class="text-muted">Muestra el <em>total de registros</em> de esa sección en la base de datos.</span>
                </div>
            </div>
            <div class="ayuda-pills-legend-item">
                <span class="ayuda-pill-demo ayuda-pill-bottom">Admins: 2</span>
                <div>
                    <strong>Píldora secundaria</strong> — aparece en la parte inferior de la tarjeta.<br>
                    <span class="text-muted">Muestra un <em>subconjunto o acumulado destacado</em> dentro de ese total, para dar contexto rápido.</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ BLOQUE: tarjetas ═══ -->
    <section class="ayuda-block">
        <h3 class="ayuda-block-title"><i class="bi bi-card-list me-2"></i>Qué significa cada tarjeta</h3>

        <div class="ayuda-cards-grid">

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-1"><i class="bi bi-people-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Usuarios</h4>
                    <p>Personas con acceso al panel de administración. Solo los usuarios marcados como <em>administradores</em> pueden iniciar sesión aquí.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Total de usuarios registrados.</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">Admins: N</span>
                        <span class="ayuda-concept-pill-desc">Cuántos de ellos tienen el rol de administrador activo.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-2"><i class="bi bi-gear-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Sistema</h4>
                    <p>Configuración general de la asociación: nombre, imagen, datos de contacto y si la web pública está visible o en mantenimiento.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Número de registros de configuración (normalmente 1).</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">Visible: N</span>
                        <span class="ayuda-concept-pill-desc"><strong>1</strong> = la web pública está activa y accesible. <strong>0</strong> = está oculta al público.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-3"><i class="bi bi-link-45deg"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Enlaces</h4>
                    <p>Listado de enlaces de interés que se publican en la web pública (redes sociales, recursos externos, etc.).</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Total de enlaces publicados.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-4"><i class="bi bi-person-lines-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Contactos</h4>
                    <p>Base de datos de personas relacionadas con la asociación: socios, interesados, colaboradores... Son los destinatarios de los mailings y de las inscripciones a eventos.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Total de contactos registrados.</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">Con mailing: N</span>
                        <span class="ayuda-concept-pill-desc">Contactos que han dado su conformidad para recibir correos masivos (mailings). Solo estos recibirán las campañas.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-5"><i class="bi bi-envelope-paper-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Correos Genéricos</h4>
                    <p>Plantillas de correo electrónico reutilizables (bienvenida, confirmación de inscripción, etc.) que el sistema usa al enviar comunicaciones automáticas.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Total de plantillas configuradas.</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">Enviados: N</span>
                        <span class="ayuda-concept-pill-desc">Suma histórica de todos los correos enviados en campañas de mailing desde que existe la aplicación.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-6"><i class="bi bi-tags-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Tipos de Evento</h4>
                    <p>Categorías que clasifican los eventos: taller, conferencia, exposición, etc. Un tipo de evento agrupa eventos similares y puede usarse para filtrar.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Total de tipos definidos.</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">En uso: N</span>
                        <span class="ayuda-concept-pill-desc">Cuántos tipos tienen al menos un evento asignado actualmente.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-7"><i class="bi bi-calendar-event-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Eventos</h4>
                    <p>Actividades organizadas por la asociación (talleres, exposiciones, charlas...). Cada evento tiene fechas, aforo, período de inscripción y puede tener inscritos, invitados y lista de espera.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Total de eventos registrados, de cualquier estado o fecha.</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">Invitados pendientes: N</span>
                        <span class="ayuda-concept-pill-desc">Personas invitadas directamente a un evento que aún no han confirmado su asistencia ni están en lista de espera.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-8"><i class="bi bi-person-plus-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Inscripción Manual</h4>
                    <p>Permite al administrador inscribir a una persona directamente en un evento, sin que ella tenga que hacerlo desde la web. Solo aparecen los eventos con inscripción <strong>activa en este momento</strong>.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Eventos cuyo período de inscripción está abierto <em>hoy</em> y no están cerrados.</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">Inscritos totales: N</span>
                        <span class="ayuda-concept-pill-desc">Suma de todas las inscripciones confirmadas en todos los eventos, desde el inicio.</span>
                    </div>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-9"><i class="bi bi-envelope-check-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Solicitudes por Correo</h4>
                    <p>Cuando alguien solicita inscribirse a un evento a través de un formulario de correo electrónico, la solicitud queda aquí pendiente hasta que el administrador la procesa.</p>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-top">N</span>
                        <span class="ayuda-concept-pill-desc">Solicitudes recibidas que aún <strong>no han sido procesadas</strong>. Requieren atención.</span>
                    </div>
                    <div class="ayuda-pills-row">
                        <span class="ayuda-pill-demo ayuda-pill-bottom">Ya inscritos: N</span>
                        <span class="ayuda-concept-pill-desc">Solicitudes ya gestionadas: el contacto fue inscrito al evento correspondiente.</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ═══ BLOQUE: histórico global ═══ -->
    <section class="ayuda-block">
        <h3 class="ayuda-block-title"><i class="bi bi-clock-history me-2"></i>Histórico global</h3>
        <p class="ayuda-block-lead">La banda inferior del Panel muestra acumulados de <em>toda la historia de la aplicación</em>, independientemente del ejercicio o del estado actual de los registros.</p>

        <div class="ayuda-history-grid">

            <div class="ayuda-history-item">
                <div class="ayuda-history-chip-demo">Campañas</div>
                <div class="ayuda-history-desc">
                    <strong>¿Qué es una campaña?</strong> Una campaña de mailing es un envío masivo de correo electrónico a un grupo de contactos seleccionados. Por ejemplo: «Convocatoria a la exposición de primavera» enviada a todos los socios con mailing activo. Cada vez que se lanza un envío desde la sección <em>Correos</em>, se registra como una campaña.<br>
                    <span class="text-muted">Este número indica cuántas campañas se han lanzado en total desde el sistema.</span>
                </div>
            </div>

            <div class="ayuda-history-item">
                <div class="ayuda-history-chip-demo">Envíos</div>
                <div class="ayuda-history-desc">
                    Suma total de correos individuales enviados en <em>todas</em> las campañas. Si una campaña se envía a 80 contactos, suma 80 envíos. Este número puede ser mucho mayor que el de campañas.
                </div>
            </div>

            <div class="ayuda-history-item">
                <div class="ayuda-history-chip-demo">Errores</div>
                <div class="ayuda-history-desc">
                    Correos que no llegaron a su destino por algún fallo técnico (dirección inválida, servidor rechazante, tiempo de espera agotado, etc.) y quedaron registrados con un mensaje de error. Un valor elevado puede indicar que hay contactos con direcciones desactualizadas.
                </div>
            </div>

            <div class="ayuda-history-item">
                <div class="ayuda-history-chip-demo">Inscritos</div>
                <div class="ayuda-history-desc">
                    Total de inscripciones confirmadas a eventos, sumando todos los eventos de todos los ejercicios. Incluye inscripciones tanto manuales como realizadas desde la web.
                </div>
            </div>

            <div class="ayuda-history-item">
                <div class="ayuda-history-chip-demo">Lista espera</div>
                <div class="ayuda-history-desc">
                    Total acumulado de personas que, en algún momento, se quedaron en lista de espera por haber llegado cuando el aforo de un evento ya estaba completo.
                </div>
            </div>

        </div>
    </section>

    <!-- ═══ BLOQUE: web pública ═══ -->
    <section class="ayuda-block">
        <h3 class="ayuda-block-title"><i class="bi bi-globe2 me-2"></i>Web pública — funcionalidades</h3>
        <p class="ayuda-block-lead">Resumen de las secciones y características disponibles en la web pública de la asociación.</p>

        <div class="ayuda-cards-grid">

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-3"><i class="bi bi-calendar2-week-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Histórico de eventos</h4>
                    <p>Accesible en <code>/eventos</code>. Muestra todos los eventos visibles paginados de 24 en 24, ordenados del más reciente al más antiguo.</p>
                    <p>Dispone de un <strong>filtro por año</strong>: al pulsar un año solo se muestran los eventos cuya fecha de fin cae en ese año. La paginación conserva el año seleccionado.</p>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-4"><i class="bi bi-images"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Galerías de artistas</h4>
                    <p>Accesible en <code>/galerias</code> (antes <code>/pinturas</code>). Los enlaces antiguos a <code>/pinturas</code> redirigen automáticamente a la nueva URL con un código 301.</p>
                    <p>Los artistas aparecen ordenados <strong>alfabéticamente por nombre</strong>. Cada obra en venta incluye un botón <em>«Contactar sobre esta obra»</em> que abre el formulario de contacto con el título de la obra pre-rellenado.</p>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-5"><i class="bi bi-share-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Compartir eventos</h4>
                    <p>En la ficha de cada evento aparecen botones para compartir directamente en <strong>Twitter/X</strong>, <strong>Facebook</strong> y <strong>WhatsApp</strong>, facilitando la difusión de actividades.</p>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-6"><i class="bi bi-diagram-3-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Sitemap</h4>
                    <p>Accesible en <code>/sitemap.xml</code>. Se genera dinámicamente e incluye la portada, las páginas principales, todos los eventos visibles y todas las galerías de artistas. Facilita la indexación por parte de Google y otros buscadores.</p>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-7"><i class="bi bi-shield-check-fill"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Política de privacidad</h4>
                    <p>Accesible en <code>/privacidad</code>. Informa a los visitantes sobre el uso de sus datos en los formularios y el uso de Google reCAPTCHA, en cumplimiento del RGPD.</p>
                    <p>Un <strong>aviso de cookies</strong> aparece en la parte inferior de todas las páginas hasta que el visitante lo acepta (la aceptación se guarda en el navegador y no vuelve a mostrarse).</p>
                </div>
            </div>

            <div class="ayuda-concept-card">
                <div class="ayuda-concept-icon ayuda-icon-8"><i class="bi bi-window-fullscreen"></i></div>
                <div class="ayuda-concept-body">
                    <h4>Redes sociales (Open Graph)</h4>
                    <p>Todas las páginas incluyen etiquetas <strong>Open Graph</strong>. Cuando alguien comparte un enlace en redes sociales, se muestra automáticamente el título, descripción e imagen correspondientes al evento o galería en cuestión, en lugar de un texto genérico.</p>
                </div>
            </div>

        </div>
    </section>

</div>

<?= $this->endSection(); ?>

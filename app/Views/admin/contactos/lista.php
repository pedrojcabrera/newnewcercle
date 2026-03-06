<?php echo $this->extend('admin/plantillas/layout'); ?>
<?php echo $this->section('contenido'); ?>



<div class="container">
    <div class="botones-superiores">
        <div class="boton-agregar">
            <a name="" id="" title="Crear" class="btn btn-primary btn-sm bi-person-plus"
                href="<?php echo base_url('control/contactos/nuevo'); ?>" role="button">
                Nuevo Contacto</a>
        </div>
        <div class="boton-cancelar">
            <a name="cancelar" id="cancelar" class="btn btn-success btn-sm bi-box-arrow-left"
                href="<?php echo base_url('dashboard'); ?>" role="button" title="Cancelar">
                Cancelar</a>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="miTabla mt-3" id="contactos-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Contacto</th>
                        <th scope="col">Contactar</th>
                        <th scope="col">Calidad</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se cargan vía AJAX para mejorar el rendimiento -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $this->endSection(); ?>

<?php echo $this->section('masJS'); ?>
<script>
$(document).ready(function() {
    $("#contactos-table").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo base_url('control/contactos/ajax'); ?>',
            type: 'GET',
            error: function(xhr, error, code) {
                console.error('Error al cargar datos:', error);
            }
        },
        columns: [
            { data: 0, orderable: true },  // ID
            { data: 1, orderable: true },  // Contacto
            { data: 2, orderable: false }, // Contactar
            { data: 3, orderable: false }, // Calidad
            { data: 4, orderable: false }  // Acciones
        ],
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 250, 500],
            [10, 25, 50, 100, 250, 500]
        ],
        order: [[1, 'asc']], // Ordenar por nombre por defecto
        language: {
            decimal: ',',
            thousands: '.',
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
            processing: 'Procesando...',
            loadingRecords: 'Cargando...',
        },
        drawCallback: function(settings) {
            // Re-aplicar estilos o eventos después de cada redibujado si es necesario
        }
    });
});
</script>
<?php echo $this->endSection(); ?>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<!-- Bootstrap JavaScript Libraries -->
<script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
</script>
<!-- ----------------------------------------------------- -->
<script type="text/javascript" src="<?=base_url('recursos/DataTables/datatables.min.js')?>">
</script>
<!-- ---------------------------------------------------- -->

<script>
$(document).ready(function() {
    $("#datatable").DataTable({
        pageLength: 15,
        "lengthMenu": [
            [3, 4, 5, 8, 10, 15, 25, 50, 75, 100, 150, 250, 500],
            [3, 4, 5, 8, 10, 15, 25, 50, 75, 100, 150, 250, 500]
        ],
        order: [
            [0, 'desc']
        ],
        language: {
            decimal: ',',
            thousands: '.',
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
        },
    });
});

$(document).ready(function() {
    $("#datatable2").DataTable({
        pageLength: 15,
        "lengthMenu": [
            [3, 4, 5, 8, 10, 15, 25, 50, 75, 100, 150, 250, 500],
            [3, 4, 5, 8, 10, 15, 25, 50, 75, 100, 150, 250, 500]
        ],
        order: [
            [0, 'desc']
        ],
        language: {
            decimal: ',',
            thousands: '.',
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
        },
    });
});
</script>
<!-- ---------------------------------------------------- -->
<script>
// Agregar una entrada en el historial para evitar el retroceso
window.history.pushState(null, "", window.location.href);
window.onpopstate = function() {
    window.history.pushState(null, "", window.location.href);
};
</script>
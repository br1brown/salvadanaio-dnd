// Questo script utilizza la libreria SweetAlert2 per mostrare avvisi personalizzati.
// La documentazione della libreria è disponibile su: https://sweetalert2.github.io/

// Attende che il DOM sia completamente caricato
$(document).ready(function () {
    $(".bottone").click(function () {
        $(this).blur();
        var val = $(this).val();
        var tipo = $(this).data("type");

        if (tipo && tipo != "") {
            swal.fire(tipo + ": Sono io", val, tipo);
        } else {
            swal.fire("Sono io di Br1Brown", val);
        }
    });
});

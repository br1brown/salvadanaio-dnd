// Questo script utilizza la libreria SweetAlert2 per mostrare avvisi personalizzati.
// La documentazione della libreria è disponibile su: https://sweetalert2.github.io/

// Attende che il DOM sia completamente caricato
$(document).ready(function () {
    // Gestisce il click su elementi con classe 'bottone'
    $(".bottone").click(function () {
        // Rimuove il focus dal bottone dopo il click
        $(this).blur();

        // Recupera il valore (val) e il tipo (data-type) del bottone cliccato
        var val = $(this).val();
        var tipo = $(this).data("type");

        // Controlla se il 'tipo' è definito e non vuoto
        if (tipo && tipo != "") {
            // Visualizza un SweetAlert con il tipo e il valore del bottone
            swal.fire(tipo + ": Sono io", val, tipo);
        } else {
            // Se 'tipo' non è definito, visualizza un SweetAlert con un messaggio predefinito
            swal.fire("Sono io di Br1Brown", val);
        }
    });
});

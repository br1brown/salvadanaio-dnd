<?php

include dirname(__DIR__) . '/BLL/auth_and_cors_middleware.php';

function eseguiPOST()
{
    echo Echo_getObj("personaggi", function ($infos_personaggi, $lingua) {

        // Controllo input valuta
        $daSpendere = new BLL\Cash(
            intval($_POST['platinum'] ?? 0),
            intval($_POST['gold'] ?? 0),
            intval($_POST['silver'] ?? 0),
            intval($_POST['copper'] ?? 0)
        );

        return allaRomana($infos_personaggi, $daSpendere, $_POST['description']);
    });
}

include dirname(path: __DIR__) . '/BLL/gestione_metodi.php';

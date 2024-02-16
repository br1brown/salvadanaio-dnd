<?php
include __DIR__ . '/BLL/auth_and_cors_middleware.php';

function eseguiGET()
{
    if (isset($_GET["basename"])) {
        $personaggio = new BLL\Personaggio($_GET["basename"]);
        echo json_encode($personaggio->getInventario());
    }
}

function eseguiPOST()
{
    // Controlla se i parametri richiesti sono presenti
    if (isset($_POST["basename"]) && isset($_POST["itemname"]) && isset($_POST["quantity"]) && isset($_POST["description"])) {
        $personaggio = new BLL\Personaggio($_POST["basename"]);

        // Assicurati che la quantità sia un intero
        $quantity = intval($_POST["quantity"]);

        $personaggio->setInventario($_POST["itemname"], $quantity, $_POST["description"]);
        // Risposta in caso di successo
        echo BLL\Response::retOK("Inventario aggiornato");

    } else {
        throw new Exception("Parametri mancanti");
    }
}



include __DIR__ . '/BLL/gestione_metodi.php';


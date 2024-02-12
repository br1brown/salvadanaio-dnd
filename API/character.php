<?php
include __DIR__.'/BLL/auth_and_cors_middleware.php';

function eseguiGET(){
    if (isset($_GET["basename"]))
        echo (new BLL\Personaggio($_GET["basename"]))->getData();
}

function eseguiPOST(){
    if (isset($_POST["nome"]))    {
        echo BLL\Personaggio::Crea($_POST["nome"]);
    }
}

function eseguiDELETE(){
    $val = BLL\Response::datiinput();
    
    if (isset($val["basename"]))    {
        echo BLL\Personaggio::Elimina($val["basename"]);
    }
}

include __DIR__.'/BLL/gestione_metodi.php';

?>
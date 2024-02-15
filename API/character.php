<?php
include __DIR__ . '/BLL/auth_and_cors_middleware.php';

function eseguiGET()
{
    if (isset($_GET["basename"]))
        echo (new BLL\Personaggio($_GET["basename"]))->getData();
}

function eseguiPOST()
{
    if (isset($_POST["name"])) {
        $platinum = isset($_POST["platinum"]) ? $_POST["platinum"] : 0;
        $gold = isset($_POST["gold"]) ? $_POST["gold"] : 0;
        $silver = isset($_POST["silver"]) ? $_POST["silver"] : 0;
        $copper = isset($_POST["copper"]) ? $_POST["copper"] : 0;
        echo BLL\Personaggio::Crea($_POST["name"], $platinum, $gold, $silver, $copper);
    }
}

function eseguiDELETE()
{
    $val = datiinput();
    if (isset($_GET["basename"])) {
        echo BLL\Personaggio::Elimina($_GET["basename"]);
    }
}

include __DIR__ . '/BLL/gestione_metodi.php';


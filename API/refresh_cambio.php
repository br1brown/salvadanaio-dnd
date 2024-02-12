<?php
include __DIR__.'/BLL/auth_and_cors_middleware.php';

function eseguiGET(){
    if (isset($_GET["basename"])){
        $p = (new BLL\Personaggio($_GET["basename"]));
        $p->refreshCambio();
        echo BLL\Response::retOK();
    }
}

include __DIR__.'/BLL/gestione_metodi.php';

?>
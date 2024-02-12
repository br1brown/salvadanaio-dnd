<?php
include __DIR__.'/BLL/auth_and_cors_middleware.php';

function eseguiPOST(){
    if (isset($_POST["basename"]))    {
         $p = new BLL\Personaggio($_POST["basename"]);
        echo $p->deleteCronologia($_POST["data"]);
    }
}

include __DIR__.'/BLL/gestione_metodi.php';

?>
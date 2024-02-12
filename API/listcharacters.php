<?php
include __DIR__.'/BLL/auth_and_cors_middleware.php';

function eseguiGET(){
echo Echo_getObj("personaggi");
}
include __DIR__.'/BLL/gestione_metodi.php';
?>
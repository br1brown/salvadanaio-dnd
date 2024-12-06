<?php
include dirname(__DIR__) . '/BLL/auth_and_cors_middleware.php';

function eseguiPOST()
{
    ManeggiaSoldi(BLL\TransactionType::CREDIT, $_POST);

}
include dirname(path: __DIR__) . '/BLL/gestione_metodi.php';
?>
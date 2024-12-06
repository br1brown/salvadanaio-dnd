<?php
include dirname(__DIR__) . '/BLL/auth_and_cors_middleware.php';

function eseguiPOST()
{
    ManeggiaSoldi(BLL\TransactionType::SPENT, $_POST);
}
include dirname(__DIR__) . '/BLL/gestione_metodi.php';
?>
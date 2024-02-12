<?php
include dirname(__DIR__).'/BLL/auth_and_cors_middleware.php';

function eseguiPOST(){
ManeggiaSoldi("settle_credit",$_POST);

}
include dirname(__DIR__).'/BLL/gestione_metodi.php';
?>
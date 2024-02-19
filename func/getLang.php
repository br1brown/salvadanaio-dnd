<?php
$k = 'lang';
require_once dirname(__DIR__) . '/FE_utils/Traduzione.php';

$l = isset($_GET[$k]) ? $_GET[$k] : '';
echo json_encode((new Traduzione($l, dirname(__DIR__) . "/FE_utils/lang"))->corrente);


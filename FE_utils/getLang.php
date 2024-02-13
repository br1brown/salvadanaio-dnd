<?php
$k = 'lang';
require_once __DIR__ . '/Traduzione.php';


$l = isset($_GET[$k]) ? $_GET[$k] : '';
echo json_encode((new Traduzione($l))->corrente);


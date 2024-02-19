<?php
require_once dirname(__DIR__) . '/FE_utils/ServerToServer.php';

$data = json_decode($_POST["payload"], true);

$risultati = ServerToServer::callURL(
    $data['url'],
    $data['type'],
    $data['data'],
    $data['dataType'],
    [
        "X-Api-Key: " . $data['XApiKey']
    ]
);

echo $risultati->Response;
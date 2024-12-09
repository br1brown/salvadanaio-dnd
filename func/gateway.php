<?php
require_once dirname(__DIR__) . '/FE_utils/ServerToServer.php';

$data = json_decode($_POST["payload"], true);
$headers = [
    "X-Api-Key: " . $data['XApiKey']
];
if (isset($data['BearerToken']) && $data['BearerToken'] != null) {
    $headers[] = "BearerToken: " . $data['BearerToken'];
}

$risultati = ServerToServer::callURL(
    $data['url'],
    $data['type'],
    $data['data'],
    $data['dataType'],
    $headers
);

echo $risultati->Response;
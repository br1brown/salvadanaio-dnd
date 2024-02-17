<?php

$data = json_decode($_POST["payload"], true);

// Estrai i parametri necessari inviati dal frontend
$endpoint = $data['endpoint'];
$apiData = $data['data'];
$type = $data['type'];
$dataType = $data['dataType']; // Nota: questo potrebbe non essere necessario per la richiesta CURL
$apiKey = $data['XApiKey'];
$apiEndPoint = $data['APIEndPoint'];

// Prepara l'URL per l'API esterna
$url = $apiEndPoint . '/' . $endpoint;

// Configura le opzioni cURL
$curlOptions = [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => $type,
    CURLOPT_HTTPHEADER => [
        'Content-Type: ' . $dataType,
        'X-Api-Key: ' . $apiKey,
    ],
];

if ($type != 'GET' && !empty($apiData)) {
    // Inserisci i dati nel corpo della richiesta per i metodi POST, PUT, etc.
    $curlOptions[CURLOPT_POSTFIELDS] = json_encode($apiData);
}

$curl = curl_init();
curl_setopt_array($curl, $curlOptions);

// Esegui la richiesta
$response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Gestisci errori di cURL
if (curl_errno($curl)) {
    // Restituisci un errore generico in caso di fallimento della richiesta cURL
    $response = json_encode([
        'status' => 'error',
        'message' => 'Error Gatway API',
    ]);
    $status = 500;
}

curl_close($curl);

// Imposta l'header di risposta e restituisci la risposta JSON
header('Content-Type: ' . $dataType);
http_response_code($status);

echo $response;

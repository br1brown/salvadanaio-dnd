<?php

// Includi i file necessari
require_once __DIR__.'/Repository.php';
require_once __DIR__.'/Response.php';
include __DIR__.'/funzioni_comuni.php';


$settingsfolder = "BLL/auth_settings/";
// Ottiene tutti gli header della richiesta HTTP
$headers = getallheaders();

// Legge il file contenente le API keys autorizzate
$paroleSegrete = file(BLL\Repository::findAPIPath().$settingsfolder.'APIKeys.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Controlla se l'header 'X-Api-Key' esiste e se corrisponde a una delle API keys autorizzate
if (!isset($headers['X-Api-Key']) || !in_array($headers['X-Api-Key'], $paroleSegrete)) {
    http_response_code(403); // Invia un codice di risposta HTTP 403 (Forbidden)
    exit; // Termina l'esecuzione dello script
}

// Gestione delle impostazioni CORS
$fileconfigCORS = BLL\Repository::findAPIPath().$settingsfolder."CORSconfig.json";
// Controlla se esiste il file di configurazione CORS
if (file_exists($fileconfigCORS)){
    // Decodifica il file JSON di configurazione CORS
    $config = json_decode(file_get_contents($fileconfigCORS), true);
    $applyCORS = $config['applyCORS'];

    // Applica le impostazioni CORS se richiesto
    if ($applyCORS) {
        $allowedOrigins = $config['allowedOrigins'] ?? [];
        // Ottiene l'origine della richiesta
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        // Imposta l'header 'Access-Control-Allow-Origin' in base all'origine
        if (empty($allowedOrigins)) {
            // Permette tutte le origini se non sono specificate origini consentite
            header("Access-Control-Allow-Origin: *");
        } elseif (in_array($origin, $allowedOrigins)) {
            // Permette solo le origini specificate nella lista di origini consentite
            header("Access-Control-Allow-Origin: $origin");
        }

        // Imposta gli altri header CORS per i metodi e gli header consentiti
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }
}
require_once __DIR__.'/Personaggio.php';


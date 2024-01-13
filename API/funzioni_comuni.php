<?php

$headers = getallheaders();

// Leggi il file delle parole segrete
$paroleSegrete = file('APIKeys.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Controlla se l'header 'X-Api-Key' esiste e corrisponde a una delle parole segrete
if (!isset($headers['X-Api-Key']) || !in_array($headers['X-Api-Key'], $paroleSegrete)) {
    http_response_code(403); // Forbidden
    exit;
}



// Gestione CORS
$fileconfigCORS = "CORSconfig.json";
if (file_exists($fileconfigCORS)){
    $config = json_decode(file_get_contents($fileconfigCORS), true);
    $applyCORS = $config['applyCORS'];
    $allowedOrigins = $config['allowedOrigins'] ?? [];
    if ($applyCORS) {
        if (empty($allowedOrigins)) {
            header("Access-Control-Allow-Origin: *");
        } elseif (in_array($origin, $allowedOrigins)) {
            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
            header("Access-Control-Allow-Origin: $origin");
        }
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }
}
require_once __DIR__.'/BLL/Repository.php';
require_once __DIR__.'/BLL/Response.php';


/**
 * Ottiene un oggetto e lo restituisce, eventualmente dopo aver applicato una callback.
 * 
 * @param string $nome Nome dell'oggetto da ottenere.
 * @param callable|null $callback Funzione di callback da applicare ai dati.
 * @return string Risposta JSON con i dati ottenuti o un messaggio di errore.
 */
function Echo_getObj($nome, $callback = null){
    $ciLavoro = is_callable($callback);
    try {
        $jsonData = BLL\Repository::getObj($nome, $ciLavoro);

        if ($ciLavoro) {
            $jsonData = json_encode($callback($jsonData));
        }

    } catch(Exception $e) {
        return BLL\Response::retError($e->getMessage());
    }
    return $jsonData;
}

?>

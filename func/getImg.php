<?php
require_once(dirname(__DIR__) . "/FE_utils/Asset.php");

// Prendi l'imgID dal parametro GET
$imgID = isset($_GET['imgID']) ? $_GET['imgID'] : '';

// Usa la classe Asset per ottenere il percorso dell'immagine
$path = Asset::getPath($imgID);

// Verifica se $path è un URL
if (filter_var($path, FILTER_VALIDATE_URL)) {
    // Reindirizza al browser per visualizzare l'immagine dall'URL
    header("Location: $path");
    exit;
} else {
    // Costruisce il percorso del file locale
    $file_path = dirname(__DIR__) . "/asset/" . $path;

    if ($path !== null && file_exists($file_path)) {
        // Determina il MIME type del file
        $content_type = Asset::getMimeType($file_path);

        // Imposta l'header corretto per il tipo di file
        header("Content-Type: $content_type");
        // Leggi e restituisce il file
        readfile($file_path);
    } else {
        // Restituisci un errore se il file non esiste o l'ID non è valido
        http_response_code(404);
    }
}

<?php
require_once __DIR__.'/BLL/Repository.php';
require_once __DIR__.'/BLL/Response.php';

use BLL\Response as response;
use BLL\Repository as repository;

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
        $jsonData = repository::getObj($nome, $ciLavoro);

        if ($ciLavoro) {
            $jsonData = json_encode($callback($jsonData));
        }

    } catch(Exception $e) {
        return response::retError($e->getMessage());
    }
    return $jsonData;
}

?>

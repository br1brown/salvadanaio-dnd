<?php
/**
 * Ottiene un oggetto e lo restituisce, eventualmente dopo aver applicato una callback.
 * 
 * @param string $nome Nome dell'oggetto da ottenere.
 * @param callable|null $callback Funzione di callback da applicare ai dati.
 * @return string Risposta JSON con i dati ottenuti o un messaggio di errore.
 */
function Echo_getObj($nome, $callback = null){
    // Controlla se la callback fornita è eseguibile
    $ciLavoro = is_callable($callback);

    try {
        // Richiede i dati all'oggetto Repository
        $jsonData = BLL\Repository::getObj($nome, $ciLavoro);

        // Se esiste una callback valida, la applica ai dati ottenuti
        if ($ciLavoro) {
            $l = isset($_GET["lang"]) ? filter_input(INPUT_GET, "lang", FILTER_SANITIZE_STRING) : BLL\Repository::getDefaultLang();
            $jsonData = json_encode($callback($jsonData, $l));
        }

    } catch(Exception $e) {
        // In caso di eccezione, restituisce un messaggio di errore
        return BLL\Response::retError($e->getMessage());
    }

    // Restituisce i dati in formato JSON
    return $jsonData;
}

function ManeggiaSoldi($tipo, $dati) {
    $canReceiveChange = isset($dati['canReceiveChange']) ? filter_var($dati['canReceiveChange'], FILTER_VALIDATE_BOOLEAN) : str_starts_with($tipo, 'settle');
    $description = isset($dati['description']) ? $dati['description'] : "";
    $itemdescription = isset($dati['itemdescription']) ? $dati['itemdescription'] : "";
    
    $pers = new BLL\Personaggio($dati['basename']);

    echo $pers->manageCharacterCoins($tipo,
        intval($dati['platinum']),
        intval($dati['gold']),
        intval($dati['silver']),
        intval($dati['copper']),
        $description,
        $canReceiveChange,
        $itemdescription
    );
}
?>

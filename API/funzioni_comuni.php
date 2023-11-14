<?php


function getConfig($frontEnd = false) {
    $infos = "config.json";
    if ($frontEnd == true)
        $infos = "API/".$infos ;

    return json_decode(file_get_contents($infos), true);
}


function getFileName($characterName,$frontEnd = false) {
    $folder = getConfig($frontEnd)["FolderCharacters"];
    if ($frontEnd == true){
        $folder = "API/".$folder ;
    }

    $characterName = trim($characterName);
    if (preg_match('/^\d/', $characterName))
        $characterName = "_".$characterName;
        return $folder.'/' . str_replace(' ', '_', strtoupper($characterName)) . '.json';
}

function getCharacterFromName($characterName) {
    $filename = getFileName($characterName);
    if (file_exists($filename)) {
        $json = file_get_contents($filename);
        return json_decode($json, true);
    } else {
        return [
            'name' => $characterName,
            'platinum' => 0,
            'gold' => 0,
            'silver' => 0,
            'copper' => 0,
            'history' => []
        ];
    }
}

function saveCharacter($character) {
    $filename = getFileName($character['name']);
    file_put_contents($filename, json_encode($character));
}

function updateCharacterHistory(&$character, $platinum, $gold, $silver, $copper, $description, $isReceiving) {
    $transaction = [
        'date' => date('Y-m-d H:i:s'),
        'platinum' => $platinum,
        'gold' => $gold,
        'silver' => $silver,
        'copper' => $copper,
        'description' => $description,
        'type' => $isReceiving ? 'received' : 'spent'
    ];
    array_push($character['history'], $transaction);
}

function receiveCoins($characterName, $platinum, $gold, $silver, $copper, $description) {
    $character = getCharacterFromName($characterName);
    $character['platinum'] += $platinum;
    $character['gold'] += $gold;
    $character['silver'] += $silver;
    $character['copper'] += $copper;
    updateCharacterHistory($character, $platinum, $gold, $silver, $copper, $description, true);
    saveCharacter($character);
    return retOK('Monete ricevute correttamente.');
}

function makePayment($characterName, $platinum, $gold, $silver, $copper, $description, $canReceiveChange) {
    
    $equivalenze = getConfig();
    
    $character = getCharacterFromName($characterName);
    $totalCopperNeeded = $platinum * $equivalenze['platinum'] + $gold * $equivalenze['gold'] + $silver * $equivalenze['silver'] + $copper;
    $characterTotalCopper = $character['platinum'] * $equivalenze['platinum'] + $character['gold'] * $equivalenze['gold'] + $character['silver'] * $equivalenze['silver'] + $character['copper'];

    if ($characterTotalCopper < $totalCopperNeeded) {
        return retError('Non hai abbastanza monete per effettuare il pagamento.');
    }

    // Se può ricevere il resto, sottraiamo direttamente il totale necessario.
    if ($canReceiveChange) {
        $characterTotalCopper -= $totalCopperNeeded;

        $character['platinum'] = floor($characterTotalCopper / $equivalenze['platinum']);
        $characterTotalCopper %= $equivalenze['platinum'];

        $character['gold'] = floor($characterTotalCopper / $equivalenze['gold']);
        $characterTotalCopper %= $equivalenze['gold'];

        $character['silver'] = floor($characterTotalCopper / $equivalenze['silver']);

        $character['copper'] = $characterTotalCopper % $equivalenze['silver'];

    } else {
        // Altrimenti, assicurati di avere l'esatta denominazione per il pagamento
        if ($character['platinum'] < $platinum || $character['gold'] < $gold || $character['silver'] < $silver || $character['copper'] < $copper) {
            return retError('Non hai le monete della denominazione corretta per effettuare il pagamento esatto.');
        }
        $character['platinum'] -= $platinum;
        $character['gold'] -= $gold;
        $character['silver'] -= $silver;
        $character['copper'] -= $copper;
    }

    updateCharacterHistory($character, -$platinum, -$gold, -$silver, -$copper, $description, false);
    saveCharacter($character);
    return retOK('Pagamento effettuato correttamente.');
}



function retError($stringa){
    return json_encode(['status' => 'error', 'message' => $stringa]);
}

function retOK($stringa){
    return json_encode(['status' => 'success', 'message' => $stringa]);
}
?>

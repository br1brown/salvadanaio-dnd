<?php
function findAPIPath($dir = __DIR__) {
    $path = $dir . '/API/';

    if (file_exists($path)) {
        return $path;
    } elseif ($dir === dirname($dir)) { // Se raggiungiamo la root senza trovare /API
        return null;
    } else {
        return findAPIPath(dirname($dir)); // Cerca nella directory padre
    }
}

function getConfig() {
    $api = findAPIPath();
    $js = json_decode(file_get_contents($api."config.json"), true);
    $js["FolderAPICharacters"] = $api.$js["FolderCharacters"];
    return $js;
}

function get_totalcopper($character, $equivalenze) {
    $totali = $character['platinum'] * $equivalenze['platinum'];
    $totali += $character['gold'] * $equivalenze['gold'];
    $totali += $character['silver']  * $equivalenze['silver'];
    $totali += $character['copper'];
    return ($totali);
}

function getFileName($characterName) {
    $folder = getConfig()["FolderAPICharacters"];

    $characterName = trim($characterName);
    if (preg_match('/^\d/', $characterName))
        $characterName = "_".$characterName;
        return $folder.'/' . str_replace(' ', '_', strtoupper($characterName)) . '.json';
}

function getCharacterFromName($characterName, $notCreate = true) {
    $filename = getFileName($characterName);
    if (file_exists($filename)) {
        $json = file_get_contents($filename);
        return json_decode($json, true);
    } else {
        if($notCreate || empty($characterName)){
            return null;
        }else
        return [
            'name' => $characterName,
            'platinum' => 0,
            'gold' => 0,
            'silver' => 0,
            'copper' => 0,
            'history' => [],
            'link' => [],
            'items' => []
        ];
    }
}

function saveCharacter($character) {
    $filename = getFileName($character['name']);
    file_put_contents($filename, json_encode($character));
}

function manageCharacterCoins($characterName, $transactionType, $platinum, $gold, $silver, $copper, $description, $canReceiveChange = false) {
    $equivalenze = getConfig();
    $character = getCharacterFromName($characterName);

    if (empty($character)) {
        return retError('Personaggio non trovato!');
    }
    $totalCopper = get_totalcopper($character, $equivalenze);
    $totalCopperNeeded = $platinum * $equivalenze['platinum'] + $gold * $equivalenze['gold'] + $silver * $equivalenze['silver'] + $copper;
    
    // Gestione delle monete
    if ($transactionType === 'receive') {
        $character['platinum'] += $platinum;
        $character['gold'] += $gold;
        $character['silver'] += $silver;
        $character['copper'] += $copper;
    } else if ($transactionType === 'pay') {
        if ($canReceiveChange) {
            if ($totalCopper < $totalCopperNeeded) {
                return retError('Non hai abbastanza monete per effettuare il pagamento.');
            }
            // Calcola il nuovo totale dopo il pagamento e aggiorna le monete
            $characterTotalCopper = $totalCopper - $totalCopperNeeded;
            $character['platinum'] = floor($characterTotalCopper / $equivalenze['platinum']);
            $characterTotalCopper %= $equivalenze['platinum'];
            $character['gold'] = floor($characterTotalCopper / $equivalenze['gold']);
            $characterTotalCopper %= $equivalenze['gold'];
            $character['silver'] = floor($characterTotalCopper / $equivalenze['silver']);
            $character['copper'] = $characterTotalCopper % $equivalenze['silver'];
        } else {
            // Verifica se il personaggio ha abbastanza monete per il pagamento esatto
            if ($character['platinum'] < $platinum || $character['gold'] < $gold || $character['silver'] < $silver || $character['copper'] < $copper) {
                return retError('Non hai le monete della denominazione corretta per effettuare il pagamento esatto.');
            }
            $character['platinum'] -= $platinum;
            $character['gold'] -= $gold;
            $character['silver'] -= $silver;
            $character['copper'] -= $copper;
        }
    } else {
        return retError('Tipo di transazione non supportato.');
    }

    $character['history'][] = [
        'date' => date('Y-m-d H:i:s'),
        'platinum' => $transactionType === 'receive' ? $platinum : -$platinum,
        'gold' => $transactionType === 'receive' ? $gold : -$gold,
        'silver' => $transactionType === 'receive' ? $silver : -$silver,
        'copper' => $transactionType === 'receive' ? $copper : -$copper,
        'description' => $description,
        'type' => $transactionType === 'receive' ? 'received' : 'spent'
    ];;

    saveCharacter($character);
    return retOK($transactionType === 'receive' ? 'Monete ricevute correttamente.' : 'Pagamento effettuato correttamente.');
}


function updaateGenericItem($tipoItem, $condizioneCallback, $modificaCallback) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $character = getCharacterFromName($_POST['name'], findAPIPath());
        
        if (empty($character)) {
            return retError('Personaggio non trovato!');
        }

        $modificato = false;
        foreach ($character[$tipoItem] as $key => &$item) {
            if ($condizioneCallback($item,$_POST)) {
                $modificaCallback($item,$_POST);
                $modificato = true;
                break;
            }
        }        
        if ($modificato){
            saveCharacter($character);
            return retOK('Modifiche avvenute con successo!');
        } else {
            return retError('Dati non trovati; Nessuna modifica');
        }
    } else {
        return retError('Metodo HTTP non supportato.');
    }
}

function deleteGenericItem( $tipoItem, $condizioneCallback) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $character = getCharacterFromName($_POST['name'], findAPIPath());
        if (empty($character)) {
            return retError('Personaggio non trovato!');
        }

        $trovato = false;
        foreach ($character[$tipoItem] as $key => $item) {
            if ($condizioneCallback($item, $_POST)) {
                unset($character[$tipoItem][$key]);
                $trovato = true;
                break;
            }
        }
        if ($trovato) {
            // Reindirizza l'array dopo aver rimosso l'elemento
            $character[$tipoItem] = array_values($character[$tipoItem]);
            saveCharacter($character);
            return retOK('Elemento eliminato con successo!');
        } else {
            return retError('Elemento non trovato; Nessuna eliminazione');
        }
    } else {
        return retError('Metodo HTTP non supportato.');
    }
}

function addGenericItem($tipoItem, $condizioneEsistenzaCallback, $creazioneCallback) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $character = getCharacterFromName($_POST['name'], findAPIPath());
        if (empty($character)) {
            return retError('Personaggio non trovato!');
        }

        // Controlla se l'elemento esiste già
        if (isset($character[$tipoItem]) && is_array($character[$tipoItem]))
            foreach ($character[$tipoItem] as $item) {
                if ($condizioneEsistenzaCallback($item, $_POST)) {
                    return retError('Elemento già esistente.');
                }
            }
        
        // Crea un nuovo elemento utilizzando la callback fornita
        $newItem = $creazioneCallback($_POST);
        $character[$tipoItem][] = $newItem;

        saveCharacter($character);
        return retOK('Elemento aggiunto con successo.');
    } else {
        return retError('Metodo HTTP non supportato.');
    }
}


function retError($stringa){
    return json_encode(['status' => 'error', 'message' => $stringa]);
}

function retOK($stringa){
    return json_encode(['status' => 'success', 'message' => $stringa]);
}
?>

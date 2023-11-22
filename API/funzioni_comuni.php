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

function defineConstants() {
    if (defined('FOLDER_CHARACTERS'))
        return;

    $data = json_decode(file_get_contents(findAPIPath()."config.json"), true);

    if ($data === null) {
        return;
    }

    foreach (["platinum","gold","silver"]as $key) {
        if (isset($data[$key])) {
            define("CAMBIO_".strtoupper($key), $data[$key]);
        }
    }

    $cartella = $data["FolderCharacters"];
    $lastChar = "/";

    if (substr($cartella, -1) !== $lastChar)
        $cartella .= $lastChar;

    define("FOLDER_CHARACTERS", $cartella);
    
}

defineConstants();

function getFolderCharacters() {
    return findAPIPath().FOLDER_CHARACTERS;
}

function getFileCharacters() {
    return findAPIPath().FOLDER_CHARACTERS."/visibili.json";
}
function getAllNameCharacters(){
    $listaPath = getFileCharacters();
    $listaContent = [];
    if (file_exists($listaPath))
        $listaContent = json_decode(file_get_contents($listaPath), true);
    return $listaContent;
}
function get_totalcopper($obj) {
    // Inizializza le variabili per il calcolo
    $platinum = isset($obj['platinum']) ? $obj['platinum'] : 0;
    $gold = isset($obj['gold']) ? $obj['gold'] : 0;
    $silver = isset($obj['silver']) ? $obj['silver'] : 0;
    $copper = isset($obj['copper']) ? $obj['copper'] : 0;

    // Calcola il totale
    $totali = $platinum * CAMBIO_PLATINUM;
    $totali += $gold * CAMBIO_GOLD;
    $totali += $silver * CAMBIO_SILVER;
    $totali += $copper;

    return $totali;
}

function getBaseName($characterName) {
    $characterName = trim($characterName);
    if (preg_match('/^\d/', $characterName))
        $characterName = "_".$characterName;
    return strtoupper($characterName);
}

function getFileName($characterName) {
    return getFileNamebase(getBaseName($characterName));
}
function getFileNamebase($baseName) {
    return getFolderCharacters().$baseName. '.json';
}

function getCharacterFromName($characterName, $notCreate = true) {
    $filename = getFileName($characterName);

    $characterData = getCharacterFromPath($filename);

    if (!isset($characterData) || $characterData == null) {
        if(!$notCreate)
            return [
                'name' => $characterName,
                'platinum' => 0,
                'gold' => 0,
                'silver' => 0,
                'copper' => 0,
                'history' => [],
                'link' => [],
                'items' => [],
                'imgPath' => 'API/pic/placeholder.png'
            ];
    }
    return $characterData;
}

function getCharacterFromPath($filename) {
    if (file_exists($filename)) {
        $json = file_get_contents($filename);

        $characterData = json_decode($json, true);

        $characterData['basename'] = basename($filename, '.' . pathinfo($filename)['extension']);
        $characterData['totalcopper'] = get_totalcopper($characterData);

        return $characterData;
    } else
        return null;
    }

function saveCharacter($character) {
    $filename = getFileName($character['name']);
    unset($character['totalcopper']);
    unset($character['basename']);

    file_put_contents($filename, json_encode($character));
}

function manageCharacterCoins($characterName, $transactionType, $platinum, $gold, $silver, $copper, $description, $canReceiveChange = false, $otherPartyName = null) {
    $character = getCharacterFromName($characterName);

    if (empty($character)) {
        return retError('Personaggio non trovato!');
    }

    $totalCopperManaging = $platinum * CAMBIO_PLATINUM + $gold * CAMBIO_GOLD + $silver * CAMBIO_SILVER + $copper;

    // Logica per gestire ricezione, pagamento, debito, credito e sanazione del debito/credito
    switch ($transactionType) {
        case 'received':
            $character['platinum'] += $platinum;
            $character['gold'] += $gold;
            $character['silver'] += $silver;
            $character['copper'] += $copper;
            break;
            
        case 'spent':
            if ($canReceiveChange) {
            $totalCopperToPay = $platinum * CAMBIO_PLATINUM + $gold * CAMBIO_GOLD + $silver * CAMBIO_SILVER + $copper;
            $totalCopper = get_totalcopper($character);
            if ($totalCopper < $totalCopperToPay) 
                return retError("Non hai le monete per effettuare il pagamento esatto");
            
            $character['platinum'] = 0;
            $character['gold'] = 0;
            $character['silver'] = 0;
            $character['copper'] = $totalCopper - $totalCopperToPay;
        
            refreshCambio($character);

            } else {
                if ($character['platinum'] < $platinum || $character['gold'] < $gold || $character['silver'] < $silver || $character['copper'] < $copper) {
                    return retError('Non hai le monete della denominazione corretta per effettuare il pagamento esatto');
                }
                $character['platinum'] -= $platinum;
                $character['gold'] -= $gold;
                $character['silver'] -= $silver;
                $character['copper'] -= $copper;
            }
            break;
            
        case 'debt':
        case 'credit':
            // Aggiungi il debito o il credito al personaggio
            $character[$transactionType][] = [
                'name' => $otherPartyName,
                'copper' => $totalCopperManaging,
                'description' => $description
            ];
            break;
            
        case 'settle_debt':
        case 'settle_credit':
            // Rimuovi il debito o il credito sanato
            $transactionKey = $transactionType === 'settle_debt' ? 'debt' : 'credit';
            $found = false;
            foreach ($character[$transactionKey] as $key => $transaction) {
                if ($transaction['name'] === $otherPartyName && $transaction['copper'] === $totalCopperManaging) {
                    unset($character[$transactionKey][$key]);
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return retError('Debito o credito specificato non trovato.');
            }
            $character[$transactionKey] = array_values($character[$transactionKey]); // Reindex array
            break;
            
        default:
            return retError('Tipo di transazione non supportato.');
    }

    $character['history'][] = [
        'date' => date('Y-m-d H:i:s'),
        'platinum' => $platinum,
        'gold' => $gold,
        'silver' => $silver,
        'copper' => $copper,
        'description' => $description,
        'type' => strtoupper($transactionType),
        'other_party' => $otherPartyName
    ];;

    saveCharacter($character);
    return retOK("Transazione ($transactionType) eseguita correttamente.");
}

function refreshCambio(&$obj){
    // Calcola il nuovo totale dopo il pagamento e aggiorna le monete
    $objTotalCopper = get_totalcopper($obj);

    $obj['platinum'] = floor($objTotalCopper / CAMBIO_PLATINUM);
    $objTotalCopper -= $obj['platinum'] * CAMBIO_PLATINUM;

    $obj['gold'] = floor($objTotalCopper / CAMBIO_GOLD);
    $objTotalCopper -= $obj['gold'] * CAMBIO_GOLD;

    $obj['silver'] = floor($objTotalCopper / CAMBIO_SILVER);

    $obj['copper'] = $objTotalCopper % CAMBIO_SILVER;

}


function updaateGenericItem($tipoItem, $condizioneCallback, $modificaCallback) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $character = getCharacterFromName($_POST['name']);
        
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
        $character = getCharacterFromName($_POST['name']);
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
        $character = getCharacterFromName($_POST['name']);
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

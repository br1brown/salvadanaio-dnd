<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    $date = $_POST['date'];
    $character = getCharacterFromName($characterName);
	
    // Controlla se nell'array 'history' c'è un oggetto con 'date' uguale a $date e rimuovilo
    foreach ($character['history'] as $key => $value) {
        if ($value['date'] == $date) {
            unset($character['history'][$key]);
        }
    }
    
    // Reindirizza l'array dopo aver rimosso l'elemento
    $character['history'] = array_values($character['history']);

    saveCharacter($character);
    echo retOK('Modifiche effettuate correttamente');

} else {
    echo retError('Metodo HTTP non supportato.');
}

?>

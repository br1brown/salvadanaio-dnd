<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    $url = $_POST['url'];
    $character = getCharacterFromName($characterName);
	
    // Controlla se nell'array 'history' c'è un oggetto con 'date' uguale a $date e rimuovilo
    foreach ($character['links'] as $key => $value) {
        if ($value['url'] == $url) {
            unset($character['links'][$key]);
        }
    }
    
    // Reindirizza l'array dopo aver rimosso l'elemento
    $character['links'] = array_values($character['links']);

    saveCharacter($character);
    echo retOK('Modifiche effettuate correttamente');

} else {
    echo retError('Metodo HTTP non supportato.');
}

?>

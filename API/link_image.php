<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $character = getCharacterFromName($_POST['name']);
    if ($character != null){
        $character['imgPath'] = $_POST['link'];
        saveCharacter($character);
        
        echo retOK("Immagine caricata con successo!");
    } else {
        echo retError("Errore immagine caricata.");
        }
        
} else {
    echo retError('Metodo HTTP non supportato.');
}

?>

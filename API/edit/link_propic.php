<?php
include dirname(__DIR__).'/funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $character = getCharacterFromName($_POST['name']);
    if ($character != null){
        if (empty($_POST['link']))
            $character['imgPath'] = 'API/pic/placeholder.png';
        else
            $character['imgPath'] = $_POST['link'];

        saveCharacter($character);
        
        echo retOK("Immagine modificata con successo!");
    } else {
        echo retError("Errore immagine caricata.");
        }
        
} else {
    echo retError('Metodo HTTP non supportato.');
}

?>

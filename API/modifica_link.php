<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    $oldUrl = $_POST['oldUrl']; // URL vecchio per identificare quale link modificare
    $url = $_POST['url'];
    $linkText = $_POST['linkText'];
    $note = $_POST['note'];
    $character = getCharacterFromName($characterName);
    
    $modificato = false;
    foreach ($character['links'] as $key => &$link) {
        if ($link['url'] == $oldUrl) {
            $link['url'] = $url;
            $link['text'] = $linkText;
            $link['note'] = $note;
            $modificato = true;
            break;
        }
    }
    if ($modificato){
        saveCharacter($character);
        echo retOK('Link modificato correttamente.');
    }
    else {
        echo retError('Link non trovato; Nessun link modificato');
    }

} else {
    echo retError('Metodo HTTP non supportato.');
}

?>
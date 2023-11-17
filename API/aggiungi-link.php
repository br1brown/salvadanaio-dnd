<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    $url = $_POST['url'];
    $linkText = $_POST['linkText'];
    $note = $_POST['note'];
    $character = getCharacterFromName($characterName);
    
    foreach ($character['links'] as $key => $value) {
            if ($value['url'] == $url) {
                echo retError('Il link a <code>"'.$url.'"</code> già presente!');
                exit;
            }
        }


    $newLink = [
        'url' => $url,
        'text' => $linkText,
        'note' => $note,
    ];
    $character['links'][] = $newLink;

    saveCharacter($character);
    echo retOK('Link aggiunto correttamente.');

} else {
    echo retError('Metodo HTTP non supportato.');
}


?>
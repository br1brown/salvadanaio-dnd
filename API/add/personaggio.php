<?php
include dirname(__DIR__).'/funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    $filename = getFileName($characterName);

    // Verifica se il personaggio esiste già
    if (file_exists($filename)) {
        echo json_encode(['status' => 'error', 'message' => 'Un personaggio con questo nome esiste già.']);
    } else {
        $newCharacter = getCharacterFromName($characterName, false); // Questa funzione creerà un personaggio se non esiste
        saveCharacter($newCharacter);
        echo retOK('Personaggio creato con successo.');
    }
} else {
    echo retError('Metodo HTTP non supportato.');
}
?>

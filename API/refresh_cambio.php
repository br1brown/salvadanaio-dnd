<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $character = getCharacterFromName($_POST['name']);
   
    refreshCambio($character);

    saveCharacter($character);
    echo retOK("Monete messe in ordine!");

} else {
    echo retError('Metodo HTTP non supportato.');
}

?>

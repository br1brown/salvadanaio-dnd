<?php
include 'funzioni_comuni.php';
if (isset($_GET["name"])) {
    $personaggio = getCharacterFromName($_GET["name"],true);
    if (empty($personaggio)){
        echo retError("Personaggio non trovato");
    }else{
        echo json_encode($personaggio);
    }
}
else{
    echo retError("Stringa personaggio non valida");
}
?>

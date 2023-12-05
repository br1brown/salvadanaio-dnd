<?php
include 'funzioni_comuni.php';

$filePath = 'data\irl.json';

if (file_exists($filePath) && is_readable($filePath)) {
    $fileContent = file_get_contents($filePath);

    $jsonData = json_decode($fileContent, true);

    if ($jsonData === null) {
        echo retError("Errore nella decodifica dell'anagrafica");
    } else {
        echo json_encode($jsonData);
    }
} else {
    echo retError("Impossibile leggere le anagrafiche");
}


?>
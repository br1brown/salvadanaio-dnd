<?php
include dirname(__DIR__).'/funzioni_comuni.php';
if (isset($_GET["basename"])) {
    $filepersonaggio = getFileNamebase($_GET["basename"]);
    if (!file_exists($filepersonaggio)) {
        echo retError("Personaggio non trovato");
    }else{
        echo json_encode(getCharacterFromPath($filepersonaggio));
    }
}
else{
    echo retError("Stringa personaggio non valida");
}
?>

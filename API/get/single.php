<?php
include dirname(__DIR__).'/funzioni_comuni.php';
if (isset($_GET["basename"])) {
    $filepersonaggio = getFileNamebase($_GET["basename"]);
    if (!file_exists($filepersonaggio)) {
        echo retError("Personaggio non trovato");
    }else{

        $c = getCharacterFromPath($filepersonaggio);

            if (isset($c["suspended"]))
                foreach ($c["suspended"] as $tipo =>$obj) {
                    foreach ($c["suspended"][$tipo] as $key => $tran) {
                        refreshCambio($c["suspended"][$tipo][$key]);
                    }
                }


        if (!in_array($c['name'], getAllNameCharacters()))
            echo retError("Personaggio non accessibile");
        else
            echo json_encode($c);

    }
}
else{
    echo retError("Stringa personaggio non valida");
}
?>

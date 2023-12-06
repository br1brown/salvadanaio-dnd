<?php

function findAPIPath($dir = __DIR__) {
    $path = $dir . '/API/';

    if (file_exists($path)) {
        return $path;
    } elseif ($dir === dirname($dir)) { // Se raggiungiamo la root senza trovare /API
        return null;
    } else {
        return findAPIPath(dirname($dir)); // Cerca nella directory padre
    }
}




function getEncodeObj($nome, $callback = null){
    $filePath = findAPIPath().'data/'.$nome.'.json';

    if (file_exists($filePath) && is_readable($filePath)) {
        $fileContent = file_get_contents($filePath);

        $jsonData = json_decode($fileContent, true);
            
        if ($jsonData === null) {
            return retError("Errore nella decodifica");
        } else {
            if (is_callable($callback)) {
                $jsonData = $callback($jsonData);
            }
            return json_encode($jsonData);
        }
    } else {
        return retError("Impossibile leggere le informazioni ".$nome);
    }

}

function retError($stringa){
    return json_encode(['status' => 'error', 'message' => $stringa]);
}

function retOK($stringa){
    return json_encode(['status' => 'success', 'message' => $stringa]);
}

?>

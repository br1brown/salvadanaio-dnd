<?php
include dirname(__DIR__).'/funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $character = getCharacterFromName($_POST['name']);
    
    // Assumiamo che '$_FILES' contenga l'immagine caricata e '$_POST' contenga il nome dell'immagine.
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        
        // Valida l'immagine e il nome del file qui.
        if ($image['error'] === UPLOAD_ERR_OK) {

            $est = GetExtension();
            if ($est == false)
                echo retError("Errore durante il caricamento del file.");
            else{
                $dir = findAPIPath()."pic";
                
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                try{

                $uploadPath = $dir."/" . getBaseName($character['name']).'.'.$est;
                $uploadRelativePath = "API/pic/" . getBaseName($character['name']).'.'.$est;

                $ilFile = file_get_contents($image['tmp_name']);
                
                file_put_contents(str_replace('\\',"/",$uploadPath), $ilFile);

                $character['imgPath'] = $uploadRelativePath;
                saveCharacter($character);
                
                echo retOK("Immagine caricata con successo!");
                } 
                catch(Exception $e) {
                    return retError($e->getMessage());
                }

            }
        } else {
            echo retError("Errore nel file caricato.");
        }
    } else {
        echo retError("Dati dell'immagine non ricevuti correttamente.");
    }
} else {
    echo retError('Metodo HTTP non supportato.');
}

/**
 * Funzione fittizia per sanificare il nome del file.
 */
function sanitizeFileName($fileName) {
    // Implementa la logica per sanificare il nome del file.
    return preg_replace('/[^a-z0-9_\-\.]/i', '_', $fileName);
}

function GetExtension() {

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
    finfo_close($finfo);

    $mimeMap = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        // altri tipi MIME e le loro corrispondenti estensioni
    ];

    return isset($mimeMap[$mimeType]) ? $mimeMap[$mimeType] : false;
}


?>

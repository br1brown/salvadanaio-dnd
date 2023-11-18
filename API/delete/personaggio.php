<?php
include dirname(__DIR__).'/funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterlabel = $_POST['name'];
    if (empty($characterlabel)){
        echo retError('Nessun personaggio');
    exit;
    }

    $infos = getConfig();
    $directory = $infos["FolderAPICharacters"].'/';

    $nome = "";
    foreach (new DirectoryIterator($directory) as $file) {
        if ($file->isDot() || $file->getExtension() !== 'json') continue;
        
        if ($characterlabel == $file->getBasename('.json')){
            $json = file_get_contents($directory . $file->getFilename());
            $characterName = json_decode($json, true)['name'];
        }

    }
    
    if (empty($characterName)){
            echo retError('Nessun personaggio');
        exit;
        }



    $percorsoFileOriginale = getFileName($characterName);
    if (!file_exists($percorsoFileOriginale)) {
        echo retError('Nessun dato');
            exit;
    }

    $folderEliminati = getConfig()["FolderAPICharacters"].'//eliminati/';

    $nomeFileSenzaEstensione = pathinfo($percorsoFileOriginale, PATHINFO_FILENAME);
    $estensioneFile = pathinfo($percorsoFileOriginale, PATHINFO_EXTENSION);

    $percorsoFileDestinazione = $folderEliminati . $nomeFileSenzaEstensione . '_' . time() . '.' . $estensioneFile;

    if (!file_exists($folderEliminati)) {
        mkdir($folderEliminati, 0777, true);
    }

    if (rename($percorsoFileOriginale, $percorsoFileDestinazione)) {
        echo retOK($characterName.' cancellato!');
    } else {
        echo retError('Qualcosa è andato male!');
    }

} else {
    echo retError('Metodo HTTP non supportato.');
}
?>

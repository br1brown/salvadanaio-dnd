<?php
include dirname(__DIR__).'/funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    if (empty($characterName)){
        echo retError('Nessun personaggio');
    exit;
    }
    $percorsoFileOriginale = getFileName($characterName);;

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

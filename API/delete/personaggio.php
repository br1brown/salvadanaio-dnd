<?php
include dirname(__DIR__).'/funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baseName = $_POST['baseName'];

    $percorsoFileOriginale = getFileNamebase($baseName);
    if (!file_exists($percorsoFileOriginale)) {
        echo retError('Nessun personaggio trovato!');
            exit;
    }

    $folderEliminati = getFolderCharacters().'//eliminati/';

    $nomeFileSenzaEstensione = pathinfo($percorsoFileOriginale, PATHINFO_FILENAME);
    $estensioneFile = pathinfo($percorsoFileOriginale, PATHINFO_EXTENSION);

    $percorsoFileDestinazione = $folderEliminati . $nomeFileSenzaEstensione . '_' . time() . '.' . $estensioneFile;

    if (!file_exists($folderEliminati)) {
        mkdir($folderEliminati, 0777, true);
    }

    if (rename($percorsoFileOriginale, $percorsoFileDestinazione)) {
        echo retOK('Personaggio cancellato!');
    } else {
        echo retError('Qualcosa è andato male!');
    }

} else {
    echo retError('Metodo HTTP non supportato.');
}
?>

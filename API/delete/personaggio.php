<?php
include dirname(__DIR__).'/funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baseName = $_POST['baseName'];

    $percorsoFileOriginale = getFileNamebase($baseName);

    $c = getCharacterFromPath($percorsoFileOriginale);

    if ($c == null) {
        echo retError('Nessun personaggio trovato!');
            exit;
    }
    file_put_contents(getFileCharacters(), json_encode(array_diff(getAllNameCharacters(), [$c['name']])));

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

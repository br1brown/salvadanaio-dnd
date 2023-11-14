<?php
include 'funzioni_comuni.php';

$characters = [];
$directory = getConfig()["FolderAPICharacters"].'/';
if (file_exists($directory)) {

    foreach (new DirectoryIterator($directory) as $file) {
        if ($file->isDot() || $file->getExtension() !== 'json') continue;

        $jsonContent = file_get_contents($directory . $file->getFilename());
        $characterData = json_decode($jsonContent, true);
        $characterData['filename'] = $file->getBasename('.json');

        $characters[] = $characterData;
    }
    
}
echo json_encode($characters);
?>

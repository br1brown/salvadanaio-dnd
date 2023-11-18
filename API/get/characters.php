<?php
include dirname(__DIR__).'/funzioni_comuni.php';

$characters = [];
$infos = getConfig();
$directory = $infos["FolderAPICharacters"].'/';
if (file_exists($directory)) {

    foreach (new DirectoryIterator($directory) as $file) {
        if ($file->isDot() || $file->getExtension() !== 'json') continue;

        $jsonContent = file_get_contents($directory . $file->getFilename());
        $characterData = json_decode($jsonContent, true);
        $characterData['filename'] = $file->getBasename('.json');
        $characterData['totalcopper'] = get_totalcopper($characterData,$infos);

        $characters[] = $characterData;
    }
    
}
echo json_encode($characters);
?>

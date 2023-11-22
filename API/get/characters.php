<?php
include dirname(__DIR__).'/funzioni_comuni.php';

$characters = [];
$directory = getFolderCharacters().'/';
if (file_exists($directory)) {
    $nomi = getAllNameCharacters();
    foreach ($nomi as $name) 
        $characters[] = getCharacterFromName($name);
    
}
echo json_encode($characters);
?>

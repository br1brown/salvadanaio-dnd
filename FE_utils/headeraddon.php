<?php
require_once __DIR__ . '/funzioni.php';
dynamicMenu($service, $settings['itemsMenu']);

if (isset($title) && $title == "salvadanaio" && isset($_GET['basename'])) {
    $nome = ucwords(strtolower(str_replace("_", " ", $_GET['basename'])));
    $meta->title = $service->traduci($title) . " " . $nome;

} else
    $meta->title = isset($title) ? $service->traduci($title) : $settings['AppName'];

$meta->description = isset($singledescription) ? $service->traduci($singledescription) : $settings['description'];


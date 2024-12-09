<?php
require_once __DIR__ . '/funzioni.php';
dynamicMenu($service, $settings['itemsMenu']);
if (isset($title) && $title == "salvadanaio" && isset($_GET['basename'])) {
    $nome = ucwords(strtolower(str_replace("_", " ", $_GET['basename'])));
    $meta->title = $service->traduci($title) . " " . $nome;

} else
    $meta->title = isset($title) ? $service->traduci($title) : "";

$meta->description = isset($singledescription) ? $service->traduci($singledescription) : $settings['description'];

session_start();

// Controlla se l'utente è loggato
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

<?php
header('Content-Type: application/manifest+json');
require_once __DIR__.'/FE_utils/Service.php';
$service = new Service();
$settings = $service->getSettings();

$manifest = [
    'name' => $settings['AppName'] ?? 'Template',
    'short_name' => $settings['AppName'] ?? 'Template',
    'description' => $settings['description'] ?? 'Descrizione Default',
    'lang' => $settings['lang'] ?? '',
    'start_url' => $service->baseUrl,
    'display' => 'browser',
    'background_color' => $settings['colorBase'] ?? '#000000',
    'theme_color' => $settings['colorTema'] ?? '#FFFFFF'
];
echo json_encode($manifest,true);
?>

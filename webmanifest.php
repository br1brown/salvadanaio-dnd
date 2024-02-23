<?php
header('Content-Type: application/manifest+json');
require_once __DIR__ . '/FE_utils/Service.php';
$service = new Service();
$settings = $service->getSettings();

$manifest = [
    'name' => $settings['AppName'] ?? 'Template',
    'short_name' => $settings['AppName'] ?? 'Template',
    'description' => $settings['description'] ?? 'Descrizione Default',
    'lang' => $service->currentLang() ?? '',
    'start_url' => $service->baseUrl,
    'display' => 'browser',
    'background_color' => $settings["colori"]['colorBase'],
    'theme_color' => $settings["colori"]['colorTema']
];
echo json_encode($manifest, true);

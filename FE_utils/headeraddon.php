<?php
require_once __DIR__ . '/funzioni.php';
dynamicMenu($service, $settings['itemsMenu']);

$meta['title'] = isset($title) ? $service->traduci($title) : $settings['AppName'];
$meta['description'] = isset($singledescription) ? $service->traduci($singledescription) : $settings['description'];


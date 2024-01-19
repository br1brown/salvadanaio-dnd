<?php
require_once __DIR__.'/funzioni.php';
dynamicMenu($service, $settings['itemsMenu']);

$meta['title'] = isset($title) ? $title : $settings['AppName'];
$meta['description'] = isset($singledescription) ? $singledescription : $settings['description'];

?>
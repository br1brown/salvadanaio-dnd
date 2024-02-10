<?php
require_once __DIR__.'/Service.php';

$service = new Service();

$settings = $service->getSettings();
$meta = $service->getMeta();

require_once __DIR__.'/headeraddon.php';
foreach ($settings as $key => $value) {
	${$key} = $value;
}

try {
$irl = $service->callApiEndpoint("/anagrafica");
} catch (Exception $e) {}
?>
<!doctype html>
<html lang="<?= $service->lang ?>">
<head>
<?php
$clsTxt = $isDarkTextPreferred? "text-dark":"text-light";
?>
	<title><?= $meta['title']; ?></title>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!--shrink-to-fit=no-->
	
	<meta name="description" content="<?= $meta['description'] ?>">

	<?php if(isset($meta['string_All_keywords'])) : ?>
	<meta name="keywords" content="<?= $meta['string_All_keywords'] ?>">
	<?php endif; ?>
	
	<?php if(isset($meta['author'])) : ?>
		<meta name="author" content="<?= $meta['author']?>">
	<?php endif; ?>
	
	<meta name="robots" content="index, follow">
	<link rel="manifest" href="webmanifest.php">
	
	<meta name="HandheldFriendly" content="<?= $meta['MobileFriendly']? "true":"false" ?>">
	<meta name="MobileOptimized" content="<?= $meta['mobileOptimizationWidth'] ?>">
	
	<!-- Indica la frequenza con cui la pagina dovrebbe essere aggiornata -->
	<meta http-equiv="refresh" content="<?= $meta['refreshIntervalInSeconds'] ?>">
	
	<!-- Colore tematico per il browser sui dispositivi Android -->
	<meta name="theme-color" content="<?= $colorTema ?>" />
	<!-- Cambia lo stile della barra di stato su iOS -->
	<meta name="apple-mobile-web-app-status-bar-style" content="<?= $colorTema ?>">
	
	<!-- Definizione del nome del sito web quando salvato come app web sui dispositivi mobili -->
	<meta name="application-name" content="<?= $AppName ?>" />
	<!-- Definisce il titolo dell'app web per iOS -->
	<meta name="apple-mobile-web-app-title" content="<?= $AppName?>">
	<!-- Permette al sito web di funzionare a schermo intero su Safari iOS, simile a un'applicazione nativa -->
	<meta name="apple-mobile-web-app-capable" content="<?= $meta['iOSFullScreenWebApp']?"yes":"no" ?>">
	
	<link rel="icon" type="image/png" href="<?=$service->baseURL($favIcon)?>">
	
	
	<script>
		const APIEndPoint = '<?= $service->urlAPI ?>';
		const APIKey = '<?= $service->APIkey ?>';
		const lang = '<?= $service->lang ?>';
		let _pathtraduzione = '<?=$service->_pathjsonLang($service->lang)?>';
	</script>
	
	<?php
	foreach ($meta['ext_link'] as $comment => $links):
    echo "\n	<!-- $comment -->\n";
    foreach ($links as $link) {
        if ($link['type'] == 'css') {
            echo '	<link rel="stylesheet" href="' . $link['url'] . '">' . "\n";
        } else if ($link['type'] == 'js') {
            echo '	<script src="' . $link['url'] . '"></script>' . "\n";
        }
    }
	endforeach;


	foreach ($meta['localjs'] as $value):
	 echo "\n	<script src=".$service->baseURL("script/".$value)."></script>";
	endforeach;


	foreach ($meta['localcss'] as $value):
	echo "\n	<link rel=\"stylesheet\" href=".$service->baseURL("style/".$value).">";
	endforeach;

	?>	
	<style>
	:root {
		<?php foreach ($colori as $chiave => $colore): ?>
			--<?= $chiave; ?>: <?= $colore; ?>;
		<?php endforeach; ?>
	}
	</style>

</head>
<body>	
<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button">
	<i class="fas fa-chevron-up"></i>
</a>
<?php 
if ($havesmoke) : ?>
<canvas id="smoke-effect-canvas" 
        data-color="<?= $smoke['color'] ?>" 
        data-opacity="<?= $smoke['opacity'] ?>" 
        data-maximumVelocity="<?= $smoke['maximumVelocity'] ?>" 
        data-particleRadius="<?= $smoke['particleRadius'] ?>" 
        data-density="<?= $smoke['density'] ?>"
        style="width:100%; height:100%; position: fixed; top: 0; left: 0; z-index: -100;">
</canvas>
<?php endif; 
//se $forceMenu è valorizzata a true lo metti, se non c'è lo metti
if (isset($itemsMenu) && count($itemsMenu) > 0 && ((isset($forceMenu))?($forceMenu == true):true)): ?>
<nav class="navbar navbar-expand-sm <?=$isDarkTextPreferred? "navbar-light":"navbar-dark" ?> fillColoreSfondo">
  <a class="navbar-brand" href="<?= $service->createRoute("index")?>"><?= $AppName ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
	<?php
	foreach($itemsMenu as $key=>$value): ?>
		<li class="nav-item<?=pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME) == $value['route']? " active":"" ?>">
			<a class="nav-link" href="<?= $service->createRoute($value['route'])?>"><?= $service->traduci(ucfirst($value['nome']));?></span></a>
		</li>
	<?php endforeach; ?>
    </ul>
	<?php
	$lingueDisponibili = $service->getLingueDisponibili();

	// Controlla se ci sono più di una lingua disponibile
	if (count($lingueDisponibili) > 1): ?>
		<div class="ml-auto">
		<div class="dropdown fillColoreSfondo">
			<button class="nav-link w-100 dropdown-toggle fillColoreSfondo <?= $clsTxt ?>" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<?= $service->traduci("selezionaLingua"); ?>
			</button>
			<div class="dropdown-menu dropdown-menu-right fillColoreSfondo" aria-labelledby="dropdownMenuButton">
			<?php foreach ($lingueDisponibili as $lingua): ?>
				<a href="javascript:setLanguage('<?= $lingua ?>')" class="dropdown-item<?=$service->lang == $lingua?" active ": " "?>fillColoreSfondo <?= $clsTxt ?>"> <?= strtoupper($lingua) ?> </a>
			<?php endforeach; ?>
			</div>
		</div>
		</div>
	<?php endif; ?>

</nav>
<?php endif; ?>

<div class="container-fluid">

<!-- qui comincia l'html diverso per tutti -->

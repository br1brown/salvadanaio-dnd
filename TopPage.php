<?php
function callApiEndpoint($urlAPI, $path) {
    $url = rtrim($urlAPI, '/') . '/' . ltrim($path, '/');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception("Errore EndPoint: " . curl_error($ch));
    }

    curl_close($ch);
    $oggetto = json_decode($response, true);

    if (isset($oggetto['status']) && $oggetto['status'] === 'error') {
        throw new Exception("Errore API: " . $oggetto['message']);
    }

    return $oggetto;
}
?>

<!doctype html>
<html lang="it">
<head>
	<?php
	$settings = json_decode(file_get_contents('websettings.json'), true);
	foreach ($settings as $key => $value) {
			${$key} = $value;
		}

	if (strpos($APIEndPoint, "http://") === 0 || strpos($APIEndPoint, "https://") === 0) {
		$urlAPI = $APIEndPoint;
	} else {
		$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
		$urlAPI =  $baseUrl.dirname($_SERVER['PHP_SELF'])."/".$APIEndPoint;
	}
	try{
		$irl = callApiEndpoint($urlAPI,"anagrafica");
	} catch (Exception $e){
	}
?>
	<title><?php echo $title ?></title>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!--shrink-to-fit=no-->
	
	<meta name="description" content="<?= $description ?>">
	<meta name="keywords" content="<?= $keywords ?>">
	
	<meta name="author" content="Br1Brown">
	
	<meta name="robots" content="index, follow">
	<link rel="manifest" href="site.webmanifest">
	
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	
	<!-- Indica la frequenza con cui la pagina dovrebbe essere aggiornata -->
	<meta http-equiv="refresh" content="900">
	
	<!-- Colore tematico per il browser sui dispositivi Android -->
	<meta name="theme-color" content="black" />
	<!-- Cambia lo stile della barra di stato su iOS -->
	<meta name="apple-mobile-web-app-status-bar-style" content="white">
	
	<!-- Definizione del nome del sito web quando salvato come app web sui dispositivi mobili -->
	<meta name="application-name" content="<?= $AppName ?>" />
	<!-- Definisce il titolo dell'app web per iOS -->
	<meta name="apple-mobile-web-app-title" content="<?= $AppName?>">
	<!-- Permette al sito web di funzionare a schermo intero su Safari iOS, simile a un'applicazione nativa -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	
	
	<link rel="icon" type="image/png" href="<?= $favIcon?>">

	<!-- ROBE PER IL MENU + SOCIAL anche quella prima per delle altre icone -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<!-- bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

	<!-- GLI ALERT -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

	<!-- Optional: include a polyfill for ES6 Promises for IE11 -->
	<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>

	<!-- ROBA NOSTRA -->
	<link rel="stylesheet" href="style/base.css">
	<link rel="stylesheet" href="style/manage_img.css">
	<link rel="stylesheet" href="style/social.css">
	<script src="script/base.js"></script>
	<!-- SFONDO CON LE NUVOLE -->
	<script src="script/jquery_bloodforge_smoke_effect.js"></script>
</head>
<script>
	const APIEndPoint = '<?= $urlAPI ?>';
	</script>
<body>	
<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button">
	<i class="fas fa-chevron-up"></i>
</a>
<?php if (isset($smoke) && $smoke["enable"]) : ?>
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
if (isset($itemsMenu) && ((isset($forceMenu))?($forceMenu == true):true)): ?>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <a class="navbar-brand" href="index"><?= $AppName ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
	<?php
	foreach($itemsMenu as $key=>$value): ?>
		<li class="nav-item<?=pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME) == $value['route']? " active":"" ?>">
			<a class="nav-link" href="<?=$value['route']?>"><?= ucfirst($value['nome']);?></span></a>
		</li>
	<?php endforeach; ?>

    </ul>
  </div>
</nav>
<?php endif; ?>





<!-- qui comincia l'html diverso per tutti -->

<?php
include 'API/funzioni_comuni.php';
$error = "";
// Verifica se il parametro "name" è presente nella richiesta GET
if (isset($_GET["name"])) {
    // Pulisce il valore di "name" per evitare iniezioni SQL e problemi di sicurezza
    $name = filter_input(INPUT_GET, "name", FILTER_SANITIZE_STRING);
    // Verifica che "name" non sia vuoto dopo la pulizia
    if (!empty($name)) {
        $filename = getFileName($name,true);
        if (file_exists($filename)) {
            $character = json_decode(file_get_contents($filename), true);
        } else {
            $error = "Il nome personaggio non esiste";
        }
    } else {
        $error = "Il nome decharacterl personaggio non può essere vuoto";
    }
} else {
    $error = "Parametro 'name' mancante nella richiesta";
}
?>


<!doctype html>
<html lang="it">

<head>

	<!-- Definizione della codifica dei caratteri per la pagina -->
	<meta charset="UTF-8">

	<!-- Impostazioni per il responsive design e il controllo della visualizzazione su dispositivi mobili -->
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!--shrink-to-fit=no-->

	<!-- Informazioni sull'autore del sito -->
	<meta name="author" content="Br1Brown">

	<title>Salvadanaio di <?php if ($error == "") echo $character['name']; ?></title>

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

<style>
@media screen and (max-width: 600px) {
	.table {
	display: block;
	width: 100%;
	overflow-x: auto;
	-webkit-overflow-scrolling: touch;
	.table-responsive {
  margin: 0 auto;
	}
	.table th,
	.table td {
	white-space: nowrap;
	}
}
}
</style>
<body>
	<canvas id="smoke-effect-canvas"
		style="width:100%; height:100%; position: fixed;top: 0; left: 0; z-index: -100;"></canvas>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-2"></div>
			<div class="col-12 col-md-8 text-center tutto">
				<div class="row">
				<h2 class="col-12 bg-danger"><?php if ($error != "") echo $error; ?></h2>
				<h1 class="col-12"><?php  if ($error == "") echo $character['name']; ?></h1>

					<div class="col">
						<div class="portafoglio shadow rounded p-1 m-2">
						<div class="row text-center">
							<span style=scale(2) class="col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i> Platino:  <?php if ($error == "") echo $character['platinum']; ?></span>
						</div>
						<div class="row small text-center">
							<span class="col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i> Oro:  <?php if ($error == "") echo $character['gold']; ?></span>
							<span class="col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i> Argento:  <?php if ($error == "") echo $character['silver']; ?></span>
						</div>
						<div class="row small text-center">
							<span class="col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i> Rame:  <?php if ($error == "") echo $character['copper']; ?></span>
						</div>
						</div>
						<?php if ($error == ""){ ?>
						<div class="row text-center mt-3 mb-5">
							<input type="button" value=Spendi class="btn btn-danger col-10 offset-1 offset-md-1 col-md-5" onclick="manageMoney('<?php if ($error == "") echo $character['name']; ?>', false)">
							<input type="button" value=Ricevi class="btn btn-success col-10 offset-1 offset-md-0 col-md-5" onclick="manageMoney('<?php if ($error == "") echo $character['name']; ?>', true)">
						</div>
						<?php } ?>
					</div>
				</div>
				<?php if (!empty($character['history'])){ ?>
				<div class="row">
				<div class="col-12" style="display: block; font-size: x-small;">
				<table class="table" >
					<thead class="thead-dark">
						<tr>
							<th width="45%">Denaro</th>
							<th width="50%">Descrizione</th>
							<th width="5%"></th>
						</tr>
					</thead>
					<tbody>
					<?php
					function confrontaData($a, $b) {
						return strtotime($b['date']) - strtotime($a['date']);
					}
					if ($error == "") {
						usort($character['history'], 'confrontaData');
						foreach ($character['history'] as $riga) {
							// Controllo se almeno uno dei valori non è zero
							if($riga['platinum'] != 0 || $riga['gold'] != 0 || $riga['silver'] != 0 || $riga['copper'] != 0) {
								$classeRiga = $riga['type'] == 'received' ? 'table-success' : 'table-danger';
								echo "<tr class='{$classeRiga}'>";
								echo "<td>";
								if($riga['platinum'] != 0) echo "<i class='fas fa-award platinum-color bordo-ico' title=platino></i> {$riga['platinum']} ";
								if($riga['gold'] != 0) echo "<i class='fas fa-medal gold-color bordo-ico' title=oro></i> {$riga['gold']} ";
								if($riga['silver'] != 0) echo "<i class='fas fa-trophy silver-color bordo-ico' title=argent></i> {$riga['silver']} ";
								if($riga['copper'] != 0) echo "<i class='fas fa-coins copper-color bordo-ico' title=rame></i> {$riga['copper']}";
								echo "</td>";
								echo "<td>{$riga['description']}</td>";
								echo "<td><i style=\"cursor: pointer;\" class=\"fa fa-solid fa-trash\" onclick=\"deleteHistory('{$character['name']}','{$riga['date']}','{$riga['description']}')\"></i></td>";
								echo "</tr>";
							}
						}
					}
					?>
					</tbody>
				</table>
				</div>
				</div>
				<?php } ?>
<br>

				</div>
		<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i
				class="fas fa-chevron-up"></i></a>
	</div>
</body>
<script>
	$(document).ready(function () {
		miaFunzione();
		window.addEventListener('pageshow', function(event) {
		if (event.persisted) {
			miaFunzione();
		}
		});

	});
function miaFunzione() {
 <?php if ($error != "")  echo "SweetAlert.fire({"
							."  title: 'Errore',"
							."  text: \"".$error."\","
							."  icon: 'error'"
							."}).then(() => {"
							."  window.location.href = 'index.html';"
							."});"; ?>
}

</script>

</html>
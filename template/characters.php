<?php

$json = file_get_contents('php://input');

$characters = json_decode($json, true);

if (json_last_error() === JSON_ERROR_NONE) {

	foreach ($characters as $personaggio) {
		foreach ($personaggio as $key => $value) {
			${$key} = $value;
		}
		
?>

	<div class="col-12 col-md-6">
	<div class="card m-2 portafoglio shadow rounded">
		<div class="card-body">
			<h5 class="card-title"><a href="detail?basename=<?php echo $basename; ?>"><?php echo $name; ?><a></h5>
			<p class="card-text">
			<div class="row text-center">
				<span class="col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i> Platino: <?php echo $platinum; ?></span>
			</div>
			<div class="row small text-center">
				<span class="col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i> Oro: <?php echo $gold; ?></span>
				<span class="col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i> Argento: <?php echo $silver; ?></span>
			</div>
			<div class="row small text-center">
				<span class="col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i> Rame: <?php echo $copper; ?></span>
			</div>
			</p>

			<a href="#<?php echo $basename; ?>" class="btn link-secondary" data-toggle="collapse">Gestione</a>
			<div id="<?php echo $basename; ?>" class="collapse text-center mt-3">
					<input type="button" data-type="success" value=Ricevi onclick="manageMoney('<?php echo $name; ?>', true)" class="btn btn-success">
					<input type="button" data-type="success" value=Spendi onclick="manageMoney('<?php echo $name; ?>', false)" class="btn btn-danger">
			</div>

		</div>
	</div>
	</div>

<?php
}
} else {
    // Imposta un codice di stato HTTP per errore (ad esempio, 400 Bad Request)
    http_response_code(400);
    // Invia il messaggio di errore
    echo "Errore nella decodifica JSON: " . json_last_error_msg();
    exit; // Termina lo script
}

?>
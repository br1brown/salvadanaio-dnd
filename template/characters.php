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
			<h5 class="card-title">
				
			<!-- <?php if (!empty($imgPath)) : ?>
			<img class="col-3 polaroid" src="<?php echo $imgPath; ?>">
			<?php endif; ?>	 -->
				<a class=col href="detail?basename=<?php echo $basename; ?>"><?php echo $name; ?><a></h5>
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

			<div class="text-center mt-3">
			<button onclick="manageMoney('<?php echo htmlspecialchars($name); ?>', true)" class="btn btn-success btn-sm">
				<i class="fas fa-coins"></i>
			</button>
			<button onclick="manageMoney('<?php echo htmlspecialchars($name); ?>', false)" class="btn btn-danger btn-sm">
				<i class="fas fa-shopping-cart"></i>
			</button>
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
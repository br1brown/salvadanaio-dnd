<?php
$title = "Salvadanaio";
// $singledescription = "Pagina principale";
include('FE_utils/TopPage.php');

$personaggi = $service->callApiEndpoint("characters");
?>

<div class="row">
	<?php
	foreach ($personaggi as $personaggio) {

		?>
		<div class="col-12 col-md-6 text-center">
			<div class="card m-2 portafoglio rounded text-dark">
				<div class="card-body">
					<p class="card-title">
						<a href="<?= $service->createRoute("detail?basename=" . $personaggio["basename"]) ?>"
							class="col btn btn-sm btn-primary">
							<?= $personaggio["name"]; ?><a>
					</p>
					<p class="card-text">
					<div class="row text-center">
						<span class="col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i>
							<?= $service->traduci("platino") ?>:
							<?= $personaggio["cash"]["platinum"]; ?>
						</span>
					</div>
					<div class="row small text-center">
						<span class="col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i>
							<?= $service->traduci("oro") ?>:
							<?= $personaggio["cash"]["gold"]; ?>
						</span>
						<span class="col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i>
							<?= $service->traduci("argento") ?>:
							<?= $personaggio["cash"]["silver"]; ?>
						</span>
					</div>
					<div class="row small text-center">
						<span class="col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i>
							<?= $service->traduci("rame") ?>:
							<?= $personaggio["cash"]["copper"]; ?>
						</span>
					</div>
					</p>
					<div class="text-center mt-3">
						<button onclick="manageMoney('<?= htmlspecialchars($personaggio["basename"]); ?>', true)"
							class="btn btn-outline-success btn-sm">
							<i class="fas fa-coins"></i>
						</button>
						<button onclick="manageMoney('<?= htmlspecialchars($personaggio["basename"]); ?>', false)"
							class="btn btn-outline-danger btn-sm">
							<i class="fas fa-shopping-cart"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	include('FE_utils/BottomPage.php'); ?>
</div>

<script>
	inizializzazioneApp.then(() => {

	});
</script>

</html>
<?php
$title = "Salvadanaio";
// $singledescription = "Pagina principale";
include('FE_utils/TopPage.php');

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'cash;DESC';

$info = $service->callApiEndpoint("characters", "GET", ["sort" => $sort]);
$personaggi = $info["characters"];
$l = count($personaggi);
?>
<div class="container-fluid">

	<?php
	if ($l > 0) { ?>
		<div class="row">
			<div class="col text-center">
				<?= "<span class='badge badge-secondary'>" . renderSoldi($info["allcash"]) . "</span> "; ?>
				<span onclick="alla_romana()" class="btn btn-danger btn-sm">
					<i class="fas fa-shopping-cart"></i>
				</span>
				<span onclick="dividamoilbottino()" class="btn btn-success btn-sm">
					<i class="fas fa-coins"></i>
				</span>
			</div>
		</div>

		<div class=row>
			<div class="col offset-md-4 col-md-4 form-group">
				<label for="sort">
					<?= $service->traduci("ordinaPer") ?>
				</label>
				<select name="sort" id="sort" class="form-control" onchange="riordina()">
					<?php
					foreach (["cash;ASC" => "cashASC", "cash;DESC" => "cashDESC", "name;ASC" => "nameASC", "name;DESC" => "nameDESC",] as $k => $s) {
						?>
						<option value="<?= $k ?>" <?= $sort == $k ? 'selected' : ''; ?>>
							<?= $service->traduci($s) ?>
						</option>
						<?php
					}
					?>
				</select>
			</div>
		</div>

		<?php
	} ?>
	<div class="col-12 col-md-10 offset-md-1">
		<div class="row">
			<?php
			if ($l < 2 && $l > 0)
				$clsCol = 12 / $l;
			else
				$clsCol = 6;
			foreach ($personaggi as $personaggio) {
				?>
				<div class="col-12 col-md-<?= $clsCol ?> text-center">
					<div class="card p-2 m-3 portafoglio rounded text-dark">
						<div class="card-body">
							<h2 class="card-title">
								<a href="<?= $service->createRoute("detail?basename=" . $personaggio["basename"]) ?>"
									class="btn btn-light">
									<?= $personaggio["name"]; ?><a>
							</h2>
							<p class="card-text">
							<div class="row text-center">
								<span class="col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i>
									<?= $service->traduci("platino") ?>:
									<?= $personaggio["platinum"]; ?>
								</span>
							</div>
							<div class="row small text-center">
								<span class="col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i>
									<?= $service->traduci("oro") ?>:
									<?= $personaggio["gold"]; ?>
								</span>
								<span class="col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i>
									<?= $service->traduci("argento") ?>:
									<?= $personaggio["silver"]; ?>
								</span>
							</div>
							<div class="row small text-center">
								<span class="col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i>
									<?= $service->traduci("rame") ?>:
									<?= $personaggio["copper"]; ?>
								</span>
							</div>
							</p>
							<div class="text-center mt-3">
								<button
									onclick="manageTransaction('<?= htmlspecialchars($personaggio["basename"]); ?>', true)"
									class="btn btn-success btn-sm">
									<i class="fas fa-coins"></i>
									<?= $service->traduci("ricevi") ?>
								</button>
								<button
									onclick="manageTransaction('<?= htmlspecialchars($personaggio["basename"]); ?>', false)"
									class="btn btn-danger btn-sm">
									<i class="fas fa-shopping-cart"></i>
									<?= $service->traduci("spendi") ?>
								</button>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	if ($l == 0) { ?>
		<h1 class="col-12 text-center">
			<?= $service->traduci("nessunPersonaggio") ?>
		</h1>
		<?php
	} ?>
</div>
</div>
<?php
include('FE_utils/BottomPage.php');
?>
<script>
	function riordina() {
		var sort = $("#sort").val();
		var parser = new URL(window.location);
		parser.searchParams.set("sort", sort);
		window.location = parser.href;
	}
	inizializzazioneApp.then(() => {

	});
	function alla_romana() {
		showAlert(
			"Spendete in totale",
			"Spendete",
			{ checkResto: "0" },
			false,
			"manage/alla_romana"
		);
	}


	function dividamoilbottino() {
		showAlert(
			"Guadagno totale",
			"Dividiamo tra tutti",
			{ checkResto: "0" },
			false,
			"manage/dividamoilbottino"
		);
	}
</script>

</html>
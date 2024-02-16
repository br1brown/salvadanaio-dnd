<?php
$title = "Salvadanaio";
// $singledescription = "Pagina principale";
include('FE_utils/TopPage.php');
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'cash;DESC';

$info = $service->callApiEndpoint("characters", "GET", ["sort" => $sort]);
$personaggi = $info["characters"];
$l = count($personaggi);
if ($l > 0) { ?>
	<div class="row">
		<strong class="col text-center">
			<?= "<span class='badge badge-secondary'>" . renderSoldi($info["allcash"]) . "</span>"; ?>
		</strong>
	</div>
	<div class=row>
		<div class="col offset-md-4 col-md-4 form-group">
			<label for="sort"></label>
			<select name="sort" id="sort" multiple class="form-control" onchange="riordina()">
				<?php
				foreach (["cash;ASC" => "Soldi (crescente)", "cash;DESC" => "Soldi (decrescente)", "name;ASC" => "Nome (A-Z)", "name;DESC" => "Nome (Z-A)",] as $k => $s) {
					?>
					<option value="<?= $k ?>" <?= $sort == $k ? 'selected' : ''; ?>>
						<?= $s ?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
	</div>

	<?php
} ?>
<div class="row">
	<?php
	if ($l < 2 && $l > 0)
		$clsCol = 12 / $l;
	else
		$clsCol = 6;
	foreach ($personaggi as $personaggio) {
		?>
		<div class="col-12 col-md-<?= $clsCol ?> text-center">
			<div class="card p-2 mb-3 portafoglio rounded text-dark">
				<div class="card-body">
					<p class="card-title">
						<a href="<?= $service->createRoute("detail?basename=" . $personaggio["basename"]) ?>"
							class="col btn btn-primary">
							<?= $personaggio["name"]; ?><a>
					</p>
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
						<button onclick="manageMoney('<?= htmlspecialchars($personaggio["basename"]); ?>', true)"
							class="btn btn-success btn-sm">
							<i class="fas fa-coins"></i>
							<?= $service->traduci("Ricevi") ?>
						</button>
						<button onclick="manageMoney('<?= htmlspecialchars($personaggio["basename"]); ?>', false)"
							class="btn btn-danger btn-sm">
							<i class="fas fa-shopping-cart"></i>
							<?= $service->traduci("Spendi") ?>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	if ($l == 0) { ?>
		<h1 class="col-12 text-center">
			<?= $service->traduci("nessunPersonaggio") ?>
		</h1>
		<?php
	} ?>
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
</script>

</html>
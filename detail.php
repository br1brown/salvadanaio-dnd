<?php
$title = "salvadanaio";
// $singledescription = "Pagina principale";
include('FE_utils/TopPage.php');

$valido = false;
if (isset($_GET['basename'])) {


	try {
		$personaggio = $service->callApiEndpoint("character", "GET", ["basename" => $_GET['basename']]);

		$name = $personaggio['name'] ?? 'Nome non disponibile';
		$basename = $personaggio['basename'] ?? 'Nome non disponibile';

		$platinum = $personaggio['platinum'] ?? 0;
		$gold = $personaggio['gold'] ?? 0;
		$silver = $personaggio['silver'] ?? 0;
		$copper = $personaggio['copper'] ?? 0;

		$suspended = $personaggio['suspended'] ?? [];

		$history = $personaggio['history'] ?? [];
		$rowsToShow = 3;

		$valido = true;

	} catch (Exception $e) {

	}

}
if (!$valido) {
	?>
	<div class="row">
		<div class="col col-md-6 offset-md-3 bg-danger">
			<?= $service->traduci("personaggioNonTrovato") ?>
		</div>
	</div>
	<?php
} else {
	?>
	<div class="row">
		<div class="col">
			<h1><strong>
					<?= $name; ?>
				</strong></h1>

			<div class="col text-center">
				<button class="btn btn-sm m-1 btn-outline-success col-10 col-md-5"
					onclick="manageMoney('<?= htmlspecialchars($basename); ?>', true)">
					<i class="fas fa-coins"></i>
					<?= $service->traduci("Ricevi") ?>
				</button>
				<button class="btn btn-sm m-1 btn-outline-danger col-10 col-md-5"
					onclick="manageMoney('<?= htmlspecialchars($basename); ?>', false)">
					<i class="fas fa-shopping-cart"></i>
					<?= $service->traduci("Spendi") ?>
				</button>
			</div>


			<div class="portafoglio rounded p-1 m-2 mb-0">
				<div class="row text-center">
					<span class="grandill-m col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i>
						<?= $service->traduci("Platino") ?>:
						<?= $platinum; ?>
					</span>
				</div>
				<div class="row small text-center">
					<span class="grandill col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i>
						<?= $service->traduci("Oro") ?>:
						<?= $gold; ?>
					</span>
					<span class="grandill col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i>
						<?= $service->traduci("Argento") ?>:
						<?= $silver; ?>
					</span>
				</div>
				<div class="row small text-center">
					<span class="grandill-s col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i>
						<?= $service->traduci("Rame") ?>:
						<?= $copper; ?>
					</span>
				</div>

				<div class="row">
					<div class="col">
						<div class="col small">
							<div class="row small">
								<?php
								$buttons = [
									[
										'testo' => $service->traduci('Ricalcola monete'),
										'onclick' => "refreshcambio('" . htmlspecialchars($basename) . "')",
										'simbolo' => 'fas fa-sync-alt',
										'class' => ''
									]
								];
								?>

								<?php foreach ($buttons as $key => $button): ?>
									<a href="javascript:<?= $button['onclick']; ?>"
										class="col-9 col-md-auto  <?= $button['class']; ?> link-secondary">
										<i class="<?= $button['simbolo']; ?>"></i>
										<?= $button['testo']; ?>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>


	<div class="row">
		<div class="col text-center">
			<button class="btn btn-sm m-1 btn-sm btn-primary col-10 col-md-5"
				onclick="creditTransaction('<?= htmlspecialchars($basename); ?>',false)">
				<i class="fas fa-money-bill-wave"></i>
				<?= $service->traduci("Fai Debito") ?>
			</button>
			<button class="btn btn-sm m-1 btn-sm btn-primary col-10 col-md-5"
				onclick="creditTransaction('<?= htmlspecialchars($basename); ?>',true)">
				<i class="fas fa-hand-holding-usd"></i>
				<?= $service->traduci("Fai Credito") ?>
			</button>
		</div>
	</div>

	<div class="row">

		<?php
		if (!empty($suspended))

			foreach ($suspended as $tipo => $obj) {
				$cls = "table-";
				$isCredit = "null";
				?>
				<div class="col-12 col-md-6">
					<?php if ($tipo === "debt" && !empty($obj)):
						$isCredit = "false";
						$cls .= "warning"; ?>
						<i>
							<?= $service->traduci("debiti") ?>
						</i>
					<?php endif; ?>
					<?php if ($tipo === "credit" && !empty($obj)):
						$isCredit = "true";
						$cls .= "info"; ?>
						<i>
							<?= $service->traduci("crediti") ?>
						</i>
					<?php endif; ?>
					<ul class="list-unstyled">
						<?php foreach ($obj as $key => $tran) {
							?>

							<li class="text-small m-1 <?= $cls; ?>">
								<button
									onclick="sanaContratto('<?= $basename; ?>', <?= $isCredit; ?>,<?= $tran['platinum'] ?>, <?= $tran['gold'] ?>,<?= $tran['silver'] ?>,<?= $tran['copper'] ?>,'<?= htmlspecialchars($tran['description']) ?>')"
									class="btn btn-primary btn-sm m-1">
									<i class="fas fa-ban"></i>
								</button>
								<strong>
									<?= "<span class='badge badge-secondary'>" . renderSoldi($tran) . "</span>"; ?>
								</strong>
								<?= $tran['description']; ?>
							</li>
						<?php } ?>
				</div>
			<?php } ?>

	</div>

	<?php
	if (!empty($history)) { ?>

		<div class="row">
			<a href="#toManage" class="btn col-12 col-md-4 offset-md-4 btnmanage" data-toggle="collapse"><i
					class="fas fa-chevron-up fa-chevron-down"></i>
				<?= $service->traduci("cronologia") ?>
			</a>
			<div class="col-12 col-md-8 offset-md-2 collapse" id="toManage" style="font-size: x-small;">
				<div style="overflow-x: auto;" class="mx-auto">
					<table class="table mb-0">
						<thead class="thead-dark">
							<tr>
								<th class="col-1">
									<?= $service->traduci("tipo") ?>
								</th>
								<th class="col-5 text-center">
									<?= $service->traduci("denaro") ?>
								</th>
								<th class="col-6">
									<?= $service->traduci("info") ?>
								</th>
								<th class="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							function confrontaData($a, $b)
							{
								return strtotime($b['date']) - strtotime($a['date']);
							}
							usort($history, 'confrontaData');

							foreach ($history as $index => $riga) { // Utilizza $index come chiave
								$hiddenClass = ($index >= $rowsToShow) ? 'hidden' : ''; // Le righe oltre la quinta avranno la classe 'hidden'
								$classeRiga = "table-";
								switch ($riga['type']) {
									case 'SETTLE_CREDIT':
									case 'RECEIVED':
										$classeRiga .= "success";
										break;

									case 'SETTLE_DEBT':
									case 'SPENT':
										$classeRiga .= "danger";
										break;

									case 'DEBT':
										$classeRiga .= "warning";
										break;

									case 'CREDIT':
										$classeRiga .= "info";
										break;
									default:
										$classeRiga = "";
										break;
								}

								echo "<tr class='{$classeRiga} {$hiddenClass}'>";
								echo "<td class='text-muted align-middle'>{$riga['type']}</td>";
								echo "<td class=\"text-center\">" . renderSoldi($riga) . "</td>";
								echo "<td>{$riga['description']}</td>";
								echo "<td class='align-middle'><i style=\"cursor: pointer;\" class=\"fa fa-solid fa-trash\" onclick=\"deleteSingleHistory('{$basename}','{$riga['date']}','{$riga['description']}')\"></i></td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
					<?php if (count($history) > $rowsToShow): ?>
						<div class="col-12 text-center">
							<button id="loadMore" class="btn btn-outline-dark"><i class="fa fa-solid fa-arrow-down"></i>
								<?= $service->traduci("Carica più") ?>
							</button>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>

	<?php }
}
include('FE_utils/BottomPage.php');
?>

<script>
	inizializzazioneApp.then(() => {
		const rowsToShow = <?= $rowsToShow; ?>;
		let startIndex = rowsToShow; // Inizia dal sesto elemento poiché i primi 5 sono già visibili

		// Nasconde tutte le righe che hanno la classe 'hidden'
		$('tr.hidden').hide();

		$('#loadMore').click(function () {
			// Mostra le prossime 5 righe
			$('tr.hidden').slice(0, rowsToShow).removeClass('hidden').fadeIn();
			startIndex += rowsToShow;

			// Se non ci sono più righe da mostrare, nascondi il pulsante
			if ($('tr.hidden').length === 0) {
				$('#loadMore').hide();
			}
		});

		$(".btnmanage").click(function () {
			$(this).children('.fas').toggleClass('fa-chevron-down', 150);
		});
	});
</script>

</html>
<?php
$title = "salvadanaio";
// $singledescription = "Pagina principale";
include('FE_utils/TopPage.php');


$valido = false;
if (isset($_GET['basename'])) {

	try {
		$personaggio = $service->callApiEndpoint("character", "GET", ["basename" => $_GET['basename']]);
		if (isset($personaggio['status']))
			throw new Exception($personaggio['message']);
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
		$valido = false;
	}

}
?>
<div class="container-fluid">

	<?php
	if (!$valido) {
		?>
		<div class="row">
			<h1 class="col col-md-6 offset-md-3 bg-danger">
				<?= $service->traduci("personaggioNonTrovato") ?>
			</h1>
		</div>
		<?php
	} else {
		?>
		<div class="mx-3">
			<div class="row">
				<div class="col-12 col-md-auto">
					<h1>
						<strong>
							<?= $name; ?>
						</strong>
					</h1>
				</div>
				<div class="col-12 col-md">
					<div class="row">
						<button class="btn btn-lg p-3 m-1 btn-success col"
							onclick="manageMoney('<?= htmlspecialchars($basename); ?>', true)">
							<i class="fas fa-coins"></i>
							<?= $service->traduci("ricevi") ?>
						</button>
						<button class="btn btn-lg p-3 m-1 btn-danger col"
							onclick="manageMoney('<?= htmlspecialchars($basename); ?>', false)">
							<i class="fas fa-shopping-cart"></i>
							<?= $service->traduci("spendi") ?>
						</button>
					</div>
				</div>
			</div>
			<div class="row">

				<div class="col-12">
					<div class="portafoglio rounded p-1 m-2">
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
					<div class="row">
						<div class="col-12 col-md-6">
							<div>
								<i>
									<?= $service->traduci("debiti") ?>
								</i>
								<button class="btn m-1 btn-lg btn-primary col"
									onclick="creditTransaction('<?= htmlspecialchars($basename); ?>',false)">
									<i class="fas fa-hand-holding-usd"></i>
									<?= $service->traduci("faiDebito") ?>
								</button>

								<ul class="list-unstyled">
									<?php if (isset($suspended["debt"]))
										foreach ($suspended["debt"] as $key => $tran) {
											?>
											<li class="text-small m-1 table-warning">
												<button
													onclick="sanaContratto('<?= $basename; ?>',false,<?= $tran['platinum'] ?>, <?= $tran['gold'] ?>,<?= $tran['silver'] ?>,<?= $tran['copper'] ?>,'<?= htmlspecialchars($tran['description']) ?>')"
													class="btn btn-primary btn-sm m-1">
													<i class="fas fa-ban"></i>
												</button>
												<strong>
													<?= "<span class='badge badge-secondary'>" . renderSoldi($tran) . "</span>"; ?>
												</strong>
												<?= $tran['description']; ?>
											</li>
										<?php } ?>
								</ul>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div>
								<i>
									<?= $service->traduci("crediti") ?>
								</i>
								<button class="btn m-1 btn-lg btn-primary col"
									onclick="creditTransaction('<?= htmlspecialchars($basename); ?>',true)">
									<i class="fas fa-money-bill-wave"></i>
									<?= $service->traduci("faiCredito") ?>
								</button>
								<ul class="list-unstyled">
									<?php if (isset($suspended["credit"]))
										foreach ($suspended["credit"] as $key => $tran) {
											?>
											<li class="text-small m-1 table-info">
												<button
													onclick="sanaContratto('<?= $basename; ?>',true,<?= $tran['platinum'] ?>, <?= $tran['gold'] ?>,<?= $tran['silver'] ?>,<?= $tran['copper'] ?>,'<?= htmlspecialchars($tran['description']) ?>')"
													class="btn btn-primary btn-sm m-1">
													<i class="fas fa-ban"></i>
												</button>
												<strong>
													<?= "<span class='badge badge-secondary'>" . renderSoldi($tran) . "</span>"; ?>
												</strong>
												<?= $tran['description']; ?>
											</li>
										<?php } ?>
								</ul>
							</div>
						</div>
					</div>

					<div>
						<h2 class="col">
							<?= $service->traduci("inventario") ?> <button id="addBtn" class="btn btn-primary">
								<?= $service->traduci("aggiungi") ?>
							</button>
						</h2>
						<div id="inventoryItems" class="row p-3">
						</div>
					</div>
				</div>

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
			</div>

		<?php }
	}
	?>
</div>
<?php
include('FE_utils/BottomPage.php');
?>

<script>
	let inventory = [];
	inizializzazioneApp.then(() => {

		updateUI();

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

		$('#addBtn').click(function () {
			Swal.fire({
				title: traduci("aggiungi"),
				html: `<input type="text" id="oggetto" class="swal2-input" placeholder="Nome">
				   <input type="text" id="description" class="swal2-input" placeholder="Descrizione">`,
				confirmButtonText: traduci("aggiungi") + "!",
				focusConfirm: false,
				preConfirm: () => {
					const name = Swal.getPopup().querySelector('#oggetto').value;
					const description = Swal.getPopup().querySelector('#description').value;
					if (!name || !description) {
						Swal.showValidationMessage(traduci(`datiMancanti`));
					}
					return { itemName: name, quantity: 1, description: description }
				}
			}).then((result) => {

				let i = inventory.push(result.value);
				updateInventory(inventory.length - 1);

			});
		});
	});


	function creditTransaction(basename, isCredit) {
		manageTransaction(basename, isCredit ? 'credito' : 'debito');
	}

	function refreshcambio(basename) {
		apiCall("refresh_cambio", { basename }, function () {
			location.reload();
		}, "GET", false);
	}


	function updateUI() {

		apiCall("inventory",
			{
				basename: "<?= $basename ?>",
			},
			function (res) {
				inventory = res;

				var md = 4;
				var sm = 6;
				if (res.length < 3)
					md = 12 / res.length;
				if (res.length < 2)
					sm = 12 / res.length;

				$('#inventoryItems').empty();
				inventory.forEach((item, index) => {
					$('#inventoryItems').append(
						`<div class="tutto col-12 col-sm-${sm} col-md-${md}">
							<div class="row">
								<div class="col">
								<h4><strong>${item.itemName}</strong></h4>
								${item.description}
								</div>
								<div class="col-auto d-flex align-items-center"">
									<button onclick="decrementQuantity(${index})" class="btn btn-secondary">-</button>
									<span class="badge badge-primary p-3 grandill-s">${item.quantity}</span>
									<button onclick="incrementQuantity(${index})" class="btn btn-secondary">+</button>
								</div>
							</div>
						</div>`
					);
				})
			});
	}

	function incrementQuantity(index) {
		inventory[index].quantity += 1;
		updateInventory(index);
	}

	function decrementQuantity(index) {
		if (inventory[index].quantity > 1) {
			inventory[index].quantity -= 1;
			updateInventory(index);
		} else {
			Swal.fire({
				title: traduci('seiSicuro'),
				text: traduci("vuoiEliminareQuesto"),
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: traduci("elimina"),
			}).then((result) => {
				if (result.isConfirmed) {
					inventory[index].quantity = 0
					updateInventory(index);
				}
			});
		}
	}
	function updateInventory(index) {

		apiCall("inventory",
			{
				basename: "<?= $basename ?>",
				itemname: inventory[index].itemName,
				quantity: inventory[index].quantity,
				description: inventory[index].description
			},
			function () {

				updateUI();
			},
			"POST", false
		)
	}

	function sanaContratto(basename, isCredit, platinum, gold, silver, copper, itemdescription) {
		var word = (isCredit ? 'credito' : 'debito');
		SweetAlert.fire({
			title: traduci('seiSicuro'),
			html: traduci("Vuoi esaurire il " + word) + "<br>" + platinum + "p " + gold + "g " + silver + "s " + copper + "c?<br><small>" + traduci("modificheSuSoldi") + "</small>",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: traduci('procedi') + '!',
			cancelButtonText: traduci('annulla')
		}).then((result) => {
			if (result.isConfirmed) {
				apiCall("manage/sana_" + (isCredit ? 'credito' : 'debito'), {
					basename,
					itemdescription,
					platinum,
					gold,
					silver,
					copper,
					description: word + " sanato"
				}, function () {
					location.reload();
				}, "POST");
			}
		});
	}

	function deleteSingleHistory(basename, datastoriacancellare, descrizione) {
		SweetAlert.fire({
			title: traduci('seiSicuro') + '?',
			html: traduci("vuoiEliminareQuesto") + " '" + descrizione + "'?<br><small>" + traduci("nonInficiaSulleSommeSomme") + "</small>",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: traduci('elimina') + '!',
			cancelButtonText: traduci('annulla')
		}).then((result) => {
			if (result.isConfirmed) {
				apiCall("delhistory", {
					basename,
					data: datastoriacancellare,
				}, function () {
					location.reload();
				}, "POST");
			}
		});
	}
</script>

</html>
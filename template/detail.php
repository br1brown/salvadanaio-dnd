<?php
$personaggio = [];
if (isset($_POST['personaggio'])) {
	$personaggio = $_POST['personaggio'];
    // foreach ($_POST['personaggio'] as $key => $value) {
    // ${$key} = $value;
	// }
}

?>

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
	<div class="row">
		<h1 class="col-12"><?php echo $personaggio['name']; ?></h1>

		<div class="col">
			<div class="portafoglio shadow rounded p-1 m-2">
			<div class="row text-center">
				<span style=scale(2) class="col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i> Platino:  <?php echo $personaggio['platinum']; ?></span>
			</div>
			<div class="row small text-center">
				<span class="col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i> Oro:  <?php echo $personaggio['gold']; ?></span>
				<span class="col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i> Argento:  <?php echo $personaggio['silver']; ?></span>
			</div>
			<div class="row small text-center">
				<span class="col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i> Rame:  <?php echo $personaggio['copper']; ?></span>
			</div>
			</div>
		</div>
	</div>
		
	<div class="row text-center mt-3 mb-5">
		<input type="button" value=Spendi class="btn btn-danger col-10 offset-1 offset-md-1 col-md-5" onclick="manageMoney('<?php echo $personaggio['name']; ?>', false)">
		<input type="button" value=Ricevi class="btn btn-success col-10 offset-1 offset-md-0 col-md-5" onclick="manageMoney('<?php echo $personaggio['name']; ?>', true)">
	</div>

	<?php
	if (!empty($personaggio['history'])){ ?>
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
				usort($personaggio['history'], 'confrontaData');
				foreach ($personaggio['history'] as $riga) {
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
						echo "<td><i style=\"cursor: pointer;\" class=\"fa fa-solid fa-trash\" onclick=\"deleteHistory('{$personaggio['name']}','{$riga['date']}','{$riga['description']}')\"></i></td>";
						echo "</tr>";
					}
				}
				
				?>
				</tbody>
			</table>
		</div>
	</div>
<?php } ?>


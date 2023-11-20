<?php
if (isset($_POST['personaggio'])) {
    foreach ($_POST['personaggio'] as $key => $value) {
    	${$key} = $value;
	}
}
$rowsToShow = 3;
?>
<script>
$(document).ready(function() {
    const rowsToShow = <?php echo $rowsToShow; ?>;
    let startIndex = rowsToShow; // Inizia dal sesto elemento poiché i primi 5 sono già visibili

    // Nasconde tutte le righe che hanno la classe 'hidden'
    $('tr.hidden').hide();

    $('#loadMore').click(function() {
        // Mostra le prossime 5 righe
        $('tr.hidden').slice(0, rowsToShow).removeClass('hidden').fadeIn();
        startIndex += rowsToShow;

        // Se non ci sono più righe da mostrare, nascondi il pulsante
        if ($('tr.hidden').length === 0) {
            $('#loadMore').hide();
        }
    });
});
</script>



<div class="row">
	<h1 class="col-12"><?php echo $name; ?></h1>
	<div class="col-12">
		
	</div>

	<div class="col">
		<div class="portafoglio shadow rounded p-1 m-2">
		<div class="row text-center">
			<span style=scale(2) class="col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i> Platino:  <?php echo $platinum; ?></span>
		</div>
		<div class="row small text-center">
			<span class="col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i> Oro:  <?php echo $gold; ?></span>
			<span class="col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i> Argento:  <?php echo $silver; ?></span>
		</div>
		<div class="row small text-center">
			<span class="col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i> Rame:  <?php echo $copper; ?></span>
		</div>
		</div>
	</div>
</div>
	
<div class="row text-center mb-3">
<!-- Bottone per spendere denaro -->
<button class="btn btn-danger col-10 offset-1 offset-md-1 col-md-5" onclick="manageMoney('<?php echo htmlspecialchars($name); ?>', false)">
    <i class="fas fa-shopping-cart"></i> Spendi
</button>

<!-- Bottone per ricevere denaro -->
<button class="btn btn-success col-10 offset-1 offset-md-0 col-md-5" onclick="manageMoney('<?php echo htmlspecialchars($name); ?>', true)">
	<i class="fas fa-coins"></i> Ricevi 
</button>
</div>

<div class="row mt-4 item_Link">
	<button id=addLink class="btn btn-dark col-md-4 offset-md-4" onclick="addEditLink('<?php echo htmlspecialchars($name); ?>', false)">
		<i class="fas fa-plus"></i> Aggiungi Nuovo Link
	</button>
</div>

<?php if (!empty($links)): ?>
        <div class="row item_Link">
		<?php foreach ($links as $index => $link): ?>
			<div class="col-md-6">
				<div class="list-group-item d-flex justify-content-start align-items-center">
					<!-- Contenitore per il link e le note -->
					<div class="mr-auto text-left">
						<a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" class="d-block">
							<?php echo htmlspecialchars($link['text']); ?>
						</a>
						<?php if (!empty($link['note'])): ?>
							<small class="text-muted">
							<?php echo htmlspecialchars($link['note']); ?>
							</small>
						<?php endif; ?>
					</div>
					<!-- Bottone di modifica -->
					<button onclick="addEditLink('<?php echo htmlspecialchars($name); ?>', true, '<?php echo htmlspecialchars($link['url']); ?>', '<?php echo htmlspecialchars($link['text']); ?>', '<?php echo htmlspecialchars($link['note'] ?? ''); ?>')" class="btn btn-dark btn-sm">
						<i class="fas fa-edit"></i>
					</button>
					<!-- Bottone di eliminazione -->
					<button onclick="deleteSingleLink('<?php echo htmlspecialchars($name); ?>','<?php echo htmlspecialchars($link['url']); ?>', '<?php echo htmlspecialchars($link['text']); ?>')" class="ml-1 btn btn-danger btn-sm">
						<i class="fas fa-trash"></i>
					</button>
				</div>
			</div>
		<?php endforeach; ?>
        </div>
<?php endif; ?>

<?php
if (!empty($items)){
?>
<div class="row">
	<div class="col-12" style="display: block; font-size: x-small;">
		<table class="table" >
			<thead class="thead-dark">
				<tr>
					<th width="70%">Oggetto</th>
					<th width="30%">Quantità</th>
				</tr>
			</thead>
			<tbody>
			<?php
			// function confrontaInventario($a, $b) {
			// 	return strtotime($b['date']) - strtotime($a['date']);
			// }
			// usort($items, 'confrontaInventario');
			foreach ($items as $oggetto) {
				
			}
			
			?>
			</tbody>
		</table>
	</div>
</div>
<?php } ?>
	
<?php
if (!empty($history)){ ?>
<div class="row mt-5">
    <div class="col-12 col-md-8 offset-md-2" style="display: block; font-size: x-small;">
        <table class="table mb-0" >
            <thead class="thead-dark">
                <tr>
                    <th class="col-1">Tipo</th>
                    <th class="col-5">Denaro</th>
                    <th class="col-6">Descrizione</th>
                    <th class="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php
            function confrontaData($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            }
            usort($history, 'confrontaData');
            foreach ($history as $index => $riga) { // Utilizza $index come chiave
                $hiddenClass = ($index >= $rowsToShow) ? 'hidden' : ''; // Le righe oltre la quinta avranno la classe 'hidden'
                $classeRiga = strtolower($riga['type']) == 'received' ? 'table-success' : 'table-danger';
                echo "<tr class='{$classeRiga} {$hiddenClass}'>";
                echo "<td>{$riga['type']}</td>";
                echo "<td>";
				if($riga['platinum'] != 0) echo "<i class='fas fa-award platinum-color bordo-ico' title=platino></i> {$riga['platinum']} ";
				if($riga['gold'] != 0) echo "<i class='fas fa-medal gold-color bordo-ico' title=oro></i> {$riga['gold']} ";
				if($riga['silver'] != 0) echo "<i class='fas fa-trophy silver-color bordo-ico' title=argent></i> {$riga['silver']} ";
				if($riga['copper'] != 0) echo "<i class='fas fa-coins copper-color bordo-ico' title=rame></i> {$riga['copper']}";                echo "</td>";
                echo "<td>{$riga['description']}</td>";
                echo "<td><i style=\"cursor: pointer;\" class=\"fa fa-solid fa-trash\" onclick=\"deleteSingleHistory('{$name}','{$riga['date']}','{$riga['description']}')\"></i></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
		<?php if (count($history) > $rowsToShow ) : ?>
        	<button id="loadMore" class="btn btn-outline-light">Carica più</button>
        <?php endif; ?>
    </div>
</div>

<?php } ?>


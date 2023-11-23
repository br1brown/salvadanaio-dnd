<?php
if (isset($_POST['personaggio'])) {
    foreach ($_POST['personaggio'] as $key => $value) {
    	${$key} = $value;
	}
}

function renderSoldi($obj){
	$ret = "";
	$primamoneta = "<span style='white-space: nowrap;'>";
	$dopomoneta= "</span>&nbsp";
	
	if(isset($obj['platinum']) && $obj['platinum'] != 0) $ret .= "{$primamoneta}<i class='fas fa-award platinum-color bordo-ico' title=platino></i> {$obj['platinum']}{$dopomoneta}";
	if(isset($obj['gold']) && $obj['gold'] != 0) $ret .= "{$primamoneta}<i class='fas fa-medal gold-color bordo-ico' title=oro></i> {$obj['gold']}{$dopomoneta}";
	if(isset($obj['silver']) && $obj['silver'] != 0) $ret .= "{$primamoneta}<i class='fas fa-trophy silver-color bordo-ico' title=argent></i> {$obj['silver']}{$dopomoneta}";
	if(isset($obj['copper']) && $obj['copper'] != 0) $ret .= "{$primamoneta}<i class='fas fa-coins copper-color bordo-ico' title=rame></i> {$obj['copper']}{$dopomoneta}";

	return $ret;
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

	$(".btnmanage").click(function () {
		$(this).children('.fas').toggleClass('fa-chevron-down', 150);
	});


	$("#searchInventInput").on("keyup", function() {
        var value = $(this).val().trim().toLowerCase();
        $(".inventory-items > .oggettoInventario").each(function() {

			var itemName = $(this).find("strong").text().toLowerCase();
            
			var presente = value.split(" ").some(function(term) {
				return itemName.indexOf(term) > -1;
			});
			
			$(this).toggle(presente);

        });
    });
});
</script>



<div class="row">
	<div class="d-none d-md-block col-md-3 propic" style=" margin-left:auto; margin-right:auto;">
		<div class="polaroid ruotadestra">
			<img src="<?php echo isset($imgPath) && !empty($imgPath) ? $imgPath :"" ?>" alt="Immgine <?php echo $name; ?>">
			<p class="caption">
				<span class="small">[<a href="#" onclick="uploadImage('<?php echo htmlspecialchars($name); ?>')"><i class="fas fa-camera"></i></a>]</span>
				<span class="small">[<a href="#" onclick="linkdImage('<?php echo htmlspecialchars($name); ?>')"><i class="fas fa-link"></i></a>]</span>
				<span class="small">[<a href="#" onclick="deleteProPic('<?php echo htmlspecialchars($name); ?>')"><i class="fas fa-trash"></i></a>]</span>
			</p>
		</div>
	</div>
	<div class="col">
		<h1><strong><?php echo $name; ?></strong></h1>
	
		<div class="col text-center">
			<button class="btn btn-sm m-1 btn-success col-10 col-md-5" onclick="manageMoney('<?php echo htmlspecialchars($name); ?>', true)">
				<i class="fas fa-coins"></i> Ricevi 
			</button>
			<button class="btn btn-sm m-1 btn-danger col-10 col-md-5" onclick="manageMoney('<?php echo htmlspecialchars($name); ?>', false)">
				<i class="fas fa-shopping-cart"></i> Spendi
			</button>
			<button class="debiti btn btn-sm m-1 btn-sm btn-info col-10 col-md-5" onclick="creditTransaction('<?php echo htmlspecialchars($name); ?>',true)">
				<i class="fas fa-hand-holding-usd"></i> Fai Credito
			</button>
			<button class="debiti btn btn-sm m-1 btn-sm btn-warning col-10 col-md-5" onclick="creditTransaction('<?php echo htmlspecialchars($name); ?>',false)">
				<i class="fas fa-money-bill-wave"></i> Fai Debito
			</button>
		</div>


		<div class="portafoglio shadow rounded p-1 m-2 mb-0">
			<div class="row text-center">
				<span class="grandill-m col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i> Platino:  <?php echo $platinum; ?></span>
			</div>
			<div class="row small text-center">
				<span class="grandill col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i> Oro:  <?php echo $gold; ?></span>
				<span class="grandill col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i> Argento:  <?php echo $silver; ?></span>
			</div>
			<div class="row small text-center">
				<span class="grandill-s col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i> Rame:  <?php echo $copper; ?></span>
			</div>
		</div>
		
	</div>
</div>
<div class="row">



<?php
if (!empty($suspended))
foreach ($suspended as $tipo =>$obj) {
	$cls = "table-";
	$isCredit= "null";
?>
	<div class="col">
	<?php if ($tipo === "debt") :
		$isCredit= "false";
		$cls.="warning";?>
		<i>Debiti</i>
	<?php endif; ?>
	<?php if ($tipo === "credit") :
		$isCredit= "true";
		$cls.="info";?>
		<i>Crediti</i>
	<?php endif; ?>
	<ul class="list-unstyled">
		<?php foreach ($obj as $key => $tran) { ?>

			<li class="text-small m-1 <?php echo $cls; ?>">
					<button onclick="sanaContratto('<?php echo $name; ?>', <?php echo $isCredit; ?>,<?php echo $tran['platinum']?>, <?php echo $tran['gold']?>,<?php echo  $tran['silver']?>,<?php echo $tran['copper']?>,<?php echo (!empty($tran['person'])? "'".$tran['person']."'":"null"); ?>)" class="btn btn-danger btn-sm m-1">
						<i class="fas fa-ban"></i>
					</button>
					<strong><?php echo "<span class='badge badge-secondary'>".renderSoldi($tran)."</span>"; ?></strong>
					<?php if (!empty($tran['person'])): ?>
						<strong><?php echo $tran['person']; ?>:</strong>
					<?php endif; ?> 
					<?php echo $tran['description']; ?>
			</li>
	<?php } ?>
	</div>
<?php } ?>

</div>

<div class="row">
	<div class="col">
		<div class="col small">
		<div class="row small">
			<div class="col-auto">Azioni:</div>
			<!-- <div class="col-auto"><i class="fas fa-bars"></i> Azioni:</div> -->
			<?php
			$buttons = [
				[
					'testo' => 'Aggiungi Nuovo Link',
					'onclick' => "addEditLink('".htmlspecialchars($name)."', false)",
					'simbolo' => 'fas fa-plus',
					'class' => 'item_Link'
				],
				[
					'testo' => 'Nuovo Oggetto in inventario',
					'onclick' => "manageInventoryItem('add','".htmlspecialchars($name)."')",
					'simbolo' => 'fas fa-plus',
					'class' => 'inventory-items'
				],
				[
					'testo' => 'Cambio Valuta',
					'onclick' => "refreshcambio('".htmlspecialchars($name)."')",
					'simbolo' => 'fas fa-sync-alt',
					'class' => ''
				]
			];
			?>

			<?php foreach ($buttons as $key =>$button): ?>
				<a href="#" onclick="<?php echo $button['onclick']; ?>" class="col-9 col-md-auto  <?php echo $button['class']; ?> link-secondary">
					<i class="<?php echo $button['simbolo']; ?>"></i> <?php echo $button['testo']; ?>
				</a>
			<?php endforeach; ?>
		</div>
		</div>

	</div>
</div>
<div class="row">

<?php if (!empty($inventory)): ?>
	<div class="col-12 col-md">
    <div class="row inventory-items">
		<h3 class=col-12>Inventario
			<input type="text" class="form-control" id="searchInventInput" placeholder="Cerca nell'inventario..."></h3>

        <?php foreach ($inventory as $index => $item): ?>
            <div class="col-12 mb-1 oggettoInventario">
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <!-- Contenitore per il nome e la descrizione dell'oggetto -->
                    <div class="text-left d-flex align-items-center">
						<div class="quantity-display mr-1">
							<span class="badge badge-primary badge-pill p-3 grandill-s">
								<?php echo htmlspecialchars($item['quantity']); ?>
							</span>
						</div>
						<div>
                        <strong><?php echo htmlspecialchars($item['itemName']); ?></strong>
                        <?php if (!empty($item['description'])): ?>
                            <p class="text-muted mb-0">
                                <?php echo htmlspecialchars($item['description']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    </div>
					<div class="d-flex align-items-center">
						<button onclick="manageInventoryItem('edit','<?php echo htmlspecialchars($name); ?>', '<?php echo htmlspecialchars($item['itemName']); ?>', '<?php echo htmlspecialchars($item['quantity']); ?>', '<?php echo htmlspecialchars($item['description'] ?? ''); ?>')" class="btn btn-outline-secondary btn-sm">
							<i class="fas fa-edit"></i>
						</button>
						<button onclick="manageInventoryItem('delete', '<?php echo htmlspecialchars($name); ?>', '<?php echo htmlspecialchars($item['itemName']); ?>')" class="btn btn-outline-danger btn-sm ml-1">
							<i class="fas fa-trash"></i>
						</button>
					</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
	</div>
<?php endif; ?>
<?php if (!empty($links)): ?>
	<div class="col-12 col-md">
        <div class="row item_Link">
		<h3 class=col-12>Link Utili</h3>
		<?php foreach ($links as $index => $link): ?>
			<div class="col-12 mb-1">
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
					<button onclick="addEditLink('<?php echo htmlspecialchars($name); ?>', true, '<?php echo htmlspecialchars($link['url']); ?>', '<?php echo htmlspecialchars($link['text']); ?>', '<?php echo htmlspecialchars($link['note'] ?? ''); ?>')" class="btn btn-outline-dark btn-sm">
						<i class="fas fa-edit"></i>
					</button>
					<!-- Bottone di eliminazione -->
					<button onclick="deleteSingleLink('<?php echo htmlspecialchars($name); ?>','<?php echo htmlspecialchars($link['url']); ?>', '<?php echo htmlspecialchars($link['text']); ?>')" class="ml-1 btn btn-outline-danger btn-sm">
						<i class="fas fa-trash"></i>
					</button>
				</div>
			</div>
		<?php endforeach; ?>
        </div>
        </div>
<?php endif; ?>
</div>

	
<?php
if (!empty($history)){ ?>

<div class="row">
	<a href="#toManage" class="btn col-12 col-md-4 offset-md-4 btnmanage" data-toggle="collapse"><i class="fas fa-chevron-up fa-chevron-down"></i> Cronologia</a>
    <div class="col-12 col-md-8 offset-md-2 collapse" id="toManage" style="font-size: x-small;">
        <div style="overflow-x: auto;" class="mx-auto">
		<table class="table mb-0" >
				<thead class="thead-dark">
					<tr>
						<th class="col-1">Tipo</th>
						<th class="col-5 text-center">Denaro</th>
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
					echo "<td class=\"text-center\">".renderSoldi($riga)."</td>";
					echo "<td>{$riga['description']}</td>";
					echo "<td class='align-middle'><i style=\"cursor: pointer;\" class=\"fa fa-solid fa-trash\" onclick=\"deleteSingleHistory('{$name}','{$riga['date']}','{$riga['description']}')\"></i></td>";
					echo "</tr>";
				}
				?>
				</tbody>
			</table>
			<?php if (count($history) > $rowsToShow ) : ?>
				<div class="col-12 text-center">
					<button id="loadMore" class="btn btn-outline-light"><i class="fa fa-solid fa-arrow-down"></i> Carica più</button>
				</div>
			<?php endif; ?>
		
		</div>
</div>
</div>

<?php } ?>


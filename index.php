<?php 
$title = "Index";
$rowsToShow = 3;

include('TopPage.php');
$animali = callApiEndpoint($urlAPI,"animali");
?>

<div class="container-fluid">
		<div class="row">
			<div class="col-12 text-center text-light">
				<h1><b>Sono io</b></h1>
				<i>Performed by Br1Brown</i>
			</div>
		</div>
		<div class="row">
		    <div class="offset-1 col-10 offset-md-2 col-md-8 shadow rounded tutto text-center">
		        <div class="row">
		            <div class="p-3 col-xs-4 col-sm-4 col-md-4">
		                <div class="polaroid ruotadestra">
		                    <img src="https://via.placeholder.com/550x360/D3D3D3" alt="Foto Generica">
		                    <p class="caption">Immagine</p>
		                </div>
		            </div>
		            <div class="col-xs-8 col-sm-8 col-md-8">
		                <!-- https://getbootstrap.com/docs/4.0/components/buttons/ -->
		                <input type="button" data-type="success" id=success class="zoomma bottone btn btn-success btn-lg" value="SUCCESS">
		                <input type="button" data-type="error" id=danger class="zoomma bottone btn btn-danger btn-lg" value="DANGER"><br>
		                <input type="button" data-type="warning" id=warning class="zoomma bottone btn btn-warning btn-sm" value="WARNING">
		                <input type="button" data-type="info" id=info class="zoomma bottone btn btn-info btn-sm" value="INFO">
		            </div>
		        </div>
		        <div class="row">
		            <div class="text-center col-xs-12 col-sm-12 col-md-12">
		                <input type="button" id=primary class="bottone btn btn-outline-primary btn-sm" value="SUBMIT">
		                <input type="button" id=secondary class="bottone btn btn-outline-secondary btn-sm" value="SECONDARY">
		                <input type="button" id=dark class="bottone btn btn-outline-dark btn-sm" value="DARK">
		                <input type="button" id=light class="bottone btn btn-outline-light btn-sm" value="LIGHT">
		                <input type="button" id=link class="bottone btn btn-outline-link btn-sm" value="LINK">
		            </div>
		        </div>
		    </div>
		</div>
		

		<br>
		<div class="row">
		<div class="col-12 offset-md-2 col-md-8 text-center shadow rounded tutto">
			<div class="row">
			    <div class="col">
			        <div class="card">
			            <img src="https://via.placeholder.com/500/D3D3D3" class="card-img-top" alt="Immagine Card">
			            <div class="card-body">
			                <h5 class="card-title ">Titolo Card</h5>
			                <p class="card-text">Testo breve per descrivere il contenuto della card.</p>
			                <a class="bottone btn btn-primary">Vai da qualche parte?</a>
			            </div>
			        </div>
			    </div>
			    <div class="col">
			        <div class="card">
			            <img src="https://via.placeholder.com/450/D3D3D3" class="card-img-top" alt="Immagine Card">
			            <div class="card-body">
			                <h5 class="card-title">Titolo Card</h5>
			                <p class="card-text">Testo breve per descrivere il contenuto della card.</p>
			                <a class="bottone btn btn-primary">Vai da qualche parte</a>
			            </div>
			        </div>
			    </div>
			    <div class="col">
			        <div class="card">
			            <img src="https://via.placeholder.com/350/D3D3D3" class="card-img-top" alt="Immagine Card">
			            <div class="card-body">
			                <h5 class="card-title">Titolo Card</h5>
			                <p class="card-text">Testo breve per descrivere il contenuto della card.</p>
			                <a class="bottone btn btn-primary">Vai da qualche parte</a>
			            </div>
			        </div>
			    </div>
			</div>
			<?php if (isset($animali)) : ?>
				<div class="row">
					<div class="col">

						<ul class="list-group">
						<?php
						foreach ($animali as $index => $animale) {

							$hiddenClass = ($index >= $rowsToShow) ? 'hidden' : '';

							echo '<li class="list-group-item bg-transparent'.$hiddenClass.'"><strong>' . $animale['nome'] . ' -</strong> ' . $animale['descrizione'] . '</li>';
						}
						?>
						</ul>

					<?php if (count($animali) > $rowsToShow ) : ?>
						<div class="col-12 text-center">
						<button id="loadMore" class="btn btn-outline-light"><i class="fa fa-solid fa-arrow-down"></i> Carica più</button>
						</div>
					<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			
		</div>
		</div>
</div>

<?php include('BottomPage.php'); ?>

<script>
	
	$(document).ready(function () {
		const rowsToShow = <?php echo $rowsToShow; ?>;
		let startIndex = rowsToShow; // Inizia dal sesto elemento poiché i primi 5 sono già visibili


		// Nasconde tutte le righe che hanno la classe 'hidden'
		$('li.hidden').hide();

		$('#loadMore').click(function() {
			// Mostra le prossime 5 righe
			$('li.hidden').slice(0, rowsToShow).removeClass('hidden').fadeIn();
        	startIndex += rowsToShow;

			// Se non ci sono più righe da mostrare, nascondi il pulsante
			if ($('li.hidden').length === 0) {
				$('#loadMore').hide();
			}
		});



	 $.ajax({
		url: getApiUrl("anagrafica"),
		type: 'GET',
		dataType: 'json',
		// data: { name: "aa" },
		// success: genericSuccess,
		success: function (a){
			debugger;
		},
		error: handleError
		});
	});
</script>

</html>
<?php 
$title = "Index";

include('TopPage.php'); ?>

<div class="container-fluid">
		<div class="row">
			<div class="col-12 text-center text-light">
				<h1 class=zoomma><b>Sono io</b></h1>
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
		                <input type="button" data-type="success" id=success class="bottone btn btn-success btn-lg" value="SUCCESS">
		                <input type="button" data-type="error" id=danger class="bottone btn btn-danger btn-lg" value="DANGER"><br>
		                <input type="button" data-type="warning" id=warning class="bottone btn btn-warning btn-sm" value="WARNING">
		                <input type="button" data-type="info" id=info class="bottone btn btn-info btn-sm" value="INFO">
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
			                <h5 class="card-title">Titolo Card</h5>
			                <p class="card-text">Testo breve per descrivere il contenuto della card.</p>
			                <a class="bottone btn btn-primary">Vai da qualche parte</a>
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
			<div class="row">
			    <div class="col">
			        <ul class="list-group">
			            <li class="list-group-item bg-transparent"><strong>Elefante -</strong> Un simpatico gigante grigio con le orecchie più grandi di una pala da neve e un naso così lungo che potrebbe essere usato come aspirapolvere.</li>
			            <li class="list-group-item bg-transparent"><strong>Pinguino -</strong> Un buttafuori in frac, sembra sempre vestito per un gala, ma sembra avere dimenticato come si cammina senza sembrare ubriaco.</li>
			            <li class="list-group-item bg-transparent"><strong>Giraffa -</strong> Un creatore di tendenze per le acconciature, con un collo così lungo da poter sbirciare i segreti della luna. Ha anche delle antenne sulla testa che usano per... beh, nessuno lo sa veramente.</li>
			            <li class="list-group-item bg-transparent"><strong>Istrice -</strong> Un adorabile pungolo ambulante che porta sempre con sé i suoi aculei, nel caso dovesse improvvisare una partita di freccette.</li>
			            <li class="list-group-item bg-transparent"><strong>Fenicottero -</strong> Un modello da passerella rosa su una gamba sola. Potrebbe sembrare che sia sempre sul punto di cadere, ma è solo il suo modo di divertirsi.</li>
			        </ul>
			    </div>
			</div>
			
		</div>
		</div>
</div>

<?php include('BottomPage.php'); ?>

<script>
	
	$(document).ready(function () {
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
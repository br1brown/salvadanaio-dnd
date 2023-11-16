<!doctype html>
<html lang="it">

<head>

	<!-- Definizione della codifica dei caratteri per la pagina -->
	<meta charset="UTF-8">

	<!-- Impostazioni per il responsive design e il controllo della visualizzazione su dispositivi mobili -->
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!--shrink-to-fit=no-->

	<!-- Informazioni sull'autore del sito -->
	<meta name="author" content="Br1Brown">

	<title>Salvadanaio Singolo</title>

	<!-- ROBE PER IL MENU + SOCIAL anche quella prima per delle altre icone -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<!-- bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

	<!-- GLI ALERT -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

	<!-- Optional: include a polyfill for ES6 Promises for IE11 -->
	<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>

	<!-- ROBA NOSTRA -->
	<link rel="stylesheet" href="style/base.css">
	<link rel="stylesheet" href="style/manage_img.css">
	<link rel="stylesheet" href="style/social.css">
	<script src="script/base.js"></script>
	<!-- SFONDO CON LE NUVOLE -->
	<script src="script/jquery_bloodforge_smoke_effect.js"></script>
</head>
<body>
	<canvas id="smoke-effect-canvas"
		style="width:100%; height:100%; position: fixed;top: 0; left: 0; z-index: -100;"></canvas>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-2">
				<a href="index.html" class="btn btn-warning btn-sm w-100">Home</a>
			</div>
			<div id=contenuto class="col-12 col-md-8 text-center tutto">
				
			</div>
		<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i
				class="fas fa-chevron-up"></i></a>
	</div>
</body>
<script>
	$(document).ready(function () {
		$.ajax({
				url: 'API/get_single.php?name=<?php echo $_GET["name"]; ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response){
					if (response.status === 'error') {
						SweetAlert.fire('Errore', response.message, 'error').then(() => {window.location.href = 'index.html';});;
					}else{
						document.title = "Portafoglio di " + response.name;
						$.ajax({
							url: "template/detail.php",
							type: 'POST',
							data: {
								personaggio: response
							},
							success: function (response) {
								$('#contenuto').html(response);
							},
							error: function (xhr, status, error) {
								SweetAlert.fire('Errore', error, 'error');
							}
						});
					}
				},
				error: function (request, status, error) {
					SweetAlert.fire('Errore', "Errore di comunicazione con il server", 'error');
					}
		});

	});

</script>

</html>
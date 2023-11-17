<!doctype html>
<html lang="it">

<head>
	<?php include('header.php'); ?>
	<title>Salvadanaio Singolo</title>
</head>
<body>
	<canvas id="smoke-effect-canvas"
		style="width:100%; height:100%; position: fixed;top: 0; left: 0; z-index: -100;"></canvas>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-2">
				<a href="index" class="btn btn-warning btn-sm w-100">Home</a>
			</div>
			<div id=contenuto class="col-12 col-md-8 text-center tutto">
				
			</div>
		<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i
				class="fas fa-chevron-up"></i></a>
	</div>
    </div>
</body>
<script>
	$(document).ready(function () {
		$.ajax({
				url: 'API/get_single?name=<?php echo $_GET["name"]; ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response){
					if (response.status === 'error') {
						SweetAlert.fire('Errore', response.message, 'error').then(() => {window.location.href = 'index.html';});;
					}else{
						document.title = "Portafoglio di " + response.name;
						$.ajax({
							url: "template/detail",
							type: 'POST',
							data: {
								personaggio: response
							},
							success: function (response) {
								$('#contenuto').html(response);
							},
							error: function (xhr, status, error) {
									SweetAlert.fire('Errore', xhr.status + ': ' + xhr.responseText, 'error');
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
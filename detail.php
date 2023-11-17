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
				<div id=lista class="list-group col-12 d-none d-md-block tutto h-80" style="overflow-y: auto; overflow-x: auto;">
				</div>
			</div>
			<div id=contenuto class="col-12 col-md-9 text-center tutto">
				
			</div>
		<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i
				class="fas fa-chevron-up"></i></a>
	</div>
    </div>
</body>
<script>
	$(document).ready(function () {

		fetchCharacters(function() {
			var urlAttuale = window.location.protocol + "//" + window.location.host + window.location.pathname;
			$("#lista").empty();

			_cachePersonaggi.sort(function (a, b) { return b.totalcopper - a.totalcopper; })

			_cachePersonaggi.forEach(function(item) {
				var sonoqui = item.filename == "<?php echo $_GET['name']; ?>";
				var classe = "list-group-item bg-transparent" + (sonoqui ? "" : "");
				var link = urlAttuale + '?' + 'name=' + item.filename;
				var elemento;
				if(sonoqui) {
					elemento = "<span class='" + classe + "' aria-disabled='true'>" + item.name + "</span>";
				} else {
					elemento = "<a href='" + link + "' class='" + classe + "'>" + item.name + "</a>";
				}
				$("#lista").append(elemento);
			});

		});



		$.ajax({
				url: 'API/get_single?name=<?php echo $_GET["name"]; ?>',
				type: 'GET',
				dataType: 'json',
				success: function(response){
					if (response.status === 'error') {
						SweetAlert.fire('Errore', response.message, 'error').then(() => {window.location.href = 'index';});;
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
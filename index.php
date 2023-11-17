<!doctype html>
<html lang="it">

<head>
    <?php include('header.php'); ?>
	<title>Salvadanaio</title>
</head>


<body>
	<canvas id="smoke-effect-canvas"
		style="width:100%; height:100%; position: fixed;top: 0; left: 0; z-index: -100;"></canvas>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-1 text-center"></div>
			<div class="col-12 col-md-10 text-center tutto">
				<div class="row">
					<h1 class="col-12 d-none d-md-block">Portafoglio Personaggi <span class="addCharacterBtn">[+]</span>
					</h1>
					<h3 class="col-12 d-block d-sm-none">Portafoglio Personaggi <span class="addCharacterBtn">[+]</span>
					</h3>

					<div class="col-12">
						<select id="ordinamento" class="offset-md-4 col-md-4 col-12 form-control form-control-sm">
							<option value="ricco_povero" selected>Dal più ricco al più povero</option>
							<option value="povero_ricco">Dal più povero al più ricco</option>
						</select>
					</div>
				</div>

				<div id="charactersContainer" class="row">

				</div>
			</div>
		</div>

		<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i
				class="fas fa-chevron-up"></i></a>
	</div>
</body>
<script>
	$(document).ready(function () {

		function fetch() {
			$('#charactersContainer').html('<img src="loading.gif" class="w-100">');
			var ordine = $('#ordinamento').val();
			fetchCharacters(
				function (characters) {
					debugger
					$.get('template/character', function (template) {

						characters.sort(function (a, b) {
							if (ordine === 'ricco_povero') {
								return b.totalcopper - a.totalcopper;
							} else {
								return a.totalcopper - b.totalcopper;
							}
						});
						$('#charactersContainer').empty();
						$.each(characters, function (index, character) {
							var characterHtml = template.replace(/{{name}}/g, character.name)
								.replace(/{{platinum}}/g, character.platinum)
								.replace(/{{gold}}/g, character.gold)
								.replace(/{{silver}}/g, character.silver)
								.replace(/{{copper}}/g, character.copper)
								.replace(/{{filename}}/g, character.filename)
							$('#charactersContainer').append('<div class="col-12 col-md-6">' + characterHtml + "</div>");
						});
					});
				},
				function (request, status, error) {
					SweetAlert.fire('Errore', 'Impossibile caricare i personaggi.', 'error');
				}
			);
		}



		fetch();

		$('#ordinamento').change(fetch);
	});
</script>

</html>
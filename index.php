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

					<div class="offset-md-2 col-md-8 col-12">
						<div class=row>
							<select id="ordinamento" class="col form-control form-control-sm">
								<option value="ricco_povero" selected>Dal più ricco al più povero</option>
								<option value="povero_ricco">Dal più povero al più ricco</option>
							</select>
							<div class="input-group col" id="spazioRicerca">
								<div class="form-outline">
									<input type="search" placeholder="Ricerca" id="ricerca" class="form-control form-control-sm" />
							</div>
						</div>
					</div>
				</div>

				<div id="characterContainer" class="row w-100" style="margin: 0;">
				<img src="loading.gif" align=center class="col-12 w-100">
				</div>
			</div>
		</div>

		<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i
				class="fas fa-chevron-up"></i></a>
	</div>
</body>
<script>
var HTMLcharacterContainer = "";
var _cachePersonaggi = [];
	$(document).ready(function () {
    	HTMLcharacterContainer = $('#characterContainer').html();

		function renderUI(){
			if (_cachePersonaggi.length > 5)
				$('#spazioRicerca').show();
			else
				$('#spazioRicerca').hide();

			var ordine = $('#ordinamento').val();
			var ricerca = $('#ricerca').val().toLowerCase();

			var filtered = _cachePersonaggi
			.filter(function (elemento) {
				if ((ricerca) && ricerca != "")
					return elemento.name.toLowerCase().includes(ricerca);
				return true;
			})
			.sort(function (a, b) {
				if (ordine === 'ricco_povero') {
				return b.totalcopper - a.totalcopper;
				} else {
				return a.totalcopper - b.totalcopper;
				}
			});

			$.ajax({
				url: getTemplateUrl("characters"),
				type: 'POST',
				data: JSON.stringify(filtered),
				success: function (characterHtml) {
					$('#characterContainer').html(characterHtml);
				},
				error: function (xhr, status, error) {
					SweetAlert.fire('Errore', xhr.status + ': ' + xhr.responseText, 'error');
			}
			});
		}

		fetch();
		$('#ordinamento').change(renderUI);
		$('#ricerca').keypress(function(e){
			if(e.which == 13){
				renderUI();
			}
		});

		function fetch() {
			$('#characterContainer').html(HTMLcharacterContainer);
			fetchCharacters(renderUI);
		}


	});
</script>

</html>
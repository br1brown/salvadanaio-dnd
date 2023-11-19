<?php 
$title = "Salvadanaio";
?>
    <?php include('TopPage.php'); ?>

	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-1 text-center"></div>
			<div class="col-12 col-md-10 text-center tutto">
				<div class="row">
					<h1 class="col-12 d-none d-md-block">Portafoglio Personaggi <span class="addCharacterBtn">[+]</span>
					</h1>
					<h3 class="col-12 d-block d-sm-none">Portafoglio Personaggi <span class="addCharacterBtn">[+]</span>
					</h3>

					<div class="filtri offset-md-2 col-md-8 col-12">
						<div class="row">
							<select id="ordinamento" class="col col-12 col-md form-control form-control-sm">
								<option value="ricco_povero" selected>Dal più ricco al più povero</option>
								<option value="povero_ricco">Dal più povero al più ricco</option>
							</select>
							<div class="input-group col-6 col-md" id="spazioRicerca">
								<div class="form-outline">
									<input type="search" placeholder="Ricerca" id="ricerca" class="form-control form-control-sm" />
							</div>
							<button id=bottone class="btn btn-outline-dark btn-sm col-5 col-md-2 ml-1">
								<i class="fa fa-search"></i>
							</button>
						</div>
					</div>
				</div>

				<div id="characterContainer" class="row w-100" style="margin: 0;">
				<img src="loading.gif" align=center class="col-12 w-100">
				</div>
			</div>
		</div>

	</div>
</body>
<script>
	
	function ReloadAfterSuccess (){
		if (_cachePersonaggi.length < 5)
			$('#spazioRicerca').hide();

		var ordine = $('#ordinamento').val();
		var ricerca = $('#ricerca').val().toLowerCase();

		var filtered = _cachePersonaggi
		.filter(function (elemento) {
		if ((ricerca) && ricerca != "") {
				var parole = elemento.name.toLowerCase().split(/\s+/);
				var ricerche = ricerca.toLowerCase().split(/\s+/);
				// Verifica che ogni parola della ricerca sia presente nel nome
				return ricerche.some(function (parolaRicerca) {
					return parole.some( function (parola) {
					return parola.includes(parolaRicerca);
				});
				});
			}
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
			error: handleError
		});
	}

	var HTMLcharacterContainer = "";
	$(document).ready(function () {
    	HTMLcharacterContainer = $('#characterContainer').html();

		fetch();
		$('#ordinamento').change(ReloadAfterSuccess);
		$('#bottone').click(ReloadAfterSuccess);
		$('#ricerca').keypress(function(e){
			if(e.which == 13){
				ReloadAfterSuccess();
			}
		});

		function fetch() {
			$('#characterContainer').html(HTMLcharacterContainer);
			fetchCharacters(ReloadAfterSuccess);
		}

		$('.addCharacterBtn').click(function () {
			SweetAlert.fire({
				title: 'Crea Nuovo Personaggio',
				input: 'text',
				inputLabel: 'Nome del Personaggio',
				showCancelButton: true,
				confirmButtonText: 'Crea',
				inputValidator: (value) => {
					if (!value) {
						return 'Devi inserire un nome!';
					}
				}
			}).then((result) => {
				if (result.isConfirmed) {
					// Crea un nuovo personaggio con il nome fornito
					$.ajax({
						url: getApiMethod("add", "personaggio"),
						type: 'POST',
						dataType: 'json',
						data: { name: result.value },
						success: genericSuccess,
						error: handleError
					});
				}
			});
		});


	});
</script>

</html>
<?php 
$title = "Salvadanaio Singolo";
?>
    <?php include('TopPage.php'); ?>	<div class="container-fluid">
	<div class="row">
		<div class="col-12 col-md-2">
			<a href="index" class="btn btn-warning btn-sm w-100">Home</a>
			<div id=lista class="list-group col-12 d-none d-md-block tutto h-80" style="overflow-y: auto; overflow-x: auto;">
			</div>
		</div>
		<div class="col-12 col-md-9">
			<div id=contenuto class="tutto">
				
			</div>
			<div class="row">
				<button type="button" onclick="eliminami('<?php echo $_GET['basename']; ?>',1)" class="col-12 col-md-6 offset-md-3 btn btn-outline-danger btn-sm"><i class="fa fa-solid fa-trash"></i> <span id="lbldel">Elimina</span></button>
			</div>
		</div>
		
    </div>
	<br>

</body>
<script>

	var isElimina = false;
	function ReloadAfterSuccess() {
		if (isElimina)
			location.href = ("index");
		else
			refreshUI();
	}

	$(document).ready(function() {

		refreshUI();

		fetchCharacters(function() {
			var urlAttuale = window.location.protocol + "//" + window.location.host + window.location.pathname;
			$("#lista").empty();

			_cachePersonaggi.sort(function(a, b) {
				return b.totalcopper - a.totalcopper;
			})

			_cachePersonaggi.forEach(function(item) {
				var sonoqui = item.basename == "<?php echo $_GET['basename']; ?>";
				var classe = "list-group-item bg-transparent" + (sonoqui ? "" : "");
				var link = urlAttuale + '?' + 'basename=' + item.basename;
				var elemento;
				if (sonoqui) {
					elemento = "<span class='" + classe + "' aria-disabled='true'>" + item.name + "</span>";
				} else {
					elemento = "<a href='" + link + "' class='" + classe + "'>" + item.name + "</a>";
				}
				$("#lista").append(elemento);
			});

		});
	});


	function refreshUI(){
		$.ajax({
			url: getApiMethod("get", "single", {
				basename: '<?php echo $_GET["basename"]; ?>'
			}),
			type: 'GET',
			dataType: 'json',
			success: function(response) {
				$("#lbldel").html("Eliminare " + response.name);
				if (response.status === 'error') {
					SweetAlert.fire('Errore', response.message, 'error').then(() => {
						window.location.href = 'index';
					});;
				} else {
					document.title = "Portafoglio di " + response.name;
					$.ajax({
						url: getTemplateUrl("detail"),
						type: 'POST',
						data: {
							personaggio: response
						},
						success: function(response) {
							$('#contenuto').html(response);
						},
						error: handleError
					});
				}
			},
			error: handleError
		});

	}


	function addEditLink(characterName, isEdit, url, text, note) {
		
		var linkData = {
			url,
			text,
			note,
			isEdit
		}
		var actionWord = isEdit ? 'Modifica' : 'Aggiungi';

		$.ajax({
			url: getTemplateUrl("link-form"),
			type: 'POST',
			data: linkData,
			success: function(htmlResponse) {
				SweetAlert.fire({
					title: actionWord + ' link per ' + characterName,
					html: htmlResponse,
					confirmButtonText: actionWord,
					focusConfirm: false,
					showCancelButton: true,
					preConfirm: () => {
						const url = Swal.getPopup().querySelector('#url').value;
						const linkText = Swal.getPopup().querySelector('#linkText').value;
						const note = Swal.getPopup().querySelector('#note').value;

						try {
							new URL(url);
						} catch (e) {
							Swal.showValidationMessage('Per favore, inserisci un URL che sia valido');
						}

						if (!url || !linkText)
							Swal.showValidationMessage('Per favore, inserisci sia l\'URL che il testo del link.');

						return {
							url,
							linkText,
							note
						};
					}
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							url: getApiMethod((isEdit ? 'edit' : 'add'), "link"),
							type: 'POST',
							dataType: 'json',
							data: {
								name: characterName,
								oldUrl: linkData.url,
								url: result.value.url,
								linkText: result.value.linkText,
								note: result.value.note.replace(/\n/g, ' - ')
							},
							success: function(response) {
								if (isEdit == true && response.status != 'success') {
									SweetAlert.fire('Errore', response.message, 'error').then(() => {
										addEditLink(characterName, false, result.value.url, result.value.linkText, result.value.note)
									});
								} else
									genericSuccess(response)
							},
							error: handleError
						});
					}
				});
			},
			error: handleError
		});

	}

	function deleteSingleHistory(nome, datastoriacancellare, descrizione) {
		SweetAlert.fire({
			title: 'Sei sicuro?',
			html: "Vuoi davvero eliminare elemento '" + descrizione + "' dallo storico?<br><small>Non inficerà sulle somme possedute</small>",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sì, elimina!',
			cancelButtonText: 'Annulla'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: getApiMethod("delete", "item_cronologia"),
					type: 'POST',
					dataType: 'json',
					data: {
						name: nome,
						date: datastoriacancellare,
					},
					success: genericSuccess,
					error: handleError
				});
			}
		});
	}

	function deleteSingleLink(nome, url, text) {
		SweetAlert.fire({
			title: 'Sei sicuro?',
			html: "Vuoi davvero eliminare link '" + text + "' ?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sì, elimina!',
			cancelButtonText: 'Annulla'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: getApiMethod("delete", "item_link"),
					type: 'POST',
					dataType: 'json',
					data: {
						name: nome,
						url: url,
					},
					success: genericSuccess,
					error: handleError
				});
			}
		});
	}

	function uploadImage(characterName) {
		SweetAlert.fire({
			title: 'Carica immagine di ' + characterName,
			html: '<input id="imageInput" type="file" class="swal2-input form-control form-control-sm " accept="image/*">',
			confirmButtonText: 'Carica',
			focusConfirm: false,
			showCancelButton: true,
			preConfirm: () => {
				const imageInput = Swal.getPopup().querySelector('#imageInput').files[0];
				if (!imageInput)
					Swal.showValidationMessage('Per favore, seleziona un\'immagine.');

				return { image: imageInput };
			}
		}).then((result) => {
			if (result.isConfirmed) {
				var formData = new FormData();
				formData.append('name', characterName);
				formData.append('image', result.value.image);

				$.ajax({
					url: getApiUrl('set_image'),
					type: 'POST',
					processData: false,
					contentType: false,
					dataType: 'json',
					data: formData,
					success: genericSuccess,
					error: handleError
				});
			}
		});
	}


	function linkdImage(characterName) {
		SweetAlert.fire({
			title: 'Linka immagine di ' + characterName,
			html: '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-link"></i></span></div><input type="url" id="url" name="url" class="form-control form-control-sm" placeholder="Inserisci l\'URL dell\'iimagine" value="" required></div>',
			confirmButtonText: 'Linka',
			focusConfirm: false,
			showCancelButton: true,
			preConfirm: () => {
				const url = Swal.getPopup().querySelector('#url') .value;

				if (!url || (url.match(/\.(jpg|gif|png)$/)!= null))
					Swal.showValidationMessage('Per favore, seleziona un\'immagine.');

				return { url };
			}
		}).then((result) => {
			if (result.isConfirmed) {

				$.ajax({
					url: getApiUrl('link_image'),
					type: 'POST',
					dataType: 'json',
					data: {
						name: characterName,
						link: result.value.url
					},
					success: genericSuccess,
					error: handleError
				});
			}
		});
	}


	function eliminami(baseName, iter) {
		isElimina = true;
		var title = 'Sei';
		for (let i = 0; i < iter; i++) {
			title = title + ' sicuro';
		}
		title += '?';
		SweetAlert.fire({
			title: title,
			html: "Vuoi eliminare il personaggio?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Sì, elimina!',
			cancelButtonText: 'Annulla'
		}).then((result) => {
			if (result.isConfirmed) {
				if (iter < 2) {
					eliminami(baseName, iter + 1);
				} else {

					$.ajax({
						url: getApiMethod("delete", "personaggio"),
						type: 'POST',
						dataType: 'json',
						data: {
							baseName: baseName,
						},
						success: genericSuccess,
						error: handleError
					});
				}
			}
		});
	}

</script>

</html>
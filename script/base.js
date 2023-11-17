var _cachePersonaggi = [];
//https://sweetalert2.github.io/
$(document).ready(function () {
	$('.addCharacterBtn').click(addNewCharacter);

	$(window).scroll(function () {
		if ($(this).scrollTop() > 50) {
			$('#back-to-top').fadeIn();
		} else {
			$('#back-to-top').fadeOut();
		}
	});


	// scroll body to 0px on click
	$('#back-to-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 400);
		return false;
	});
	$('#smoke-effect-canvas').SmokeEffect({
		color: 'orange',
		opacity: 0.4,
		maximumVelocity: 100,
		particleRadius: 250,
		density: 5
	});

});


// Funzione per caricare i dati dei personaggi
function fetchCharacters(_success) {
	$.ajax({
		url: 'API/get_characters',
		type: 'GET',
		dataType: 'json',
		success: function (personaggi) {
			_cachePersonaggi = personaggi;


			_success()
		},
		error: handleError
	});
}


// Funzione per gestire il denaro
function manageMoney(characterName, isReceiving) {
	$.get(('template/insert?spendi=' + (isReceiving ? "0" : "1")), function (template, n2) {
		const actionWord = isReceiving ? 'Ricevi' : 'Spendi';
		SweetAlert.fire({
			title: actionWord + ' monete per ' + characterName,
			html: template,
			confirmButtonText: actionWord,
			focusConfirm: false,
			showCancelButton: true,
			preConfirm: () => {
				const platinum = Swal.getPopup().querySelector('#platinum').value;
				const gold = Swal.getPopup().querySelector('#gold').value;
				const silver = Swal.getPopup().querySelector('#silver').value;
				const copper = Swal.getPopup().querySelector('#copper').value;
				const description = Swal.getPopup().querySelector('#description').value;
				const canReceiveChange = !isReceiving ? Swal.getPopup().querySelector('#canReceiveChange').checked : false;

				if (description == "" || (!description))
					Swal.showValidationMessage('Inserire descrizione');

				var bindCorrect = true;
				validanum = function (sznum, nome) {
					if (sznum == "")
						return 0;
					var Num = parseFloat(sznum);
					if (isNaN(Num)) {
						bindCorrect = false;
						Swal.showValidationMessage('Errore nella validazione ' + nome);
						return 0;
					}
					if (Num < 0) {
						bindCorrect = false;
						Swal.showValidationMessage('Modifica non supportata: Valore ' + nome + ' negativo');
					}
					return Num;
				}

				var platinumNum = validanum(platinum, "platino");
				var goldNum = validanum(gold, "Oro");
				var silverNum = validanum(silver, "Argento");
				var copperNum = validanum(copper, "Rame");
				if (bindCorrect && (platinumNum + goldNum + silverNum + copperNum == 0)) {
					Swal.showValidationMessage('Nessuna variazione nel salvadanaio');
				}

				return { platinum, gold, silver, copper, description, canReceiveChange };
			}
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "API/" + (isReceiving ? 'ricevi' : 'spendi'),
					type: 'POST',
					dataType: 'json', // Assicurati che la risposta sia in formato JSON
					data: {
						name: characterName,
						platinum: result.value.platinum,
						gold: result.value.gold,
						silver: result.value.silver,
						copper: result.value.copper,
						canReceiveChange: result.value.canReceiveChange,
						description: result.value.description
					},
					success: genericSuccess,
					error: handleError
				});
			}
		});
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
		url: "template/link-form",
		type: 'POST',
		data: linkData,
		success: function (htmlResponse) {
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
					}
					catch (e) {
						Swal.showValidationMessage('Per favore, inserisci un URL che sia valido');
					}

					if (!url || !linkText)
						Swal.showValidationMessage('Per favore, inserisci sia l\'URL che il testo del link.');

					return { url, linkText, note };
				}
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: "API/" + (isEdit ? 'modifica-link' : 'aggiungi-link'),
						type: 'POST',
						dataType: 'json',
						data: {
							name: characterName,
							oldUrl: linkData.url,
							url: result.value.url,
							linkText: result.value.linkText,
							note: result.value.note.replace(/\n/g, ' - ')
						},
						success: function (response) {
							if (isEdit == true && response.status != 'success') {
								SweetAlert.fire('Errore', response.message, 'error').then(() => {
									addEditLink(characterName, false, result.value.url, result.value.linkText, result.value.note)
								});
							}
							else
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


function addNewCharacter() {
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
				url: 'API/aggiungi_personaggio',
				type: 'POST',
				dataType: 'json',
				data: { name: result.value },
				success: genericSuccess,
				error: handleError
			});
		}
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
				url: "API/elimina_itemcronologia",
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
				url: "API/elimina_itemlink",
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

function handleError(xhr, status, error) {
	SweetAlert.fire('Errore ' + xhr.status, xhr.responseText, 'error');
}

function genericSuccess(response) {
	if (response.status === 'success') {
		SweetAlert.fire('Ottimo!', response.message, 'success').then(() => {
			location.reload();
		});
	} else {
		SweetAlert.fire('Errore', response.message, 'error');
	}
}
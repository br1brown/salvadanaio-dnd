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
function fetchCharacters(_success, _error) {
	$.ajax({
		url: 'API/get_characters.php',
		type: 'GET',
		dataType: 'json',
		success: _success,
		error: _error
	});
}


// Funzione per gestire il denaro
function manageMoney(characterName, isReceiving) {
	$.get(('template/insert.php?spendi=' + (isReceiving ? "0" : "1")), function (template, n2) {
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
					url: "API/" + (isReceiving ? 'ricevi.php' : 'spendi.php'),
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
					success: function (response) {
						// La risposta dal server dovrebbe essere un oggetto JSON
						if (response.status === 'success') {
							SweetAlert.fire('Successo', response.message, 'success').then(() => {
								location.reload();
							});
						} else {
							SweetAlert.fire('Errore', response.message, 'error');
						}
					},
					error: function (xhr, status, error) {
						SweetAlert.fire('Errore', 'Si è verificato un errore nella comunicazione con il server.', 'error');
					}
				});
			}
		});
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
				url: 'API/aggiungi_personaggio.php',
				type: 'POST',
				dataType: 'json',
				data: { name: result.value },
				success: function (response) {
					if (response.status === 'success') {
						SweetAlert.fire('Creato!', response.message, 'success').then(() => {
							location.reload();
						});
					} else {
						SweetAlert.fire('Errore!', response.message, 'error');
					}
				},
				error: function (xhr, status, error) {
					SweetAlert.fire('Errore!', 'Non è stato possibile creare il personaggio.', 'error');
				}
			});
		}
	});
}

function deleteHistory(nome, datastoriacancellare, descrizione) {
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
				url: "API/elimina_cronologia.php",
				type: 'POST',
				dataType: 'json',
				data: {
					name: nome,
					date: datastoriacancellare,
				},
				success: function (response) {
					if (response.status === 'success') {
						SweetAlert.fire('Successo', response.message, 'success').then(() => {
							location.reload();
						});
					} else {
						SweetAlert.fire('Errore', response.message, 'error');
					}
				},
				error: function (xhr, status, error) {
					SweetAlert.fire('Errore', 'Si è verificato un errore nella comunicazione con il server.', 'error');
				}
			});
		}
	});
}

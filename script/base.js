var _cachePersonaggi = [];

//https://sweetalert2.github.io/
$(document).ready(function () {

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

function fetchCharacters(_success) {
	$.ajax({
		url: getApiMethod("get", "characters"),
		type: 'GET',
		dataType: 'json',
		success: function (personaggi) {
			_cachePersonaggi = personaggi;
			_success();
		},
		error: handleError
	});
}


function refreshcambio(name) {
	$.ajax({
		url: getApiUrl("refresh_cambio"),
		type: 'POST',
		dataType: 'json',
		data: {
			name: name,
		},
		success: function (response) {
			if (response.status === 'success') {
				if (window.ReloadAfterSuccess) {
					ReloadAfterSuccess()
				}
				else {
					location.reload();
				}
			} else {
				SweetAlert.fire('Errore', response.message, 'error');
			}
		},
		error: handleError
	});
}

function creditTransaction(characterName, isCredit) {
	const actionWord = isCredit ? 'Credito' : 'Debito';
	$.get(getApiMethod("get", "names"), function (lista) {
		var altri = JSON.parse(lista).filter(e => e !== characterName)
		$.get(getTemplateUrl('insert', { lista: JSON.stringify(altri) }), function (template) {
			SweetAlert.fire({
				title: actionWord + ' per ' + characterName,
				html: template,
				confirmButtonText: "Procedi",
				focusConfirm: false,
				showCancelButton: true,
				preConfirm: () => {
					const platinum = Swal.getPopup().querySelector('#platinum').value;
					const gold = Swal.getPopup().querySelector('#gold').value;
					const silver = Swal.getPopup().querySelector('#silver').value;
					const copper = Swal.getPopup().querySelector('#copper').value;
					var description = Swal.getPopup().querySelector('#description').value;
					const persona = Swal.getPopup().querySelector('#altro').value;

					if (persona == "" || (!persona))
						Swal.showValidationMessage('Inserire persona di rifermento');

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
						Swal.showValidationMessage('Nessuna valuta rilevata');
					}

					if (description == "" || (!description))
						description = actionWord + " con " + persona;

					return { platinum, gold, silver, copper, description, persona };
				}
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: getApiUrl((isCredit ? 'credito' : 'debito')),
						type: 'POST',
						dataType: 'json', // Assicurati che la risposta sia in formato JSON
						data: {
							name: characterName,
							platinum: result.value.platinum,
							gold: result.value.gold,
							silver: result.value.silver,
							copper: result.value.copper,
							persona: result.value.persona,
							description: result.value.description
						},
						success: genericSuccess,
						error: handleError
					});
				}
			});
		});
	});
}


function sanaContratto(characterName, isCredit, platinum, gold, silver, copper, persona) {
	var word = (isCredit ? 'credito' : 'debito');
	SweetAlert.fire({
		title: 'Sei sicuro?',
		html: "Vuoi esaurire il " + word + " di '" + characterName + "'<br> sono " + platinum + "p " + gold + "g " + silver + "s " + copper + "c?<br><small>Le modifiche saranno effettive sui soldi posseduti</small>",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Procedi!',
		cancelButtonText: 'Annulla'
	}).then((result) => {
		debugger
		if (result.isConfirmed) {
			$.ajax({
				url: getApiUrl("sana_" + (isCredit ? 'credito' : 'debito')),
				type: 'POST',
				dataType: 'json',
				data: {
					name: characterName,
					platinum,
					gold,
					silver,
					copper,
					persona,
					description: word + " sanato"
				},
				success: genericSuccess,
				error: handleError
			});
		}
	});
}


function manageMoney(characterName, isReceiving) {
	$.get(getTemplateUrl('insert', { spendi: (isReceiving ? "0" : "1") }), function (template) {
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
					url: getApiUrl((isReceiving ? 'ricevi' : 'spendi')),
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

function handleError(xhr, status, error) {
	SweetAlert.fire('Errore ' + xhr.status, xhr.responseText, 'error');
}

function genericSuccess(response) {
	if (response.status === 'success') {
		SweetAlert.fire('Ottimo!', response.message, 'success').then(() => {
			if (window.ReloadAfterSuccess) {
				ReloadAfterSuccess()
			}
			else {
				location.reload();
			}
		});
	} else {
		SweetAlert.fire('Errore', response.message, 'error');
	}
}

function getApiMethod(action, metod, params = null) {
	return "API/" + action + "/" + metod + MakeGetQueryString(params);
}
function getApiUrl(action, params = null) {
	return "API/" + action + MakeGetQueryString(params);
}

function getTemplateUrl(type, params = null) {
	return "template/" + type + MakeGetQueryString(params);
}


function getConfigUrl() {
	return "config.json";
}

function MakeGetQueryString(parametri) {
	var ret = '';
	if (!parametri || Object.keys(parametri).length === 0)
		ret = '';
	else
		ret = Object.keys(parametri)
			.map(key => `${encodeURIComponent(key)}=${encodeURIComponent(parametri[key])}`)
			.join('&');

	if (ret != '')
		return "?" + ret;
	return ret;

}

function IsAggiornato() {
	$.ajax({
		url: 'https://api.github.com/repos/br1brown/salvadanaio-dnd/contents/settingsFE.json',
		//url: 'https://api.github.com/repos/br1brown/salvadanaio-dnd/contents/.versione',
		type: 'GET',
		headers: {
			'Accept': 'application/vnd.github.v3.raw'
			// 'Authorization': 'token YOUR_GITHUB_TOKEN'
		},
		success: function (gitHubContent) {
			var localContent = 'CONTENUTO_DEL_TUO_FILE_LOCALE';
			debugger;
			if (gitHubContent.trim() === localContent.trim()) {
				console.log('I file sono identici.');
			} else {
				console.log('I file sono diversi.');
			}
		},
		error: function (request, status, error) {
			console.error('Errore nella richiesta:', status, error);
		}
	});
}
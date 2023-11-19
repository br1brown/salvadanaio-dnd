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

var lastfetchCharacters = null;
function fetchCharacters(_success) {
	$.ajax({
		url: getApiMethod("get", "characters"),
		type: 'GET',
		dataType: 'json',
		success: function (personaggi) {
			_cachePersonaggi = personaggi;


			_success(personaggi)
		},
		error: handleError
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
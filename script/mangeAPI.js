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


function getApiMethod(action, metod, params = null) {
	return APIEndPoint + "/" + action + "/" + metod + MakeGetQueryString(params);
}
function getApiUrl(action, params = null) {
	return APIEndPoint + "/" + action + MakeGetQueryString(params);
}

function apiCall(endpoint, data, callback = null, type = 'GET', dataType = 'json') {
	let settings = {
		url: type === 'GET' ? getApiUrl(endpoint, data) : getApiUrl(endpoint),
		type: type,
		headers: {
			'X-Api-Key': APIKey
		},
		dataType: dataType,
		success: function (response) {
			genericSuccess(response, callback);
		},
		error: handleError
	};

	if (type !== 'GET') {
		settings.data = data; // Aggiunge i dati al corpo della richiesta per POST, PUT, DELETE, ecc.
	}

	$.ajax(settings);
}

function handleError(xhr, status, error) {
	// Log dell'errore per il debugging
	console.error(`Errore API: ${status} - ${error}`, xhr.responseText);

	let errorMessage = 'Si è verificato un errore imprevisto';

	if (xhr.status === 404) {
		errorMessage = 'Risorsa non trovata';
	} else if (xhr.status === 500) {
		errorMessage = 'Errore interno del server';
	} else if (xhr.responseText) {
		try {
			const response = JSON.parse(xhr.responseText);
			errorMessage = response.message || errorMessage;
		} catch (e) {
			// xhr.responseText non è JSON, usa il messaggio di errore generico
		}
	}
	SweetAlert.fire('Errore ' + xhr.status, errorMessage, 'error');
}


function genericSuccess(response, callback) {
	if (response.status === 'success') {
		SweetAlert.fire('Ottimo!', response.message, 'success').then(() => {
			if (typeof callback === "function") {
				callback()
			}
		});
	} else if (response.status === 'error') {
		SweetAlert.fire('Errore', response.message, 'error');
	}
	else {
		if (typeof callback === "function") {
			callback(response)
		}
	}
}

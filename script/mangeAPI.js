/**
 * Crea una stringa di query URL da un oggetto di parametri.
 * 
 * @param {Object} parametri - L'oggetto contenente le coppie chiave-valore da convertire in stringa di query.
 * @returns {string} La stringa di query formattata, preceduta da '?' se non vuota, altrimenti una stringa vuota.
 */
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

/**
 * Esegue una chiamata API e gestisce la risposta.
 * 
 * @param {string} endpoint - Il percorso dell'endpoint API.
 * @param {Object} data - I dati da inviare con la richiesta.
 * @param {Function} [callback=null] - La funzione di callback da eseguire al successo.
 * @param {string} [type='GET'] - Il metodo HTTP da utilizzare (es. GET, POST).
 * @param {string} [dataType='json'] - Il tipo di dati attesi nella risposta.
 */
function apiCall(endpoint, data, callback = null, type = 'GET', dataType = 'json') {

	data.lang = lang;

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

	if (type !== 'GET' && !(!data)) {
		settings.data = data; // Aggiunge i dati al corpo della richiesta per POST, PUT, DELETE, ecc.
	}

	$.ajax(settings);
}

/**
 * Gestisce gli errori delle chiamate API.
 * 
 * @param {Object} xhr - L'oggetto XMLHttpRequest della chiamata fallita.
 * @param {string} status - Lo stato testuale dell'errore (es. "error", "timeout").
 * @param {string} error - Il testo dell'errore.
 */
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

/**
 * Gestisce le risposte di successo delle chiamate API.
 * 
 * @param {Object} response - L'oggetto risposta ricevuto dall'API.
 * @param {Function} [callback=null] - La funzione di callback da eseguire al successo.
 */
function genericSuccess(response, callback) {
	if (response.status === 'success') {
		SweetAlert.fire('Ottimo!', response.message, 'success').then(() => {
			if (typeof callback === "function") {
				callback();
			}
		});
	} else if (response.status === 'error') {
		SweetAlert.fire('Errore', response.message, 'error');
	} else {
		if (typeof callback === "function") {
			callback(response);
		}
	}
}

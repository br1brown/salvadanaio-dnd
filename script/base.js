// Aggrega tutte le dipendenze in una singola Promessa
var inizializzazioneApp = Promise.all([
	traduzioneCaricata,
	new Promise(resolve => $(document).ready(resolve))
]);


/**
 * Funzione di inizializzazione eseguita al caricamento completo del DOM.
 */
inizializzazioneApp.then(() => {

	/**
	 * Gestisce l'evento di scorrimento della finestra.
	 */
	$(window).scroll(function () {
		// Controlla se lo scroll supera i 50 pixel dall'alto della pagina
		if ($(this).scrollTop() > 50) {
			$('#back-to-top').fadeIn(); // Mostra il pulsante 'back-to-top'
		} else {
			$('#back-to-top').fadeOut(); // Nasconde il pulsante
		}
	});

	/**
	 * Gestisce il click sul pulsante 'back-to-top'.
	 */
	$('#back-to-top').click(function () {
		// Anima lo scroll verso l'alto della pagina
		$('body,html').animate({
			scrollTop: 0
		}, 400); // Durata dell'animazione: 400 millisecondi
		return false;
	});

	/**
	 * Imposta l'effetto fumo su un elemento canvas specificato.
	 */
	var fumo = $('#smoke-effect-canvas');
	if (fumo.length)
		fumo.SmokeEffect({
			color: fumo.data('color'), // Colore del fumo
			opacity: fumo.data('opacity'), // Opacità del fumo
			maximumVelocity: fumo.data('maximumVelocity'), // Velocità massima delle particelle
			particleRadius: fumo.data('particleRadius'), // Raggio delle particelle di fumo
			density: fumo.data('density') // Densità del fumo
		});

	/**
	 * Gestisce lo scorrimento orizzontale su elementi con classe 'horizontal-scroll'.
	 */
	$('.horizontal-scroll').on('wheel', function (event) {
		event.preventDefault(); // Previene lo scorrimento verticale predefinito
		this.scrollLeft += event.originalEvent.deltaY + event.originalEvent.deltaX;
	});

});

/**
 * Apre un link codificato.
 * 
 * @param {string} prefix - Prefisso da aggiungere alla stringa decodificata.
 * @param {string} encodedStr - Stringa codificata da decodificare e aprire come link.
 */
function openEncodedLink(prefix, encodedStr) {
	var decodedString = encodedStr;
	var url = "";

	// Costruisce l'URL completo
	if (prefix) {
		url = prefix + decodedString;
	} else {
		url = decodedString;
	}

	// Reindirizza alla nuova URL
	window.location.href = url;
}

/**
 * Imposta lo sfondo della pagina con il colore medio di un'immagine.
 * 
 * @param {string} src - Percorso dell'immagine da cui estrarre il colore medio.
 */
function set_background_with_average_rgb(src) {
	var canvas = document.createElement('canvas');
	var context = canvas.getContext('2d');
	var img = new Image();

	img.crossOrigin = 'Anonymous';

	img.onload = function () {
		context.drawImage(img, 0, 0, 1, 1);
		var data = context.getImageData(0, 0, 1, 1).data;
		var colorStr = 'rgb(' + data[0] + ',' + data[1] + ',' + data[2] + ')';
		document.body.style.backgroundColor = colorStr;
	};

	img.onerror = function () {
		// Gestione dell'errore
	};

	img.src = src;
}

/**
 * Copia un testo specificato nella clipboard del sistema.
 * 
 * Utilizza l'API Clipboard di navigator, se disponibile e il contesto è sicuro (https).
 * In caso contrario, ricorre a un metodo alternativo creando un'area di testo temporanea.
 * 
 * @param {string} testoDaCopiare - Testo da copiare nella clipboard.
 * @param {string} idElemento - ID dell'elemento da cui ottenere il testo (usato nel metodo alternativo).
 * @returns {Promise} - Ritorna una Promise che risolve se la copia è riuscita, altrimenti la rifiuta.
 * 
 * Esempio di utilizzo:
 * copyToClipboard('Testo da copiare').then(() => {
 *     console.log('Testo copiato con successo!');
 * }).catch(() => {
 *     console.error('Errore nella copia del testo.');
 * });
 */
function copyToClipboard(testoDaCopiare) {
	// Controlla se l'API Clipboard di navigator è disponibile e se il contesto è sicuro (https)
	if (navigator.clipboard && window.isSecureContext) {
		// Usa il metodo writeText dell'API Clipboard di navigator per copiare il testo
		return navigator.clipboard.writeText(testoDaCopiare);
	} else {
		// Metodo alternativo creando un elemento input temporaneo
		let tempInput = document.createElement("input");
		tempInput.style.position = "absolute";
		tempInput.style.left = "-9999px";
		tempInput.value = testoDaCopiare;
		document.body.appendChild(tempInput);
		tempInput.select();

		// Crea una nuova Promise per gestire la copia
		return new Promise((resolve, reject) => {
			// Esegue il comando di copia e risolve o rifiuta la Promise in base al risultato
			if (document.execCommand('copy')) {
				resolve();
			} else {
				reject();
			}
			// Rimuove l'elemento temporaneo dal DOM
			document.body.removeChild(tempInput);
		});
	}
}

/**
 * Gestisce l'animazione e lo stato di un oggetto durante un periodo di tempo definito.
 * 
 * @param {Object} myobj - Selettore jQuery per identificare l'oggetto.
 * @param {number} durata - Durata dell'animazione in millisecondi.
 */
function disattivaper(myobj, durata) {
	// Funzione per aggiungere lo stile di caricamento e disabilitare l'oggetto
	function iniziaCaricamento() {
		myobj.prop('disabled', true).addClass('obj-loading');
	}

	// Funzione per aggiornare lo sfondo del oggetto
	function updateProgress(value) {
		var percentage = (value / durata) * 100;
		myobj.css('background-size', percentage + '% 100%');
	}

	// Funzione per terminare il caricamento e riabilitare l'oggetto
	function terminaCaricamento() {
		myobj.prop('disabled', false).removeClass('obj-loading');
	}

	// Inizia l'animazione
	iniziaCaricamento();

	// Imposta un timer per riattivare l'oggetto dopo la durata specificata e aggiornare il progresso
	var startTime = Date.now();
	var interval = setInterval(function () {
		var elapsedTime = Date.now() - startTime;
		updateProgress(elapsedTime);

		if (elapsedTime >= durata) {
			clearInterval(interval);
			terminaCaricamento();
		}
	}, 100);
}

/**
 * Setta ala linga dell'applicativo
 * @param {string} lang - codice lingua
 */
function setLanguage(lang) {
	let searchParams = new URLSearchParams(window.location.search);
	searchParams.set('lang', lang); // Imposta o aggiorna il parametro 'lang'

	// Costruisce l'URL con i parametri aggiornati
	let newUrl = window.location.pathname + '?' + searchParams.toString() + window.location.hash;
	window.location.href = newUrl; // Reindirizza l'utente all'URL aggiornato
}
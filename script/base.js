/**
 * Funzione di inizializzazione eseguita al caricamento completo del DOM.
 */
$(document).ready(function () {
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

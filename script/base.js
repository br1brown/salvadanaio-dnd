// Attende che il DOM sia completamente caricato
$(document).ready(function () {
	// Gestisce l'evento di scorrimento della finestra
	$(window).scroll(function () {
		// Controlla se lo scroll supera i 50 pixel
		if ($(this).scrollTop() > 50) {
			$('#back-to-top').fadeIn(); // Mostra il pulsante 'back-to-top'
		} else {
			$('#back-to-top').fadeOut(); // Nasconde il pulsante 'back-to-top'
		}
	});

	// Gestisce il click sul pulsante 'back-to-top'
	$('#back-to-top').click(function () {
		// Anima lo scroll verso l'alto della pagina
		$('body,html').animate({
			scrollTop: 0
		}, 400); // 400 millisecondi per l'animazione
		return false;
	});

	// Imposta l'effetto fumo su un elemento canvas
	var fumo = $('#smoke-effect-canvas');
	fumo.SmokeEffect({
		color: fumo.data('color'), // Colore del fumo
		opacity: fumo.data('opacity'), // Opacità del fumo
		maximumVelocity: fumo.data('maximumVelocity'), // Velocità massima delle particelle
		particleRadius: fumo.data('particleRadius'), // Raggio delle particelle di fumo
		density: fumo.data('density') // Densità del fumo
	});
});

// Funzione per aprire un link codificato
function openEncodedLink(prefix, encodedStr) {
	var decodedString = encodedStr; // Decodifica la stringa (assumendo che il browser gestisca l'encoding)
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

// Funzione per impostare lo sfondo con il colore medio di un'immagine
function set_background_with_average_rgb(src) {
	var canvas = document.createElement('canvas');
	var context = canvas.getContext('2d');
	var img = new Image();

	// Imposta crossOrigin per evitare problemi di CORS con immagini esterne
	img.crossOrigin = 'Anonymous';

	img.onload = function () {
		// Disegna l'immagine nel canvas a dimensioni ridotte
		context.drawImage(img, 0, 0, 1, 1);
		var data = context.getImageData(0, 0, 1, 1).data;

		// Calcola il colore medio e lo imposta come sfondo del body
		var colorStr = 'rgb(' + data[0] + ',' + data[1] + ',' + data[2] + ')';
		document.body.style.backgroundColor = colorStr;
	};

	img.onerror = function () {
		// Gestione dell'errore di caricamento immagine
	};

	img.src = src; // Imposta la sorgente dell'immagine
}

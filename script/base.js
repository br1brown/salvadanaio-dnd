//https://sweetalert2.github.io/
$(document).ready(function () {
	$(".bottone").click(function () {
		$(this).blur();
		var val = $(this).val()
		var tipo = $(this).data("type");
		if (tipo && tipo != "")
			swal.fire(tipo + ": Sono io", val, tipo);
		else
			swal.fire("Sono io di Br1Brown", val);
	});


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

	var fumo = $('#smoke-effect-canvas');
	fumo.SmokeEffect({
		color: fumo.data('color'),
		opacity: fumo.data('opacity'),
		maximumVelocity: fumo.data('maximumVelocity'),
		particleRadius: fumo.data('particleRadius'),
		density: fumo.data('density')
	});


});

function handleError(xhr, status, error) {
	SweetAlert.fire('Errore ' + xhr.status, xhr.responseText, 'error');
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

function getApiMethod(action, metod, params = null) {
	return APIEndPoint + "/" + action + "/" + metod + MakeGetQueryString(params);
}
function getApiUrl(action, params = null) {
	return APIEndPoint + "/" + action + MakeGetQueryString(params);
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

function set_background_with_average_rgb(src) {
	var canvas = document.createElement('canvas');
	var context = canvas.getContext('2d');
	var img = new Image();

	// Setto crossOrigin per evitare problemi di CORS su immagini da domini esterni.
	img.crossOrigin = 'Anonymous';

	img.onload = function () {
		// Disegno l'immagine nel canvas. La dimensione 1x1 è sufficiente per il calcolo medio del colore.
		context.drawImage(img, 0, 0, 1, 1);
		var data = context.getImageData(0, 0, 1, 1).data;

		// Calcolo il colore medio e lo imposto come sfondo della pagina.
		var colorStr = 'rgb(' + data[0] + ',' + data[1] + ',' + data[2] + ')';
		document.body.style.backgroundColor = colorStr;
	};

	img.onerror = function () {
	};

	img.src = src;
}

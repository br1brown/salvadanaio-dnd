const FontEnum = {
	ARIAL: 'Arial',
	VERDANA: 'Verdana',
	HELVETICA: 'Helvetica',
	TAHOMA: 'Tahoma',
	TREBUCHET_MS: 'Trebuchet MS',
	TIMES_NEW_ROMAN: 'Times New Roman',
	GEORGIA: 'Georgia',
	GARAMOND: 'Garamond',
	COURIER_NEW: 'Courier New',
	BRUSH_SCRIPT_MT: 'Brush Script MT',
	IMPACT: 'Impact',
	COMIC_SANS_MS: 'Comic Sans MS',
	CALIBRI: 'Calibri',
	CAMBRIA: 'Cambria',
	CANDARA: 'Candara',
	CONSOLAS: 'Consolas',
	CONSTANTIA: 'Constantia',
	CORBEL: 'Corbel',
	FRANKLIN_GOTHIC_MEDIUM: 'Franklin Gothic Medium',
	FUTURA: 'Futura',
	GILL_SANS: 'Gill Sans',
	LUCIDA_GRANDE: 'Lucida Grande',
	LUCIDA_SANS_UNICODE: 'Lucida Sans Unicode',
	MICROSOFT_SANS_SERIF: 'Microsoft Sans Serif',
	PALATINO_LINOTYPE: 'Palatino Linotype',
	SEGOE_UI: 'Segoe UI',
	ROBOTO: 'Roboto',
	OXYGEN: 'Oxygen',
	UBUNTU: 'Ubuntu',
	CANTARELL: 'Cantarell',
	FIRA_SANS: 'Fira Sans',
	DROID_SANS: 'Droid Sans',
	LATO: 'Lato',
	MONTSERRAT: 'Montserrat',
	RALEWAY: 'Raleway',
	PT_SANS: 'PT Sans',
	SOURCE_SANS_PRO: 'Source Sans Pro',
	OPEN_SANS: 'Open Sans',
	LORA: 'Lora',
	AVENIR: 'Avenir',
	NOTO_SANS: 'Noto Sans',
	BASKERVILLE: 'Baskerville',
	PLAYFAIR_DISPLAY: 'Playfair Display',
	MERRIWEATHER: 'Merriweather',
	LOBSTER: 'Lobster',
	POPPINS: 'Poppins',
	ARVO: 'Arvo',
	VARELA_ROUND: 'Varela Round',
	SPECTRAL: 'Spectral',
	RUBIK: 'Rubik',
	MULI: 'Muli',
	OLD_STANDARD_TT: 'Old Standard TT',
	ABRIL_FATFACE: 'Abril Fatface',
	JOSEFIN_SANS: 'Josefin Sans',
	NUNITO: 'Nunito',
	MONOSPACE: 'Monospace'
};


class CreaImmagine {

	constructor(testo, fillStyle, txtcolor) {
		if (typeof testo !== 'string') {
			throw new Error('Il testo deve essere una stringa');
		}
		this.testo = testo;
		this.setColoreTesto(txtcolor);
		this.setfillStyleSfondo(fillStyle);
		this.setFontsize(50);
		this.setFont(FontEnum.VERDANA);
		this.setMargine(50);
		this.setLarghezza(900);
	}


	setColoreTesto(colore) {
		if (!this.#is_Color(colore)) {
			throw new Error('Il colore del testo non è valido');
		}
		this.coloreTesto = colore;
		return this;
	}

	setfillStyleSfondo(fillStyle) {
		this.fillStyleSfondo = fillStyle;
		return this;
	}

	setFontsize(fontsize) {
		if (typeof fontsize !== 'number' || fontsize <= 0) {
			throw new Error('La dimensione del font deve essere un numero positivo');
		}
		this.fontsize = fontsize;
		return this;
	}

	setFont(font) {
		if (!this.#is_Font(font)) {
			throw new Error('Il font specificato non è valido');
		}
		this.font = font;
		return this;
	}

	setMargine(margine) {
		if (typeof margine !== 'number' || margine < 0) {
			throw new Error('Il margine deve essere un numero non negativo');
		}
		this.margine = margine;
		return this;
	}

	setLarghezza(larghezza) {
		if (typeof larghezza !== 'number' || larghezza <= 0) {
			throw new Error('La larghezza deve essere un numero positivo');
		}
		this.larghezza = larghezza;
		return this;
	}

	costruisci() {
		return new Img_txtCenter(
			this.testo,
			this.coloreTesto,
			this.fillStyleSfondo,
			this.fontsize,
			this.font,
			this.margine,
			this.larghezza,
		);
	}

	#is_Color(color) {
		const reg = [/^#(?:[0-9a-fA-F]{3}){1,2}$/];
		reg.forEach((r) => {
			if (r.test(color)) {
				return true;
			}
		});

		return typeof color === 'string' && color.trim() !== '';
	}

	#is_Font(font) {
		if (!(typeof font === 'string' && font.trim() !== ''))
			return false;

		const fontParts = font.split(',');

		return fontParts.some(fp => Object.values(FontEnum).includes(fp.trim()));
	}
}




class Img_txtCenter {

	constructor(
		testo,
		coloreTesto,
		fillStyleSfondo,
		fontsize,
		font,
		margine,
		larghezza,
	) {
		var dimensioneTesto = fontsize;
		var altezzaMinima = parseInt(larghezza * 3 / 4);
		this.larghezza = larghezza;
		this.margine = margine;

		this.tela = document.createElement('canvas');
		this.ctx = this.tela.getContext('2d');
		this.ctx.font = fontsize + "px " + font;
		this.ctx.textAlign = 'center';
		this.ctx.textBaseline = 'middle';

		var righe = this.#splitTextIntoLines(testo);

		this.tela.width = this.larghezza;
		this.tela.height = (this.margine * 2) + Math.max((righe.length * dimensioneTesto), altezzaMinima);
		this.ctx.fillStyle = fillStyleSfondo;
		this.ctx.fillRect(0, 0, this.tela.width, this.tela.height);
		this.ctx.fillStyle = coloreTesto;

		let y = (this.tela.height - righe.length * dimensioneTesto) / 2 + dimensioneTesto / 2;
		righe.forEach((riga) => {
			this.ctx.font = fontsize + "px " + font;
			this.ctx.fillText(riga, margine, y);
			y += (dimensioneTesto);
		});
	}

	#splitTextIntoLines(testo) {
		var paragrafo = testo.split('\n');
		var righe = [];

		paragrafo.forEach((p) => {
			var parole = p.split(' ');
			let riga = '';

			parole.forEach((parola) => {
				let rigaTest = riga + parola + ' ';
				let misura = this.ctx.measureText(rigaTest);
				if (misura.width > this.larghezza - (this.margine * 2) && riga !== '') {
					righe.push(riga.trim());
					riga = parola + ' ';
				} else {
					riga = rigaTest;
				}
			});

			if (riga) {
				righe.push(riga.trim());
			}
		});

		return righe;
	}


	/**
	 * Questa funzione restituisce l'URL dell'immagine creata
	 */
	urlImmagine() {
		return this.tela.toDataURL("image/png");
	}

	/**
	 * Questa funzione permette di scaricare l'immagine
	 */
	scaricaImmagine() {
		var link = document.createElement('a');
		link.download = 'immagine.png';
		link.href = this.urlImmagine();
		link.click();
	}
}
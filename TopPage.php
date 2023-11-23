<!doctype html>
<html lang="it">
<head>
	<?php
	// Leggi il contenuto del file settingsFE.json
	$settingsJson = file_get_contents('settingsFE.json');
	// Decodifica il JSON in un array associativo
	$settings = json_decode($settingsJson, true);
	?>
	<title><?php echo $title ?></title>

	<!-- Definizione della codifica dei caratteri per la pagina -->
	<meta charset="UTF-8">

	<!-- Impostazioni per il responsive design e il controllo della visualizzazione su dispositivi mobili -->
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!--shrink-to-fit=no-->

	<!-- Informazioni sull'autore del sito -->
	<meta name="author" content="Br1Brown">
    
	<!-- ROBE PER IL MENU + SOCIAL anche quella prima per delle altre icone -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- jquery -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<!-- bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

	<!-- GLI ALERT -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

	<!-- Optional: include a polyfill for ES6 Promises for IE11 -->
	<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>

	<!-- ROBA NOSTRA -->
	<link rel="stylesheet" href="style/base.css">
	<link rel="stylesheet" href="style/manage_img.css">
	<link rel="stylesheet" href="style/social.css">
	<script src="script/base.js"></script>
	<!-- SFONDO CON LE NUVOLE -->
	<script src="script/jquery_bloodforge_smoke_effect.js"></script>
</head>

<style>
        <?php if (!$settings['filtri']) : ?>
            .filtri { display: none !important; }
        <?php endif; ?>

        <?php if (!$settings['aggiuntaPersonaggio']) : ?>
            .addCharacterBtn { display: none !important; }
        <?php endif; ?>

        <?php if (!$settings['links']) : ?>
            .item_Link, #addLink { display: none !important; }
        <?php endif; ?>

        <?php if (!$settings['eliminaPersonaggio']) : ?>
            .btnEliminaPersonaggi { display: none !important; }
        <?php endif; ?>
		
        <?php if (!$settings['inventario']) : ?>
            .inventory-items { display: none !important; }
        <?php endif; ?>
		
        <?php if (!$settings['debiti']) : ?>
            .debiti { display: none !important; }
        <?php endif; ?>
		
        <?php if (!$settings['propic']) : ?>
            .propic { display: none !important;}
        <?php endif; ?>
    </style>

<body>	
<a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button">
	<i class="fas fa-chevron-up"></i>
</a>
<canvas id="smoke-effect-canvas"
		style="width:100%; height:100%; position: fixed;top: 0; left: 0; z-index: -100;"></canvas>
<!-- qui comincia l'html diverso per tutti -->

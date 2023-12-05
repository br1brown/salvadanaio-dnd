<!doctype html>
<html lang="it">
<?php
$codes = array(
    400 => 'Richiesta Errata',
    401 => 'Non Autorizzato',
    402 => 'Pagamento Richiesto',
    403 => 'Vietato',
    404 => 'Non Trovato',
    405 => 'Metodo Non Permesso',
    406 => 'Non Accettabile',
    407 => 'Autenticazione Proxy Richiesta',
    408 => 'Richiesta Timeout',
    409 => 'Conflitto',
    410 => 'Andato',
    411 => 'Lunghezza Richiesta',
    412 => 'Precondizione Fallita',
    413 => 'Payload Troppo Grande',
    414 => 'URI Troppo Lungo',
    416 => 'Range Non Soddisfacibile',
    417 => 'Aspettativa Fallita',
    418 => 'Sono una teiera', // Nota: questo è un codice scherzoso definito nell'RFC 2324
    421 => 'Richiesta Mal Diretta',
    422 => 'Entità Non Elaborabile',
    423 => 'Bloccato',
    424 => 'Dipendenza Fallita',
    425 => 'Troppo Presto',
    426 => 'Upgrade Richiesto',
    428 => 'Precondizione Richiesta',
    429 => 'Troppe Richieste',
    431 => 'Campi Intestazione Richiesta Troppo Grandi',
    451 => 'Non Disponibile Per Ragioni Legali',
    500 => 'Errore Interno del Server',
    501 => 'Non Implementato',
    502 => 'Bad Gateway',
    503 => 'Servizio Non Disponibile',
    504 => 'Gateway Timeout',
    505 => 'Versione HTTP Non Supportata',
    506 => 'Variante Anche Tratta',
    507 => 'Memoria Insufficiente',
    508 => 'Rilevato Loop',
    511 => 'Autenticazione di Rete Richiesta',
);

?>
<?php 
$title = "Errore";
?>
<?php include('TopPage.php'); ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-2">
				<a href="index" class="btn btn-warning btn-sm w-100">Home</a>
			</div>
			<div id=contenuto class="col-12 col-md-8 text-center tutto">
				<?php
					$code = $_SERVER['REDIRECT_STATUS'];
					$source_url = 'http'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					if (array_key_exists($code, $codes) && is_numeric($code)) {
						echo "<h2>Error $code: {$codes[$code]}</h2>";
						echo "<small>".$source_url."</small>";
					} else {
						echo 'Errore generico';
					}
				?>
			</div>
	</div>
    </div>
</body>
<script>
	$(document).ready(function () {


	});

</script>

</html>
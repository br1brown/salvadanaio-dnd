<!doctype html>
<html lang="it">
<?php
$codes = array(
    400 => array(
        'title' => 'Richiesta Errata',
        'description' => 'La sintassi della richiesta non è corretta. Controlla la formattazione della tua richiesta.'
    ),
    401 => array(
        'title' => 'Non Autorizzato',
        'description' => 'Devi autenticarti per accedere a questa risorsa.'
    ),
    402 => array(
        'title' => 'Pagamento Richiesto',
        'description' => 'L\'accesso alla risorsa richiede un pagamento.'
    ),
    403 => array(
        'title' => 'Vietato',
        'description' => 'Non hai i permessi necessari per accedere a questa risorsa.'
    ),
    404 => array(
        'title' => 'Non Trovato',
        'description' => 'La risorsa richiesta non è stata trovata sul server.'
    ),
    405 => array(
        'title' => 'Metodo Non Permesso',
        'description' => 'Il metodo HTTP usato non è consentito per la risorsa richiesta.'
    ),
    406 => array(
        'title' => 'Non Accettabile',
        'description' => 'Il server non può generare una risposta compatibile con i criteri della richiesta.'
    ),
    407 => array(
        'title' => 'Autenticazione Proxy Richiesta',
        'description' => 'Devi autenticarti con il proxy per procedere.'
    ),
    408 => array(
        'title' => 'Richiesta Timeout',
        'description' => 'Il server ha impiegato troppo tempo per rispondere. Riprova.'
    ),
    409 => array(
        'title' => 'Conflitto',
        'description' => 'La richiesta non può essere completata a causa di un conflitto.'
    ),
        // Continuazione degli errori HTTP
    410 => array(
        'title' => 'Andato',
        'description' => 'La risorsa richiesta non è più disponibile e non ci sono indirizzi alternativi.'
    ),
    411 => array(
        'title' => 'Lunghezza Richiesta',
        'description' => 'Il server rifiuta la richiesta perché non è specificata la lunghezza del contenuto.'
    ),
    412 => array(
        'title' => 'Precondizione Fallita',
        'description' => 'Una o più condizioni nella richiesta header non sono state soddisfatte.'
    ),
    413 => array(
        'title' => 'Payload Troppo Grande',
        'description' => 'La dimensione della richiesta è maggiore di quanto il server è disposto a gestire.'
    ),
    414 => array(
        'title' => 'URI Troppo Lungo',
        'description' => 'L\'URI richiesto è troppo lungo per essere elaborato dal server.'
    ),
    416 => array(
        'title' => 'Range Non Soddisfacibile',
        'description' => 'Il client ha richiesto una parte del file, ma il server non può fornire quella parte.'
    ),
    417 => array(
        'title' => 'Aspettativa Fallita',
        'description' => 'Il server non può soddisfare i requisiti del campo di aspettativa della richiesta.'
    ),
    421 => array(
        'title' => 'Richiesta Mal Diretta',
        'description' => 'La richiesta è stata indirizzata a un server che non è in grado di produrre una risposta.'
    ),
    422 => array(
        'title' => 'Entità Non Elaborabile',
        'description' => 'La richiesta è ben formata ma impossibile da seguire a causa di errori semantici.'
    ),
    423 => array(
        'title' => 'Bloccato',
        'description' => 'La risorsa a cui si sta cercando di accedere è bloccata.'
    ),
    424 => array(
        'title' => 'Dipendenza Fallita',
        'description' => 'La richiesta fallisce a causa del fallimento di una richiesta precedente.'
    ),
    425 => array(
        'title' => 'Troppo Presto',
        'description' => 'Indica che il server non è disposto a rischiare di elaborare una richiesta che potrebbe essere riprodotta.'
    ),
    426 => array(
        'title' => 'Upgrade Richiesto',
        'description' => 'Il client dovrebbe cambiare protocollo, ad esempio aggiornando a TLS/1.0.'
    ),
    428 => array(
        'title' => 'Precondizione Richiesta',
        'description' => 'Il server richiede che la richiesta del client sia condizionale.'
    ),
    429 => array(
        'title' => 'Troppe Richieste',
        'description' => 'Hai inviato troppe richieste in un dato periodo di tempo.'
    ),
    431 => array(
        'title' => 'Campi Intestazione Richiesta Troppo Grandi',
        'description' => 'Il server non può elaborare la richiesta perché uno o più campi dell\'intestazione della richiesta sono troppo grandi.'
    ),
    451 => array(
        'title' => 'Non Disponibile Per Ragioni Legali',
        'description' => 'L\'accesso alla risorsa è bloccato per motivi legali.'
    ),
    500 => array(
        'title' => 'Errore Interno del Server',
        'description' => 'Il server ha incontrato un errore interno e non può completare la tua richiesta.'
    ),
    501 => array(
        'title' => 'Non Implementato',
        'description' => 'Il server non supporta la funzionalità richiesta per soddisfare la richiesta.'
    ),
    502 => array(
        'title' => 'Bad Gateway',
        'description' => 'Il server ha ricevuto una risposta non valida dal server upstream.'
    ),
    503 => array(
        'title' => 'Servizio Non Disponibile',
        'description' => 'Il server non è al momento disponibile, solitamente a causa di sovraccarico o manutenzione.'
    ),
    504 => array(
        'title' => 'Gateway Timeout',
        'description' => 'Il server ha agito come un gateway o un proxy e non ha ricevuto una risposta in tempo.'
    ),
    505 => array(
        'title' => 'Versione HTTP Non Supportata',
        'description' => 'La versione del protocollo HTTP utilizzata nella richiesta non è supportata dal server.'
    ),
    506 => array(
        'title' => 'Variante Anche Tratta',
        'description' => 'La variante della negoziazione trasparente del contenuto richiesta non è configurata.'
    ),
    507 => array(
        'title' => 'Memoria Insufficiente',
        'description' => 'Il server non è in grado di archiviare la rappresentazione necessaria per completare la richiesta.'
    ),
    508 => array(
        'title' => 'Rilevato Loop',
        'description' => 'Il server ha rilevato un loop infinito durante l\'elaborazione della richiesta.'
    ),
    511 => array(
        'title' => 'Autenticazione di Rete Richiesta',
        'description' => 'È necessaria l\'autenticazione per accedere alla rete.'
    )
);
$code = $_SERVER['REDIRECT_STATUS'];
$title = "Errore " . $code. " - ". $codes[$code]['title'];
$forceMenu = false;
?>
<?php include('FE_utils/TopPage.php'); ?>
		<div class="row">
			<div id=contenuto class="col-12 offset-md-2 col-md-8 text-center tutto">
				<?php
					$source_url = 'http'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					if (array_key_exists($code, $codes) && is_numeric($code)) {
						echo "<h2>Errore $code</h2>";
						echo "<small>{$codes[$code]['title']} <i>".$source_url."</i></small>";
						echo "<p>{$codes[$code]['description']}</p>";
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
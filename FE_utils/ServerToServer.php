<?php

class URLResponse
{
    public mixed $Response;
    public string $ResponseContentType;
    public function __construct(mixed $Response, string $ResponseContentType)
    {
        $this->Response = $Response;
        $this->ResponseContentType = $ResponseContentType;
    }
}

class ServerToServer
{
    /**
     * Esegue una chiamata all'endpoint dell'API utilizzando il metodo HTTP specificato e restituisce la risposta.
     * 
     * @param string $pathOrEndpoint Il percorso dell'endpoint o interno dell'API.
     * @param string $metodo Il metodo HTTP da utilizzare per la chiamata (ad es. 'GET', 'POST', 'PUT', 'DELETE', 'PATCH'). Di default è 'GET'.
     * @param array $dati I dati da inviare con la richiesta, utili per i metodi come 'POST', 'PUT'.
     * @param string $contentType Il Content Type della richiesta, di default è 'application/json'.
     * @param array $headerPersonalizzati Header HTTP personalizzati da includere nella richiesta.
     * @param int $timeoutTotale Il timeout totale per la richiesta in secondi. Di default è 30 secondi.
     * @param int $timeoutConnessione Il timeout per la connessione in secondi. Di default è 10 secondi.
     * @param bool $CheckSSL controllo gli SSL dell' endpoint?
     * @return URLResponse Risposta dell'API
     * @throws InvalidArgumentException Se i parametri obbligatori non sono validi.
     * @throws Exception In caso di errore nella chiamata all'endpoint o nella risposta dell'API.
     */
    public static function callURL(string $url, string $metodo = "GET", array $dati = [], string $contentType = 'application/json', array $headerPersonalizzati = [], int $timeoutTotale = 30, int $timeoutConnessione = 10, bool $CheckSSL = true)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("L'URL fornito non è valido");
        }

        if (strtoupper($metodo) === "GET" && !empty($dati)) {
            $url .= '?' . http_build_query($dati);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeoutTotale); // Timeout totale
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeoutConnessione); // Timeout di connessione


        if (!$CheckSSL) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }
        // Inizializza gli header con 'Content-Type' e 'X-Api-Key'
        $header = [
            "Content-Type: $contentType",
        ];
        // Aggiungi gli header personalizzati agli header di default
        $header = array_merge($header, $headerPersonalizzati);
        // Imposta gli header HTTP
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // Imposta CURLOPT_RETURNTRANSFER per ottenere il risultato come stringa
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Imposta il metodo HTTP e i dati
        switch (strtoupper($metodo)) {
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
            // Intenzionalmente nessun break qui per permettere il fall-through
            case "PUT":
            case "PATCH":
            case "DELETE":
                // Per PUT e DELETE, se non specificato diversamente sopra
                if ($metodo !== "POST") {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
                }
                if (!empty($dati)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dati));
                }
                break;
        }

        // Esegui la chiamata cURL
        $response = curl_exec($ch);
        $ret = new URLResponse($response, curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
        // Controlla se ci sono stati errori nella chiamata cURL
        if ($response === false) {
            $errorCode = curl_errno($ch);
            $error = curl_error($ch);
            curl_close($ch);

            // Controlla se si tratta di un timeout
            if ($errorCode === CURLE_OPERATION_TIMEDOUT) {
                throw new Exception("Timeout della richiesta raggiunto: " . $error);
            }

            // Gestisci altri errori di connessione
            throw new Exception("Errore EndPoint: " . $error);
        }

        // Ottieni il codice di risposta HTTP
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!($httpCode >= 200 && $httpCode < 300)) {
            curl_close($ch);
            throw new Exception("Errore HTTP: " . $httpCode);
        }
        // Chiudi la sessione cURL
        curl_close($ch);

        return $ret;
    }

}

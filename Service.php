<?php
require_once __DIR__.'/parsedown-1.7.4/Parsedown.php';
class Service {

    /**
     * @var Parsedown il parser
     */
    private Parsedown $Parser;
    /**
     * Il parser
     *
     * @return Parsedown Il parser.
     */
    public function getparser() : Parsedown {
        if (!isset($this->Parser))
          $this->Parser =   (new Parsedown());
        return $this->Parser;
    }

    /**
     * @var string le keyword in una stringa
     */
    public string $keywords = "";

    /**
     * @var array Impostazioni dell'applicativo
     */
    private array $settings;

    /**
     * @var array Chiavi da escludere dalle impostazioni quando richiesto.
     */
    private $excludeKeys = ['APIEndPoint','keywords'];
    /**
     * Restituisce le impostazioni dell'applicativo necessarie
     *
     * @return array Impostazioni filtrate.
     */
    public function getSettings() {
        $data = array_filter($this->settings, function($key) {
            return !in_array($key, $this->excludeKeys);
        }, ARRAY_FILTER_USE_KEY);

        $data['meta']['keywords'] = $this->keywords;

        return $data; 
    }

    /**
     * @var string URL dell'API di servizio
     */
    public string $urlAPI;

    /**
     * @var string URL dell'Host
     */
    private string $baseUrl;

    /**
     * Costruttore della classe Service.
     * Legge le impostazioni dal file JSON e inizializza l'URL dell'API.
     */
  public function __construct()
  {
      // Controllo prima la variabile $_SERVER['HTTPS']
      $protocol = 'http'; // Impostazione predefinita a 'http'
      if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
          $protocol = 'https'; // Se 'HTTPS' è attivo, usa 'https'
      } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
          $protocol = 'https'; // Se 'HTTP_X_FORWARDED_PROTO' è 'https', usa 'https'
      }

      // Costruzione dell'URL
      $this->baseUrl = $protocol . "://$_SERVER[HTTP_HOST]" . dirname($_SERVER['PHP_SELF']) . "/";
                          
      $this->settings = json_decode(file_get_contents('websettings.json'), true);
      

      foreach ($this->settings["keywords"] as $keyword) {
          $this->keywords .= trim($keyword) . ",";
      }
      $this->keywords = rtrim($this->keywords, ",");


      $APIEndPoint = $this->settings['APIEndPoint'];
      if (strpos($APIEndPoint, "http://") === 0 || strpos($APIEndPoint, "https://") === 0) {
          $this->urlAPI = $APIEndPoint;
      } else {
          $this->urlAPI = $this->baseUrl.$APIEndPoint;
      }
  }

  /**
   * Restituisce il percorso completo dell'URL per una risorsa nelle API.
   * 
   * @param string $path Percorso della risorsa.
   * @return string URL completo della risorsa.
   */
  public function APIbaseURL($path) {
      if (strpos($path, "http://") === 0 || strpos($path, "https://") === 0) {
          return $path;
      } else {
          return rtrim($this->urlAPI, '/') . '/' . $path;
      }
  }

  /**
   * Restituisce il percorso completo dell'URL per una risorsa.
   * 
   * @param string $path Percorso della risorsa.
   * @return string URL completo della risorsa.
   */
  public function baseURL($path) {
      if (strpos($path, "http://") === 0 || strpos($path, "https://") === 0) {
          return $path;
      } else { 
          return rtrim($this->baseUrl, '/') . '/' . $path;
      }
  }

  /**
   * @var bool controllo gli SSL dell' endpoint?
   */
  public bool $CheckSSL = false;

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
   * @return array Risposta dell'API decodificata in formato array.
   * @throws InvalidArgumentException Se i parametri obbligatori non sono validi.
   * @throws Exception In caso di errore nella chiamata all'endpoint o nella risposta dell'API.
   */
  public function callApiEndpoint(string $pathOrEndpoint, string $metodo = "GET", array $dati = [], string $contentType = 'application/json', array $headerPersonalizzati = [], int $timeoutTotale = 30, int $timeoutConnessione = 10) {
    // Validazione del parametro $pathOrEndpoint
    if (empty($pathOrEndpoint)) {
        throw new InvalidArgumentException("Il parametro 'pathOrEndpoint' non può essere vuoto.");
    }

    // Validazione del parametro $metodo
    $metodiValidi = ['GET', 'POST'];
    if (!in_array(strtoupper($metodo), $metodiValidi)) {
        throw new InvalidArgumentException("Metodo HTTP non supportato: " . $metodo);
    }

    $url = $this->APIbaseURL($pathOrEndpoint);

    if (strtoupper($metodo) === "GET" && !empty($dati)) {
        $url .= '?' . http_build_query($dati);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeoutTotale); // Timeout totale
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeoutConnessione); // Timeout di connessione


    if (!$this->CheckSSL){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }
    // header per la definizione del Content-Type
    $header = ["Content-Type: $contentType"];
    // Aggiungi gli header personalizzati agli header di default
    $header = array_merge($header, $headerPersonalizzati);
    // Imposta gli header HTTP
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    // Imposta il metodo HTTP e i dati
    switch (strtoupper($metodo)) {
        case "POST":
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($dati)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dati));
            }
            break;
    }

    // Imposta CURLOPT_RETURNTRANSFER per ottenere il risultato come stringa
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Esegui la chiamata cURL
    $response = curl_exec($ch);
    $ResponseContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    // Controlla se ci sono stati errori nella chiamata cURL
    if ($response === false) {
        $errorCode = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        // Controlla se si tratta di un timeout
        if ($errorCode === CURLE_OPERATION_TIMEDOUT) {
            throw new Exception("Timeout della richiesta raggiunto: ".$error);
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

    // Elaborazione della risposta in base al suo tipo di contenuto
    $managedContentTypes = ['application/json', 'text/xml', 'application/xml'];
    $processAs = in_array($ResponseContentType, $managedContentTypes) ? $ResponseContentType : $contentType;

    switch ($processAs) {
        case 'application/json':
            // Gestione della risposta JSON
            $oggetto = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Errore nella decodifica JSON: " . json_last_error_msg());
            }
            break;

        case 'text/xml':
        case 'application/xml':
            // Gestione della risposta XML
            libxml_use_internal_errors(true);
            $oggetto = simplexml_load_string($response);
            if ($oggetto === false) {
                $error = libxml_get_errors();
                libxml_clear_errors();
                throw new Exception("Errore nel parsing XML: " . implode(", ", $error));
            }
            break;

        default:
            // Gestione di altri tipi di contenuto (come testo semplice o HTML)
            $oggetto = $response;
            break;
    }
    // Restituisci l'oggetto decodificato o la risposta grezza
    return $oggetto;

}

}
?>

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
   * Restituisce il percorso completo dell'URL per una risorsa.
   * 
   * @param string $path Percorso della risorsa.
   * @return string URL completo della risorsa.
   */
  public function APIbaseURL($path) {
    if (strpos($path, "http://") === 0 || strpos($path, "https://") === 0) {
      return $path;
    } else {
      return $this->urlAPI."/".$path;
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
      return $this->baseUrl.$path;
    }
  }

  /**
   * Esegue una chiamata all'endpoint dell'API e restituisce la risposta.
   * 
   * @param string $path Percorso dell'endpoint dell'API.
   * @return array Risposta dell'API in formato array.
   * @throws Exception In caso di errore nella chiamata all'endpoint o nella risposta dell'API.
   */
  public function callApiEndpoint($path) {
    $url = rtrim($this->urlAPI, '/') . '/' . ltrim($path, '/');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception("Errore EndPoint: " . curl_error($ch));
    }

    curl_close($ch);
    $oggetto = json_decode($response, true);

    if (isset($oggetto['status']) && $oggetto['status'] === 'error') {
        throw new Exception("Errore API: " . $oggetto['message']);
    }

    return $oggetto;
  }
}
?>

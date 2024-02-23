<?php

class ExternalLink
{
    public string $type;
    public string $url;

    public function __construct(string $type, string $url)
    {
        $this->type = $type;
        $this->url = $url;
    }
}

class MetaDTO
{
    public bool $MobileFriendly = false;
    public bool $iOSFullScreenWebApp = false;
    public int $mobileOptimizationWidth = 320;
    public int $refreshIntervalInSeconds = 900;
    public array $keywords = [];
    private string $dataScadenza = '';
    public ?string $dataScadenzaGMT = null;
    public array $localcss = [];
    public array $localjs = [];
    /** @var ExternalLink[] */
    public array $ext_link = [];
    public string $title = '';
    public string $description = '';
    public ?string $author = null;

    public function __construct($data = null)
    {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }

            if (isset($this->dataScadenza) && $this->dataScadenza !== '') {
                $dateScadenza = DateTime::createFromFormat('d/m/Y', $this->dataScadenza, new DateTimeZone('Europe/Rome'));
                $this->dataScadenzaGMT = $dateScadenza ? $dateScadenza->format('D, d M Y H:i:s') . ' GMT' : null;
            }
        }
    }
}

class VoceInformazione
{
    public $chiave;
    public $traduzioneKey;
    public $callback;

    public function __construct($chiave, $traduzioneKey, $callback)
    {
        $this->chiave = $chiave;
        $this->traduzioneKey = $traduzioneKey;
        $this->callback = $callback;
    }

    public function visualizza($dati, $service)
    {
        if (isset($dati[$this->chiave])) {
            $valore = $dati[$this->chiave];
            $testo = $this->traduzioneKey ? $service->traduci($this->traduzioneKey) . ": " : "";
            $testo .= is_callable($this->callback) ? call_user_func($this->callback, $valore) : $valore;
            return $testo;
        }
        return null;
    }

    public static function verificaPresenzaDati($arrayVoceInformazione, $dati): bool
    {
        if (isset($dati))
            foreach ($arrayVoceInformazione as $voce) {
                if (isset($dati[$voce->chiave]) && !empty($dati[$voce->chiave])) {
                    return true;
                }
            }
        return false;
    }

    /**
     * Funzione per rendere un array di oggetti VoceInformazione.
     *
     * @param array $informazioni Array di oggetti VoceInformazione.
     * @param mixed $dati Informazioni della risorsa/logica specifica da passare a visualizza.
     * @param mixed $service Servizio/utilità per operazioni come la creazione di link.
     */
    public static function renderInfos($informazioni, $dati, $service, $forceFluid = false)
    {
        if (!self::verificaPresenzaDati($informazioni, $dati))
            return "";
        // Inizia a catturare l'output in un buffer
        ob_start();
        ?>
        <div class="col-12 col-sm<?= $forceFluid === true ? "" : "-6" ?> pt-1">
            <ul class="list-unstyled">
                <?php foreach ($informazioni as $voce): ?>
                    <?php $output = $voce->visualizza($dati, $service); ?>
                    <?php if ($output !== null): ?>
                        <li>
                            <?= $output ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        // Ottieni l'output dal buffer e poi puliscilo
        $html = ob_get_clean();
        // Restituisci l'HTML generato
        return $html;
    }

}




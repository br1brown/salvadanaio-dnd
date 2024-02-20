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



<?php
namespace BLL;

require_once 'APIException.php';
class Repository
{

    /**
     * Trova il percorso della directory 'API' partendo dalla directory corrente o da una specificata.
     * 
     * @param string $dir Percorso della directory da cui iniziare la ricerca.
     * @return string|null Percorso della directory 'API' se trovata, altrimenti null.
     */
    public static function findAPIPath(string $dir = __DIR__): ?string
    {
        $path = $dir . '/API/';

        // Controlla se il percorso esiste e in caso affermativo lo restituisce.
        if (file_exists($path)) {
            return $path;
        } elseif ($dir === dirname($dir)) {
            // Se siamo arrivati alla root senza trovare /API
            return null;
        } else {
            // Cerca nella directory padre
            return self::findAPIPath(dirname($dir));
        }
    }

    /**
     * Ottiene il nome del file JSON partendo da un nome base.
     * 
     * @param string $nome Nome base per il file.
     * @param string $ext estensione per il file, sempre .
     * @return string Percorso completo del file json.
     */
    public static function getFileName(string $nome, string $ext = "json"): string
    {
        return self::findAPIPath() . 'data/' . $nome . '.' . $ext;
    }

    /**
     * Ottiene il file
     * 
     * @param string $filePath Nome per il file.
     * @return string Contenuto completo del file
     */
    public static function getFileContent(string $filePath): string
    {
        if (file_exists($filePath) && is_readable($filePath)) {
            return file_get_contents($filePath);
        } else {
            throw new NotFoundException(pathinfo(($filePath), PATHINFO_FILENAME));
        }
    }
    /**
     * Ottiene un oggetto da un file JSON. Se 'decodeInData' è vero, decodifica il contenuto del file.
     * 
     * @param string $nome Nome base per il file.
     * @param bool $decodeInData Indica se decodificare il contenuto in un array.
     * @return mixed Oggetto o contenuto del file.
     * @throws Exception Se il file non può essere letto o se la decodifica JSON fallisce.
     */
    public static function getObj(string $nome, bool $decodeInData = true): mixed
    {
        $fileContent = self::getFileContent(self::getFileName($nome));
        if ($decodeInData) {
            $jsonData = json_decode($fileContent, true);

            if ($jsonData === null) {
                throw new DecodingException();
            } else {
                return $jsonData;
            }
        } else {
            return $fileContent;
        }
    }

    /**
     * Ottiene un oggetto da un file JSON. Se 'decodeInData' è vero, decodifica il contenuto del file.
     * 
     * @param string $nome Nome base per la stringa.
     * @return mixed contenuto del file.
     * @throws \Exception Se il file non può essere letto o se la decodifica JSON fallisce.
     */
    public static function getTxt(string $nome): mixed
    {
        $filePath = self::getFileName($nome, "txt");
        return self::getFileContent($filePath);
    }

    /**
     * Scrive un oggetto JSON.
     * 
     * @param string $nome Nome base per il file.
     * @param mixed $jsonData Dati da scrivere.
     * @param bool $isDecodedInData Indica se i dati sono già in formato JSON.
     */
    public static function putObj($nome, $jsonData, $isDecodedInData = true): void
    {
        $filename = self::getFileName($nome);

        if ($isDecodedInData) {
            $fileContent = json_encode($jsonData);
        } else {
            $fileContent = $jsonData;
        }

        file_put_contents($filename, $fileContent);
    }

    public static function getDefaultLang(): string
    {
        return "it";
    }

}
class Logging
{
    // Metodo statico per scrivere nel file di log con parametri variabili
    public static function log($tipo, $stringa, ...$oggetti)
    {
        // Ottieni il nome del file in base al tipo di log
        $file = Repository::getFileName($tipo . '_log', 'txt');

        // Crea una stringa con timestamp per il log
        $timestamp = date('Y-m-d H:i:s');

        // Se ci sono oggetti da loggare, formatta
        if (!empty($oggetti)) {
            // Usa json_encode su oggetti passati come parametri variabili
            $stringa = sprintf($stringa, ...$oggetti);
        }

        // Prepara il log completo con tipo e timestamp
        $log = sprintf("[%s] %s\n", $timestamp, $stringa);

        // Scrivi nel file
        file_put_contents($file, $log, FILE_APPEND);
    }

    // Metodi statici specifici per tipo di log
    public static function logError($stringa, ...$oggetti)
    {
        self::log('error', $stringa, ...$oggetti);
    }

    public static function logInfo($stringa, ...$oggetti)
    {
        self::log('info', $stringa, ...$oggetti);
    }

    public static function logWarning($stringa, ...$oggetti)
    {
        self::log('warning', $stringa, ...$oggetti);
    }

    // Aggiungi ulteriori metodi per altri tipi di log, come log di debug, successi, ecc.
}


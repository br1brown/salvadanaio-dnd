<?php
namespace BLL;
require_once 'APIException.php';
class Repository {

    /**
     * Trova il percorso della directory 'API' partendo dalla directory corrente o da una specificata.
     * 
     * @param string $dir Percorso della directory da cui iniziare la ricerca.
     * @return string|null Percorso della directory 'API' se trovata, altrimenti null.
     */
    private static function findAPIPath(string $dir = __DIR__): ?string {
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
    private static function getFileName(string $nome, string $ext = "json"): string{
        return self::findAPIPath() . 'data/' . $nome . '.'.$ext;
    }

    /**
     * Ottiene il file
     * 
     * @param string $filePath Nome per il file.
     * @return string Contenuto completo del file
     */
    private static function getFileContent(string $filePath): string{
        if (file_exists($filePath) && is_readable($filePath)) {
            return file_get_contents($filePath);
        } else {
            throw new NotFoundException($nome);
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
    public static function getObj(string $nome, bool $decodeInData = true): mixed{
        $fileContent = self::getFileContent (self::getFileName($nome));
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
     * @throws Exception Se il file non può essere letto o se la decodifica JSON fallisce.
     */
    public static function getTxt(string $nome): mixed{
        $filePath = self::getFileName($nome,"txt");
        return self::getFileContent ($filePath);
    }

    /**
     * Aggiorna elementi di un oggetto JSON in base a condizioni e modifiche specificate dalle callback.
     * Include controlli di validità sui parametri e una migliore gestione degli errori.
     * 
     * @param object &$data riferimento ai dati su cui sto lavorando.
     * @param string $tipoItem Chiave dell'elemento da modificare.
     * @param callable $condizioneCallback Callback che definisce la condizione di modifica.
     * @param callable $modificaCallback Callback che effettua la modifica.
     * @return bool True se la modifica è avvenuta, false altrimenti.
     * @throws Exception Se i dati non vengono trovati, se i parametri non sono validi.
     */
    public static function updateSingleItemInArrayObj(&$data, string $tipoItem, callable $condizioneCallback, callable $modificaCallback):bool {
        // Verifica la validità dei parametri
        if (empty($tipoItem) || !is_callable($condizioneCallback) || !is_callable($modificaCallback)) {
            throw new InvalidParametersException();
        }

        if (empty($data) || !isset($data[$tipoItem])) {
            throw new DataNotFoundException();
        }

        $modificato = false;
        foreach ($data[$tipoItem] as $key => &$item) {
            if ($condizioneCallback($item, $_POST)) {
                $modificaCallback($item, $_POST);
                $modificato = true;
                break;
            }
        }        

        return $modificato;
    }


    /**
     * Elimina elementi di un oggetto JSON in base a condizioni e modifiche specificate dalle callback.
     * Include controlli di validità sui parametri e una migliore gestione degli errori.
     * 
     * @param object &$data riferimento ai dati su cui sto lavorando.
     * @param string $tipoItem Chiave dell'elemento da eliminare.
     * @param callable $condizioneCallback Callback che definisce la condizione di eiminazione.
     * @return bool True se la eliminazione è avvenuta, false altrimenti.
     * @throws Exception Se i dati non vengono trovati, se i parametri non sono validi.
     */
    public static function deleteSingleItemInArrayObj(&$data, string $tipoItem, callable $condizioneCallback):bool {
        // Verifica la validità dei parametri
        if (empty($tipoItem) || !is_callable($condizioneCallback)) {
            throw new InvalidParametersException();
        }

        if (empty($data) || !isset($data[$tipoItem])) {
            throw new DataNotFoundException();
        }

        $trovato = false;
        foreach ($data[$tipoItem] as $key => $item) {
            if ($condizioneCallback($item, $_POST)) {
                unset($data[$tipoItem][$key]);
                $trovato = true;
                break;
            }
        }
        if ($trovato) {
            $data[$tipoItem] = array_values($data[$tipoItem]);
        }
        return $trovato;
    }

    /**
     * Aggiunge un nuovo elemento a un oggetto JSON se non esiste già.
     *
     * @param object &$data riferimento ai dati su cui sto lavorando.
     * @param string $tipoItem Chiave dell'elemento da aggiungere.
     * @param callable $condizioneEsistenzaCallback Callback che definisce la condizione di esistenza.
     * @param callable $creazioneCallback Callback che crea il nuovo elemento.
     * @return bool True se l'elemento è stato aggiunto, false se esiste già.
     * @throws Exception Se i parametri non sono validi.
     */
    public static function addSingleItemInArrayObj(&$data, string $tipoItem, callable $condizioneEsistenzaCallback, callable $creazioneCallback):bool {
        // Verifica la validità dei parametri
        if (empty($tipoItem) || !is_callable($condizioneEsistenzaCallback) || !is_callable($creazioneCallback)) {
            throw new InvalidParametersException();
        }

        if (!isset($data[$tipoItem]) || !is_array($data[$tipoItem])) {
            $data[$tipoItem] = [];
        }

        foreach ($data[$tipoItem] as $item) {
            if ($condizioneEsistenzaCallback($item, $_POST)) {
                return false; // Elemento già esistente, non aggiungere
            }
        }

        // Crea e aggiungi il nuovo elemento
        $newItem = $creazioneCallback($_POST);
        $data[$tipoItem][] = $newItem;

        return true; // Elemento aggiunto con successo
    }


    /**
     * Scrive un oggetto JSON.
     * 
     * @param string $nome Nome base per il file.
     * @param mixed $jsonData Dati da scrivere.
     * @param bool $isDecodedInData Indica se i dati sono già in formato JSON.
     */
    public static function putObj($nome, $jsonData, $isDecodedInData = true): void{
        $filename = self::getFileName($nome);

        if ($isDecodedInData) {
            $fileContent = json_encode($jsonData);
        } else {
            $fileContent = $jsonData;
        }

        file_put_contents($filename, $fileContent);
    }

}
?>
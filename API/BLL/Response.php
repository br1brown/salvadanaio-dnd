<?php
namespace BLL;

class Response
{
    /**
     * Restituisce un messaggio di successo in formato JSON.
     *
     * @param string $message Messaggio di successo.
     * @return string JSON response.
     */
    public static function retOK(string $message = null): string
    {
        if ($message != null)
            return json_encode(['status' => 'success', 'message' => $message]);
        return json_encode(['status' => 'success']);
    }

    /**
     * Restituisce un messaggio di errore in formato JSON.
     *
     * @param string $message Messaggio di errore.
     * @return string JSON response.
     */
    public static function retError(string $message): string
    {
        return json_encode(['status' => 'error', 'message' => $message]);
    }

    /**
     * Recupera i dati in "php://input"
     * @return mixed "php://input" Parsato se possibile
     */
    public static function datiinput()
    {
        $result = file_get_contents('php://input');
        try {
            return json_decode($result, true);
        } catch (\Exception $e) {
            parse_str($result, $rawData);
            return $rawData;
        }
    }

    /**
     * Traduce i valori specificati di una lista di array associativi nella lingua desiderata.
     * 
     * @param array $lista La lista da tradurre.
     * @param array $chiavi Chiavi degli oggetti che hanno come sottochiavi dei valori con "lingua" : "oggetto tradotto" da tradurre.
     * @param string $lingua La lingua in cui tradurre i valori.
     * @return array Lista tradotta.
     */
    public static function traduciLista($lista, $chiavi, $lingua)
    {
        return array_map(function ($elemento) use ($chiavi, $lingua) {
            return self::traduciElemento($elemento, $chiavi, $lingua);
        }, $lista);
    }

    /**
     * Traduce i valori specificati di un singolo elemento (array associativo) nella lingua desiderata.
     *
     * @param array $elemento L'elemento da tradurre, dove ogni chiave può contenere un valore diretto o un array di traduzioni.
     * @param array $chiavi Un array di chiavi da controllare e tradurre se disponibile.
     * @param string $lingua La lingua in cui tradurre i valori, se una traduzione è disponibile.
     * @return array L'elemento con i valori specificati tradotti nella lingua desiderata.
     */
    public static function traduciElemento($elemento, $chiavi, $lingua)
    {
        foreach ($chiavi as $chiave) {
            // Verifica se la chiave esiste nell'elemento e se il valore per quella chiave è un array
            if (isset($elemento[$chiave]) && is_array($elemento[$chiave])) {
                // Verifica se esiste la traduzione per la lingua desiderata
                if (isset($elemento[$chiave][$lingua])) {
                    // Sostituisce il valore con la traduzione trovata
                    $elemento[$chiave] = $elemento[$chiave][$lingua];
                } else {
                    $elemento[$chiave] = "";
                }
            }
        }
        return $elemento;
    }
}

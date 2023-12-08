<?php
namespace BLL;
class Response {
    /**
     * Restituisce un messaggio di successo in formato JSON.
     *
     * @param string $message Messaggio di successo.
     * @return string JSON response.
     */
    public static function retOK(string $message): string {
        return json_encode(['status' => 'success', 'message' => $message]);
    }

    /**
     * Restituisce un messaggio di errore in formato JSON.
     *
     * @param string $message Messaggio di errore.
     * @return string JSON response.
     */
    public static function retError(string $message): string {
        return json_encode(['status' => 'error', 'message' => $message]);
    }


    /**
     * Controlla se la richiesta HTTP corrente è una POST
     * @throws Exception Se il metodo della richiesta HTTP non è POST, lancia un'eccezione con un messaggio di errore.
     */
    public static function SiamoInPost(): void{
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception('Metodo HTTP non supportato');
        }
}
}
?>
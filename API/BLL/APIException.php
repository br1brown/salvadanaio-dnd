<?php
namespace BLL;

class NotFoundException extends \Exception
{
    public function __construct($nomeDati = "richieste")
    {
        parent::__construct("Impossibile leggere le informazioni " . $nomeDati, 404);
    }
}

class DecodingException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Errore nella decodifica", 400);
    }
}

class DataNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Dati non trovati", 404);
    }
}

class InvalidParametersException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Parametri non validi o mancanti", 400);
    }
}

?>
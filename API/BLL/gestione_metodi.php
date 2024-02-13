<?php

$funzione = "esegui" . $_SERVER['REQUEST_METHOD'];
// Verifica se il metodo è gestito e se la funzione corrispondente esiste
if (isset($funzione) && function_exists($funzione)) {
    try {
        call_user_func($funzione); // Chiama la funzione corrispondente
    } catch (Exception $e) {
        echo BLL\Response::retError($e->getMessage());
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
}

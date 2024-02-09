<?php
$metodo = $_SERVER['REQUEST_METHOD'];

$gestoriMetodi = [
    'GET' => 'eseguiGET',
    'POST' => 'eseguiPOST',
    'PUT' => 'eseguiPUT',
    'DELETE' => 'eseguiDELETE'
];

// Verifica se il metodo è gestito e se la funzione corrispondente esiste
if (isset($gestoriMetodi[$metodo]) && function_exists($gestoriMetodi[$metodo])) {
    call_user_func($gestoriMetodi[$metodo]); // Chiama la funzione corrispondente
} else {
    header("HTTP/1.1 405 Method Not Allowed");
}

?>
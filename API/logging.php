<?php
include __DIR__ . '/BLL/auth_and_cors_middleware.php';

// function eseguiPOST()
// {

//     header('Content-Type: application/json');

//     // Verifica se l'input 'pwd' è presente in $_POST
//     if (isset($_POST['pwd'])) {
//         echo json_encode(generaToken($_POST['pwd']));
//     } else {
//         // Se l'input 'pwd' non è presente, restituisce un errore
echo json_encode(['error' => 'Password non fornita']);
//     }
// }

include __DIR__ . '/BLL/gestione_metodi.php';

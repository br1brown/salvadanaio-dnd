<?php
include __DIR__.'/BLL/auth_and_cors_middleware.php';

$nomi = isset($_GET['nomi'])? $_GET['nomi'] : [];

echo Echo_getObj("social",function ($data) use ($nomi) { // use ($variabile, $variabile2) 
    
    if (empty($nomi)) 
        return $data;

    $nomiSocial = explode(';', $nomi);

    $risultati = [];

    // Converti tutte le chiavi di $data in minuscolo
    $dataLowerCase = array_change_key_case($data, CASE_LOWER);

    foreach ($nomiSocial as $nomeSocial) {
        // Converti anche il nomeSocial in minuscolo
        $nomeSocialLowerCase = strtolower($nomeSocial);

        if (isset($dataLowerCase[$nomeSocialLowerCase])) {
            $risultati[$nomeSocial] = $dataLowerCase[$nomeSocialLowerCase];
        }
    }


    return $risultati;    
});
?>
<?php
include __DIR__ . '/BLL/auth_and_cors_middleware.php';

function eseguiGET()
{
    echo Echo_getObj("personaggi", function ($nomi_personaggi, $lingua) {
        $personaggi = [];

        foreach ($nomi_personaggi as $infos_personaggio) {
            $personaggi[] = (new BLL\Personaggio($infos_personaggio["basename"]))->getData(false);
        }
        return $personaggi;
    });
}
include __DIR__ . '/BLL/gestione_metodi.php';

<?php
include __DIR__ . '/BLL/auth_and_cors_middleware.php';

function eseguiGET()
{
    echo Echo_getObj("personaggi", function ($nomi_personaggi, $lingua) {
        $personaggi = [];
        $total = 0;
        foreach ($nomi_personaggi as $infos_personaggio) {
            $personaggio = (new BLL\Personaggio($infos_personaggio["basename"]))->getData(false, ["history", "suspended"]);
            $total += $personaggio["totalcopper"];
            $personaggi[] = $personaggio;
        }

        // Valori di default per l'ordinamento
        $defaultField = 'totalcopper';
        $defaultOrder = 'ASC';

        // Estrazione e validazione del campo di ordinamento e della direzione
        $sort = isset($_GET["sort"]) ? $_GET["sort"] : "$defaultField;$defaultOrder";
        list($sortField, $sortOrder) = explode(';', $sort) + [null, null]; // Aggiunge null per evitare warning se manca uno dei due

        // Mappa il campo di ordinamento ricevuto a un campo effettivo dell'array e valida
        switch ($sortField) {
            case 'cash':
                $field = 'totalcopper';
                break;
            case 'name':
                $field = 'name';
                break;
            default:
                $field = $defaultField;
        }

        $sortOrder = in_array($sortOrder, ['ASC', 'DESC']) ? $sortOrder : $defaultOrder;

        // Implementazione dell'ordinamento
        usort($personaggi, function ($a, $b) use ($field, $sortOrder) {
            if ($field == 'name') {
                $result = strcmp($a[$field], $b[$field]);
            } else {
                $result = $a[$field] - $b[$field];
            }

            if ($sortOrder == 'DESC') {
                $result = -$result;
            }

            return $result;
        });

        return ["characters" => $personaggi, "allcash" => BLL\Personaggio::_ConvertValuta($total)];
    });
}
include __DIR__ . '/BLL/gestione_metodi.php';

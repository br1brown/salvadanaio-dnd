<?php

/**
 * Modifica l'array del menu direttamente per riferimento.
 *
 * @param array &$itemsMenu Riferimento all'array del menu ottenuto dal JSON statico.
 */
function dynamicMenu(&$itemsMenu) {
    // Qui puoi modificare direttamente l'array $itemsMenu.

    // Esempio: Aggiungere un nuovo elemento al menu
    // $itemsMenu[] = [
    //     'nome' => 'Nuova Voce',
    //     'route' => 'nuova-voce-route'
    // ];

    // Esempio: Modificare un elemento esistente
    // foreach ($itemsMenu as $key => &$item) {
    //     if ($item['nome'] == 'ElementoDaModificare') {
    //         $item['route'] = 'nuovo-route-modificato';
    //     }
    // }
    // Nota: Non dimenticare di rimuovere il riferimento dopo il ciclo
    // unset($item);

    // Esempio: Rimuovere un elemento
    // $itemsMenu = array_filter($itemsMenu, function($item) {
    //     return $item['nome'] != 'ElementoDaRimuovere';
    // });

    // Non è necessario restituire l'array, poiché è stato passato per riferimento
}
?>

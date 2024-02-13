<?php
require_once __DIR__ . '/parsedown-1.7.4/Parsedown.php';

$parser = null;
function Markdown_HTML($mark)
{

    if (!isset($parser))
        $parser = new Parsedown();

    return $parser->text($mark);

}



/**
 * Modifica l'array del menu direttamente per riferimento.
 *
 * @param Service Il servizio per le utilità front end
 * @param array &$itemsMenu Riferimento all'array del menu ottenuto dal JSON statico.
 */
function dynamicMenu($Service, &$itemsMenu)
{
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




function renderSoldi($obj)
{
    $ret = "";
    $primamoneta = "<span style='white-space: nowrap;'>";
    $dopomoneta = "</span>&nbsp";

    if (isset($obj['platinum']) && $obj['platinum'] != 0)
        $ret .= "{$primamoneta}<i class='fas fa-award platinum-color bordo-ico' title=platino></i> {$obj['platinum']}{$dopomoneta}";
    if (isset($obj['gold']) && $obj['gold'] != 0)
        $ret .= "{$primamoneta}<i class='fas fa-medal gold-color bordo-ico' title=oro></i> {$obj['gold']}{$dopomoneta}";
    if (isset($obj['silver']) && $obj['silver'] != 0)
        $ret .= "{$primamoneta}<i class='fas fa-trophy silver-color bordo-ico' title=argent></i> {$obj['silver']}{$dopomoneta}";
    if (isset($obj['copper']) && $obj['copper'] != 0)
        $ret .= "{$primamoneta}<i class='fas fa-coins copper-color bordo-ico' title=rame></i> {$obj['copper']}{$dopomoneta}";

    return $ret;
}

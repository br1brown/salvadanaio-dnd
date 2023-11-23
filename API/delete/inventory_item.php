<?php
include dirname(__DIR__).'/funzioni_comuni.php';

echo deleteGenericItem( 'inventory', 
    function($item, $postData) {
        return $item['itemName'] == $postData['itemName'];
    }
);

?>

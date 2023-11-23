<?php
include dirname(__DIR__).'/funzioni_comuni.php';

echo addGenericItem( 'inventory', 
    function($item, $postData) {
        return $item['itemName'] == $postData['itemName'];
    },
    function($postData) {
        return [
            'itemName' => $postData['itemName'],
            'quantity' => $postData['quantity'],
            'description' => $postData['description']
        ];
    }
);
?>

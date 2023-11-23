<?php
include dirname(__DIR__).'/funzioni_comuni.php';

echo updaateGenericItem( 'inventory', 
            function($item, $postData) {
                return $item['itemName'] == $postData['oldItemName'];
            },
            function(&$item, $postData) {
                $item['itemName'] = $postData['itemName'];
                $item['quantity'] = $postData['quantity'];
                $item['description'] = $postData['description'];
            });
?>

<?php
include dirname(__DIR__).'/funzioni_comuni.php';

echo deleteGenericItem( 'history', 
    function($item, $postData) {
        return $item['date'] == $postData['date'];
    }
);

?>

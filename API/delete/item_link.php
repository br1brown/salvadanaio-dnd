<?php
include dirname(__DIR__).'/funzioni_comuni.php';

echo deleteGenericItem( 'links', 
    function($item, $postData) {
        return $item['url'] == $postData['url'];
    }
);

?>

<?php
include dirname(__DIR__).'/funzioni_comuni.php';

echo addGenericItem( 'links', 
    function($item, $postData) {
        return $item['url'] == $postData['url'];
    },
    function($postData) {
        return [
            'url' => $postData['url'],
            'text' => $postData['linkText'],
            'note' => $postData['note']
        ];
    }
);


?>
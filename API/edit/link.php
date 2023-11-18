<?php
include dirname(__DIR__).'/funzioni_comuni.php';

echo updaateGenericItem( 'links', 
            function($item, $postData) {
                return $item['url'] == $postData['oldUrl'];
            },
            function(&$item, $postData) {
                $item['url'] = $postData['url'];
                $item['text'] = $postData['linkText'];
                $item['note'] = $postData['note'];
            });


?>
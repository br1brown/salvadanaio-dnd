<?php
include 'funzioni_comuni.php';
$variabile = 0;
echo getEncodeObj("irl",function ($data) use ($variabile) { // use ($variabile, $variabile2) 
    return $data;
});
?>
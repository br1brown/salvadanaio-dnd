<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    echo manageCharacterCoins($_POST['name'],"received",
        intval($_POST['platinum']),
        intval($_POST['gold']),
        intval($_POST['silver']),
        intval($_POST['copper']),
        $_POST['description']);

} else {
    echo retError('Metodo HTTP non supportato.');
}
?>

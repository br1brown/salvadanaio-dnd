<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    echo manageCharacterCoins($_POST['name'],"pay",
        intval($_POST['platinum']),
        intval($_POST['gold']),
        intval($_POST['silver']),
        intval($_POST['copper']),
        $_POST['description'],
        filter_var($_POST['canReceiveChange'], FILTER_VALIDATE_BOOLEAN)
    );

} else {
    echo retError('Metodo HTTP non supportato.');
}

?>

<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    $platinum = intval($_POST['platinum']);
    $gold = intval($_POST['gold']);
    $silver = intval($_POST['silver']);
    $copper = intval($_POST['copper']);
    $description = $_POST['description'];

    echo receiveCoins($characterName, $platinum, $gold, $silver, $copper, $description);
} else {
    echo retError('Metodo HTTP non supportato.');
}
?>

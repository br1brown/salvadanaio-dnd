<?php
include 'funzioni_comuni.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $characterName = $_POST['name'];
    $platinum = intval($_POST['platinum']);
    $gold = intval($_POST['gold']);
    $silver = intval($_POST['silver']);
    $copper = intval($_POST['copper']);
    $canReceiveChange = filter_var($_POST['canReceiveChange'], FILTER_VALIDATE_BOOLEAN);
    $description = $_POST['description'];

    echo makePayment($characterName, $platinum, $gold, $silver, $copper, $description, $canReceiveChange);
} else {
    echo retError('Metodo HTTP non supportato.');
}
?>

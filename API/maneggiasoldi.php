<?php
include 'funzioni_comuni.php';

    function esegui($tipo, $post) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $canReceiveChange = isset($post['canReceiveChange']) ? filter_var($post['canReceiveChange'], FILTER_VALIDATE_BOOLEAN) : str_starts_with($tipo, 'settle');
        $persona = isset($post['persona']) && !empty($post['persona']) ? $post['persona'] : null;
        $description = isset($post['description']) ? $post['description'] : "";
        echo manageCharacterCoins($post['name'], $tipo,
            intval($post['platinum']),
            intval($post['gold']),
            intval($post['silver']),
            intval($post['copper']),
            $description,
            $canReceiveChange,
            $persona
        );

    } else {
        echo retError('Metodo HTTP non supportato.');
    }
}

?>

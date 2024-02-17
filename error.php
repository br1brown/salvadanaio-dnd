<!doctype html>
<html lang="it">
<?php
$code = $_SERVER['REDIRECT_STATUS'];
$forceMenu = false;

include('FE_utils/TopPage.php');


$keyInfoTraduci = "errore" . $code . "Info";
$keyDescTraduci = "errore" . $code . "Desc";
$errorInfo = $service->traduci($keyInfoTraduci);
$errorMessage = $service->traduci($keyDescTraduci);

if ($errorMessage == $keyDescTraduci)
    $errorMessage = $service->traduci("erroreImprevisto");

if ($errorInfo == $keyInfoTraduci)
    $errorInfo = $service->traduci("errore") . ' ' . $code;
else {
    $errorInfo = $code . ": " . $errorInfo;

}

$source_url = 'http' . ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

?>
<div class="container-fluid">
    <div class="row">
        <div id=contenuto class="col-12 offset-md-2 col-md-8 text-center tutto">
            <h2>
                <?= $errorInfo ?>
            </h2>
            <small><i>
                    <?= $source_url ?>
                </i></small>
            <p>
                <?= $errorMessage ?>
            </p>

        </div>
    </div>
</div>
</body>
<script>
    inizializzazioneApp.then(() => {


    });

</script>

</html>
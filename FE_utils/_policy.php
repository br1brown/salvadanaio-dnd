<?php
include('TopPage.php');
$current_lang = $service->currentLang();
$sezioniComuni = [
    [
        "modificheInformativa" => [
            "it" => "Eventuali modifiche a questa Informativa verranno pubblicate su questa pagina con indicazione della data di aggiornamento.",
            "en" => "Any changes to this Policy will be posted on this page with an indication of the update date."
        ]
    ]
];

$sezioniComplessive = array_merge($pagina, $sezioniComuni);


?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 offset-md-1 col-md-10<?= $isDarkTextPreferred ? "" : " tutto" ?>">
            <div class="col-12 offset-md-1 col-md-10 p-3">
                <?php
                echo "<h1 class='text-right'>" . $service->traduci($title) . "</h1>\n";
                foreach ($sezioniComplessive as $sezione) {
                    foreach ($sezione as $chiave => $testo) {
                        if (isset($testo[$current_lang])) {
                            echo "<h3>" . $service->traduci($chiave) . "</h3>\n";
                            echo "<p>" . $testo[$current_lang] . "</p>\n";
                        }
                    }
                }
                if (!empty($sz_mail)) {

                    ?>
                    <h4>
                        <?= $service->traduci("contattaci") ?>
                    </h4>
                    <p>
                        <?php
                        $htmlMAIL = "<span class=\"badge badge-light\">" . $service->creaLinkCodificato($sz_mail, 'mailto:') . "</span>";
                        switch ($current_lang) {
                            case "it":
                                echo "Per domande riguardanti la nostra Informativa, potete contattarci all'indirizzo email: " . $htmlMAIL;
                                break;
                            case "en":
                                echo "For questions regarding our Policy, you can contact us at the email address: " . $htmlMAIL;
                        }
                        ?>
                    </p>
                    <?php
                }
                ?>
            </div>
            <div class="col-12">
                <hr>
                <p class="px-3 ">
                    <?= $service->traduci("ultimaRevisione") . ": <code class=\"badge badge-secondary\">" . $sz_data . "</code>"; ?>
                </p>

            </div>
        </div>
    </div>
</div>
<?php include('BottomPage.php'); ?>

</html>
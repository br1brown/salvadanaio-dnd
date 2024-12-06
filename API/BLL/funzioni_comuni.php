<?php
require_once __DIR__ . '/Personaggio.php';
use BLL\Cash;

/**
 * Ottiene un oggetto e lo restituisce, eventualmente dopo aver applicato una callback.
 * 
 * @param string $nome Nome dell'oggetto da ottenere.
 * @param callable|null $callback Funzione di callback da applicare ai dati.
 * @return string Risposta JSON con i dati ottenuti o un messaggio di errore.
 */
function Echo_getObj($nome, $callback = null)
{
    // Controlla se la callback fornita è eseguibile
    $ciLavoro = is_callable($callback);

    try {
        // Richiede i dati all'oggetto Repository
        $jsonData = BLL\Repository::getObj($nome, $ciLavoro);

        // Se esiste una callback valida, la applica ai dati ottenuti
        if ($ciLavoro) {
            $l = isset($_GET["lang"]) ? filter_input(INPUT_GET, "lang", FILTER_SANITIZE_STRING) : BLL\Repository::getDefaultLang();
            $jsonData = json_encode($callback($jsonData, $l));
        }

    } catch (Exception $e) {
        // In caso di eccezione, restituisce un messaggio di errore
        return BLL\Response::retError($e->getMessage());
    }

    // Restituisce i dati in formato JSON
    return $jsonData;
}

function ManeggiaSoldi($tipo, $dati)
{
    $canReceiveChange = isset($dati['canReceiveChange']) ? filter_var($dati['canReceiveChange'], FILTER_VALIDATE_BOOLEAN) : str_starts_with($tipo, 'settle');
    $description = isset($dati['description']) ? $dati['description'] : "";
    $itemdescription = isset($dati['itemdescription']) ? $dati['itemdescription'] : "";

    $pers = new BLL\Personaggio($dati['basename']);

    echo $pers->manageCharacterCoins(
        $tipo,
        new Cash(
            intval($dati['platinum']),
            intval($dati['gold']),
            intval($dati['silver']),
            intval($dati['copper'])
        ),
        $description,
        $canReceiveChange,
        $itemdescription
    );
}


function allaRomana(array $infos_personaggi, Cash $daSpendere, string $description, bool $gentile = true)
{
    $numeroPersonaggi = count($infos_personaggi);

    // Controlla che ci siano personaggi validi
    if ($numeroPersonaggi === 0) {
        throw new Exception("Nessun personaggio valido per dividere la spesa.");
    }

    $description = $description ?? "1/" . $numeroPersonaggi; // Imposta la descrizione se non è stata passata

    $daSpendere->refreshValuta();
    $totalCopper = $daSpendere->get_totalcopper();

    // Istanzia i personaggi e raccogli i soldi in un solo ciclo
    $personaggiIstanziati = [];
    $totaleRaccolto = new Cash(0, 0, 0, 0);
    $denaroIniziale = [];

    foreach ($infos_personaggi as $personaggio) {
        $pers = new BLL\Personaggio($personaggio["basename"]);
        $denaro = $pers->getCash();
        $totaleRaccolto->addCash($denaro);

        $denaroIniziale[] = [
            'personaggio' => $pers,
            'totalCopper' => $denaro->get_totalcopper(),
        ];
    }

    $totaleRaccoltoInCopper = $totaleRaccolto->get_totalcopper();

    // Verifica divisibilità del totale
    if ($totalCopper % $numeroPersonaggi !== 0) {
        throw new Exception("La somma non è divisibile in modo equo tra i personaggi.");
    }

    // Verifica se il totale raccolto è sufficiente
    if ($totaleRaccoltoInCopper < $totalCopper) {
        return BLL\Response::retError("Fondi insufficienti per coprire la spesa totale.", false);
    }

    // Modalità "gentile"
    if ($gentile) {
        // Calcola quanto assegnare equamente e l'eccedenza
        $quotaPerPersona = intdiv($totaleRaccoltoInCopper, $numeroPersonaggi);
        $eccedenza = $totaleRaccoltoInCopper % $numeroPersonaggi;

        // Ordina i personaggi per il loro denaro iniziale (discendente)
        usort($denaroIniziale, fn($a, $b) => $b['totalCopper'] <=> $a['totalCopper']);

        // Distribuisci il denaro equamente e assegna l'eccedenza
        foreach ($denaroIniziale as $index => $entry) {
            $entry['personaggio']->setCash(Cash::ConvertiValuta($quotaPerPersona));
        }

        if ($eccedenza > 0) {
            $denaroIniziale[0]['personaggio']->getCash()->addCash(Cash::ConvertiValuta($eccedenza));
        }
    }

    $quantopagailsingolo = Cash::ConvertiValuta($totalCopper / $numeroPersonaggi);

    $erroriPersonaggi = [];
    $personeDaSalvare = [];

    // Effettua i pagamenti e aggiungi le transazioni
    foreach ($denaroIniziale as $entry) {
        try {
            $pers = $entry['personaggio'];
            $pers->manageCurrency(false, $quantopagailsingolo);

            // Aggiungi la transazione alla cronologia
            $pers->addTransactionToHistory(
                BLL\TransactionType::SPENT,
                $quantopagailsingolo,
                $description . " (Split - 1/" . $numeroPersonaggi . ")"
            );

            // Salva il personaggio in caso di successo
            $personeDaSalvare[] = $pers;

        } catch (Exception $e) {
            $erroriPersonaggi[] = $pers->getName();
        }
    }

    // Gestione del risultato finale
    if (empty($erroriPersonaggi)) {
        // Salva tutti i personaggi
        foreach ($personeDaSalvare as $pers) {
            $pers->save();
        }
        return BLL\Response::retOK("Transazione (split) eseguita correttamente.", false);
    }

    // Restituisci un errore con i nomi dei personaggi problematici
    return BLL\Response::retError(
        "Errore: il pagamento non è riuscito per i seguenti personaggi: " . implode(", ", $erroriPersonaggi),
        false
    );
}


<?php

include dirname(__DIR__) . '/BLL/auth_and_cors_middleware.php';

function eseguiPOST()
{
    echo Echo_getObj("personaggi", function ($infos_personaggi, $lingua) {

        $tuttoOK = true;  // Flag per monitorare se tutti hanno pagato correttamente

        // Controllo input valuta
        $daSpendere = new BLL\Cash(
            intval($_POST['platinum'] ?? 0),
            intval($_POST['gold'] ?? 0),
            intval($_POST['silver'] ?? 0),
            intval($_POST['copper'] ?? 0)
        );
        $totalCopper = $daSpendere->get_totalcopper();

        // Controllo che la somma sia divisibile per il numero di personaggi
        $numeroPersonaggi = count($infos_personaggi);
        if ($numeroPersonaggi === 0 || $totalCopper % $numeroPersonaggi !== 0) {
            // La somma non è divisibile in modo equo tra i personaggi, solleva un'eccezione
            throw new Exception("La somma non è divisibile in modo equo tra i personaggi.");
        }

        $quantopagailsingolo = BLL\Cash::ConvertiValuta($totalCopper / $numeroPersonaggi);
        $description = $_POST['description'] ?? "1/" . $numeroPersonaggi;

        // Array per gestire gli errori specifici per personaggio
        $erroriPersonaggi = [];
        $personeDaSalvare = [];  // Array per salvare i personaggi che hanno pagato correttamente

        foreach ($infos_personaggi as $personaggio) {
            $pers = new BLL\Personaggio($personaggio["basename"]);

            try {
                $pers->manageCurrency(false, $quantopagailsingolo);

                // Se il pagamento è riuscito, aggiungi il personaggio all'array per il salvataggio
                $personeDaSalvare[] = $pers;

                // Aggiungi la transazione alla cronologia
                $pers->addTransactionToHistory(BLL\TransactionType::SPENT, $daSpendere, $description . " (Split - 1/" . $numeroPersonaggi . ")");

            } catch (Exception $e) {
                $erroriPersonaggi[] = $personaggio["name"];
                // Se si verifica un errore, imposta il flag e termina il ciclo
                $tuttoOK = false;
                break;  // Esce dal ciclo se si verifica un errore
            }
        }

        // Se tutti i personaggi hanno pagato correttamente, salva lo stato
        if ($tuttoOK) {
            foreach ($personeDaSalvare as $pers) {
                // Salva lo stato del personaggio
                $pers->save();
            }
            return BLL\Response::retOK("Transazione (Split) eseguita correttamente.");
        } else {
            // Se non tutti hanno pagato, restituisci un errore con i nomi dei personaggi problematici
            return BLL\Response::retError("Errore: il pagamento non è riuscito per i seguenti personaggi: " . implode(", ", $erroriPersonaggi));
        }

    });
}

include dirname(path: __DIR__) . '/BLL/gestione_metodi.php';

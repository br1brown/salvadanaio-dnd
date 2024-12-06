<?php

include dirname(__DIR__) . '/BLL/auth_and_cors_middleware.php';

function eseguiPOST()
{
    echo Echo_getObj("personaggi", function ($infos_personaggi, $lingua) {

        $tuttoOK = true;  // Flag per monitorare se tutti hanno ricevuto la loro parte del bottino senza errori

        // Controllo input valuta
        $daDividere = new BLL\Cash(
            intval($_POST['platinum'] ?? 0),
            intval($_POST['gold'] ?? 0),
            intval($_POST['silver'] ?? 0),
            intval($_POST['copper'] ?? 0)
        );
        $totalCopper = $daDividere->get_totalcopper();

        $numeroPersonaggi = count($infos_personaggi);

        // Calcolo della quota individuale
        $quotaPerPersonaggio = BLL\Cash::ConvertiValuta($totalCopper / $numeroPersonaggi);
        $description = $_POST['description'] ?? "Divisione del bottino - 1/" . $numeroPersonaggi;

        // Array per gestire gli errori specifici per personaggio
        $erroriPersonaggi = [];
        $personaggiAggiornati = [];  // Array per salvare i personaggi che hanno ricevuto la loro parte

        foreach ($infos_personaggi as $personaggio) {
            $pers = new BLL\Personaggio($personaggio["basename"]);

            try {
                $pers->manageCurrency(true, $quotaPerPersonaggio);

                // Se la divisione è riuscita, aggiungi il personaggio all'array di aggiornamento
                $personaggiAggiornati[] = $pers;

                // Aggiungi la transazione alla cronologia
                $pers->addTransactionToHistory(
                    BLL\TransactionType::RECEIVED,
                    $quotaPerPersonaggio,
                    $description . " (Split - 1/" . $numeroPersonaggi . ")"
                );

            } catch (Exception $e) {
                $erroriPersonaggi[] = $personaggio["name"];
                // Se si verifica un errore, imposta il flag e continua per i rimanenti
                $tuttoOK = false;
            }
        }

        // Se tutti i personaggi hanno ricevuto la loro parte, salva lo stato
        if ($tuttoOK) {
            foreach ($personaggiAggiornati as $pers) {
                $pers->save(); // Salva lo stato del personaggio
            }
            return BLL\Response::retOK("Transazione (split) eseguita correttamente.", false);
        } else {
            // Se ci sono stati errori, restituisci i dettagli
            return BLL\Response::retError(
                "Errore: divisione del bottino non riuscita per i seguenti personaggi: " . implode(", ", $erroriPersonaggi),
                false
            );
        }
    });
}

include dirname(__DIR__) . '/BLL/gestione_metodi.php';

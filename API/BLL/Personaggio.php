<?php
namespace BLL;

enum TransactionType: string
{
    case DEBT = 'debt';
    case RECEIVED = 'received';
    case SETTLE_CREDIT = 'settle_credit';
    case CREDIT = 'credit';
    case SPENT = 'spent';
    case SETTLE_DEBT = 'settle_debt';
}



class Personaggio
{
    private string $baseName;
    private PersonaggioDTO $personaggio;
    private string $filePath;

    public static function Crea(string $nome, int $platinum = 0, int $gold = 0, int $silver = 0, int $copper = 0)
    {
        if (empty($nome))
            throw new \Exception("Valore nome non valido");
        $nome = trim($nome);
        $baseName = self::GetBaseName($nome);
        $fileName = Repository::getFileName($baseName);
        if (file_exists($fileName)) {
            throw new \Exception($nome . " esiste");
        }
        $p = Repository::getObj("personaggi");
        $p[] = ["basename" => $baseName, "name" => $nome];
        Repository::putObj("personaggi", $p);

        $dto = new PersonaggioDTO($nome, new Cash($platinum, $gold, $silver, $copper));

        self::_save($fileName, $dto);

        return Response::retOK($nome . " creato");
    }

    public static function Elimina(string $baseName)
    {
        if (!file_exists(Repository::getFileName($baseName))) {
            throw new \Exception($baseName . " non esiste ");
        }

        $p = Repository::getObj("personaggi");
        $index = -1;
        foreach ($p as $i => $pers) {

            if ($pers["basename"] == $baseName) {
                $index = $i;
            }
        }
        if ($index >= 0) {
            unset($p[$index]);
            $p = array_values($p);
            Repository::putObj("personaggi", $p);
            unlink(Repository::getFileName($baseName));
        } else {
            throw new \Exception($baseName . " non trovato nell'elenco");
        }

        Repository::putObj("personaggi", $p);

        return Response::retOK($baseName . " cancellato");
    }


    public static function GetBaseName(string $baseName): string
    {
        return strtoupper(trim(preg_replace("/\s+/", "_", $baseName)));
    }

    public function __construct(string $baseName)
    {
        $this->baseName = $baseName;
        $this->filePath = Repository::getFileName($baseName);

        $this->loadData();
    }
    private static function loadCambi()
    {
        return Repository::getObj("configmoney");
    }
    private function loadData(): void
    {
        if (file_exists($this->filePath)) {
            $json = file_get_contents($this->filePath);
            $decoded = json_decode($json, true);

            $cash = new Cash($decoded["cash"]['platinum'], $decoded["cash"]['gold'], $decoded["cash"]['silver'], $decoded["cash"]['copper']);

            $suspended = [];
            $history = [];
            $inventory = [];
            if (isset($decoded['suspended'])) {
                foreach ($decoded['suspended'] as $tipo => $transazioni) {
                    foreach ($transazioni as $transazione) {
                        $suspended[$tipo][] = new TransazioneSospesa($transazione['copper'], $transazione['description']);
                    }
                }
            }
            if (isset($decoded['inventory'])) {
                foreach ($decoded['inventory'] as $roba) {
                    $inventory[] = new Inventario($roba["itemName"], $roba["quantity"], $roba["description"], );
                }
            }

            if (isset($decoded['history'])) {
                foreach ($decoded['history'] as $evento) {
                    $history[] = CronologiaPagamento::from_objectvars($evento);
                }
            }

            $this->personaggio = new PersonaggioDTO($decoded['name'], $cash, $suspended, $history, $inventory);

        } else {
            throw new \Exception("Personaggio non trovato");
        }
    }

    public static function _save(string $filePath, PersonaggioDTO $personaggio): void
    {
        file_put_contents($filePath, json_encode($personaggio));
    }

    public function save(): void
    {
        self::_save($this->filePath, $this->personaggio);
    }

    /**
     * Gestisce le transazioni di valuta del personaggio, incluse ricezione, spesa,
     * avvio di debiti o crediti, e la loro sanatoria.
     *
     * @param TransactionType $transactionType Tipo di transazione
     * @param Cash $soldi Oggetto Cash che rappresenta l'importo coinvolto nella transazione.
     * @param string $description Descrizione della transazione da aggiungere alla cronologia.
     * @param bool $canReceiveChange Indica se è possibile ricevere resto (default: false).
     * @param string $itemdescription Descrizione dell'elemento legato alla transazione (usato per identificare debiti/crediti da sanare).
     * 
     * @throws \Exception Se il tipo di transazione non è supportato o se un contratto non viene trovato.
     */
    public function manageCharacterCoins(
        TransactionType $transactionType,
        Cash $soldi,
        string $description,
        bool $canReceiveChange = false,
        string $itemdescription = ""
    ): string {
        // Determina se la transazione aggiunge o rimuove valuta
        $isAdding = $this->determineTransactionDirection($transactionType);

        // Gestisce la valuta del personaggio
        $this->manageCurrency(
            isAdding: $isAdding,
            tempCash: $soldi,
            canReceiveChange: $canReceiveChange
        );

        // Totale in rame della transazione
        $totalCopperManaging = $soldi->get_totalcopper();

        // Gestisce transazioni sospese o sanate
        if (in_array($transactionType, [TransactionType::DEBT, TransactionType::CREDIT])) {
            $this->addSuspendedTransaction($transactionType, $totalCopperManaging, $description);
        } elseif (in_array($transactionType, [TransactionType::SETTLE_DEBT, TransactionType::SETTLE_CREDIT])) {
            $this->settleSuspendedTransaction($transactionType, $totalCopperManaging, $itemdescription);
        }

        // Aggiunge la transazione alla cronologia
        $this->addTransactionToHistory($transactionType, $soldi, $description);

        // Salva lo stato del personaggio
        $this->save();

        // Restituisce una risposta di successo
        return Response::retOK("Transazione ($transactionType->value) eseguita correttamente.");
    }

    /**
     * Determina la direzione della transazione (aggiunta o rimozione di valuta).
     */
    private function determineTransactionDirection(TransactionType $transactionType): bool
    {
        return match ($transactionType) {
            TransactionType::DEBT, TransactionType::RECEIVED, TransactionType::SETTLE_CREDIT => true, //aggiungo soldi
            TransactionType::CREDIT, TransactionType::SPENT, TransactionType::SETTLE_DEBT => false, //tolgo soldi
            default => throw new \Exception("Tipo di transazione non supportato."),
        };
    }

    /**
     * Aggiunge una nuova transazione sospesa.
     */
    private function addSuspendedTransaction(TransactionType $type, int $amount, string $description): void
    {
        $this->personaggio->suspended[$type->value][] = new TransazioneSospesa(
            $amount,
            preg_replace("/\r\n|\n|\r/", " - ", $description)  // Rimuove i ritorni a capo dalla descrizione
        );
    }

    /**
     * Sanatoria di una transazione sospesa (debito o credito).
     */
    private function settleSuspendedTransaction(TransactionType $type, int $amount, string $description): void
    {
        // Usa i valori dell'enum per determinare il tipo di transazione
        $transactionKey = match ($type) {
            TransactionType::SETTLE_DEBT => 'debt',
            TransactionType::SETTLE_CREDIT => 'credit',
            default => throw new \Exception("Tipo di transazione non valido per la sanatoria."),
        };

        $found = false;

        // Scorre le transazioni sospese per trovare quella corrispondente
        foreach ($this->personaggio->suspended[$transactionKey] as $key => $transaction) {
            if ($transaction->copper === $amount && $transaction->description === $description) {
                unset($this->personaggio->suspended[$transactionKey][$key]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \Exception("Contratto non trovato.");
        }

        // Reindicizza l'array
        $this->personaggio->suspended[$transactionKey] = array_values($this->personaggio->suspended[$transactionKey]);
    }

    /**
     * Aggiunge una transazione alla cronologia del personaggio.
     */
    private function addTransactionToHistory(
        TransactionType $type,
        Cash $soldi,
        string $description
    ): void {
        $this->personaggio->history[] = new CronologiaPagamento(
            new \DateTime(),
            $soldi->platinum,
            $soldi->gold,
            $soldi->silver,
            $soldi->copper,
            $description,
            strtoupper($type->value)
        );
    }



    /**
     * Gestisce l'aggiunta o la rimozione di valuta dall'oggetto Cash del personaggio.
     * 
     * @param bool $isAdding Indica se si sta aggiungendo valuta (true) o effettuando un pagamento (false).
     * @param int $platinum Monete di platino da gestire.
     * @param int $gold Monete d'oro da gestire.
     * @param int $silver Monete d'argento da gestire.
     * @param int $copper Monete di rame da gestire.
     * @param bool $canReceiveChange Indica se il pagamento può includere il resto (true) o richiede pagamento esatto (false).
     * @throws \Exception Se non ci sono abbastanza fondi o le denominazioni richieste.
     */
    function manageCurrency(
        bool $isAdding,
        Cash $tempCash,
        bool $canReceiveChange = true
    ) {

        if ($isAdding) {
            // Aggiunge la valuta al Cash del personaggio
            $this->personaggio->cash->addCash($tempCash);
        } else {
            // Effettua un pagamento
            try {
                $this->personaggio->cash->processPayment($tempCash, $canReceiveChange);
            } catch (\Exception $e) {
                // Gestisce eventuali errori legati a fondi insufficienti o denominazioni errate
                throw new \Exception("Errore durante il pagamento: " . $e->getMessage());
            }
        }
    }

    public function refreshCambio()
    {
        $this->personaggio->cash->refreshValuta();
        $this->save();
    }

    public function getData(bool $encode = true, array $exclude = [])
    {
        $personaggioArray = [
            "basename" => $this->baseName,
            "name" => $this->personaggio->name,
            "platinum" => $this->personaggio->cash->platinum,
            "gold" => $this->personaggio->cash->gold,
            "silver" => $this->personaggio->cash->silver,
            "copper" => $this->personaggio->cash->copper,
            "suspended" => [],
            "history" => [],
            "totalcopper" => $this->personaggio->cash->get_totalcopper(),
        ];

        if (!in_array("history", $exclude))
            foreach ($this->personaggio->history as $cronologia) {
                $personaggioArray["history"][] = $cronologia->jsonSerialize();
            }

        if (!in_array("suspended", $exclude))
            foreach ($this->personaggio->suspended as $tipo => $transazioni) {
                foreach ($transazioni as $transazione) {
                    $converted = new Cash(0, 0, 0, $transazione->copper);
                    $converted->refreshValuta();
                    $personaggioArray["suspended"][$tipo][] = [
                        "platinum" => $converted->platinum,
                        "gold" => $converted->gold,
                        "silver" => $converted->silver,
                        "copper" => $converted->copper,
                        "description" => $transazione->description,
                        "totalCopper" => $transazione->copper,
                    ];
                }
            }

        if ($encode) {
            return json_encode($personaggioArray);
        } else {
            return $personaggioArray;
        }
    }
    function setInventario($itemName, $quantity, $description)
    {
        $trovato = false;
        foreach ($this->personaggio->inventory as &$item) {
            if ($item->itemName === $itemName) {
                $trovato = true;
                if ($quantity > 0) {
                    $item->quantity = $quantity;
                    if (!empty($description))
                        $item->description = $description;
                } else {
                    unset($this->personaggio->inventory[array_search($item, $this->personaggio->inventory)]);
                    $this->personaggio->inventory = array_values($this->personaggio->inventory);
                }
                break;
            }
        }
        if (!$trovato && $quantity > 0) {
            // Se l'elemento non è stato trovato e la quantità è maggiore di zero, aggiungi un nuovo elemento
            $nuovoItem = new Inventario($itemName, $quantity, $description);
            $this->personaggio->inventory[] = $nuovoItem;
            $this->save();
        }
        $this->save();
        return Response::retOK();
    }

    public function getInventario()
    {
        return $this->personaggio->inventory;
    }

    function deleteCronologia($data)
    {
        $trovato = false;
        foreach ($this->personaggio->history as $key => $cronologiaPagamento) {
            if ($cronologiaPagamento->haQuestaData($data)) {
                unset($this->personaggio->history[$key]);
                $this->personaggio->history = array_values($this->personaggio->history); // Reindirizza l'array
                $trovato = true;
            }
        }
        if ($trovato) {
            $this->save();
            return Response::retOK();
        } else {
            throw new \Exception('Elemento non trovato; Nessuna eliminazione');
        }
    }
}

class PersonaggioDTO implements \JsonSerializable
{
    public string $name;
    public Cash $cash;
    public int $gold;
    public int $silver;
    public int $copper;
    public array $suspended; // Array "tipo" -> Array di oggetti TransazioneSospesa
    public array $history; // Array di oggetti CronologiaPagamento
    public array $inventory; // Array di oggetti Inventario

    public function __construct(
        string $name,
        Cash $cash,
        array $suspended = [],
        array $history = [],
        array $inventory = []
    ) {
        $this->name = $name;
        $this->cash = $cash;
        $this->suspended = $suspended;
        $this->history = $history;
        $this->inventory = $inventory;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

class Cash
{
    // Proprietà pubbliche che rappresentano il numero di monete di ciascun tipo
    public int $platinum; // Monete di platino
    public int $gold;     // Monete d'oro
    public int $silver;   // Monete d'argento
    public int $copper;   // Monete di rame

    // Proprietà private che rappresentano i tassi di cambio per ogni tipo di moneta
    private int $CAMBIO_PLATINUM; // Valore in copper per 1 moneta di platino
    private int $CAMBIO_GOLD;     // Valore in copper per 1 moneta d'oro
    private int $CAMBIO_SILVER;   // Valore in copper per 1 moneta d'argento

    /**
     * Carica i tassi di cambio dalla repository di configurazione.
     * Metodo statico perché non dipende da una specifica istanza della classe.
     * @return array Configurazione con i tassi di cambio.
     */
    private static function loadCambi()
    {
        return Repository::getObj("configmoney");
    }

    /**
     * Aggiorna i valori delle monete sulla base del totale in copper.
     * Divide il totale in copper nelle varie denominazioni.
     */
    public function refreshValuta()
    {
        $totCopper = $this->get_totalcopper(); // Ottiene il totale in copper

        // Calcola il numero di monete di platino e aggiorna il totale in copper rimanente
        $this->platinum = floor($totCopper / $this->CAMBIO_PLATINUM);
        $totCopper -= $this->platinum * $this->CAMBIO_PLATINUM;

        // Calcola il numero di monete d'oro e aggiorna il totale in copper rimanente
        $this->gold = floor($totCopper / $this->CAMBIO_GOLD);
        $totCopper -= $this->gold * $this->CAMBIO_GOLD;

        // Calcola il numero di monete d'argento e il rimanente diventa copper
        $this->silver = floor($totCopper / $this->CAMBIO_SILVER);
        $this->copper = $totCopper % $this->CAMBIO_SILVER;
    }

    /**
     * Converte un totale in copper in un oggetto Cash.
     * @param int $copper Totale in monete di rame da convertire.
     * @return Cash Istanza della classe Cash con i valori calcolati.
     */
    public static function ConvertiValuta(int $copper): Cash
    {
        $ret = new Cash(0, 0, 0, $copper);
        $ret->refreshValuta(); // Aggiorna i valori delle monete
        return $ret;
    }

    /**
     * Costruttore della classe Cash.
     * @param int $platinum Numero iniziale di monete di platino.
     * @param int $gold Numero iniziale di monete d'oro.
     * @param int $silver Numero iniziale di monete d'argento.
     * @param int $copper Numero iniziale di monete di rame.
     */
    public function __construct(int $platinum, int $gold, int $silver, int $copper)
    {
        $config = self::loadCambi(); // Carica i tassi di cambio
        // Inizializza i tassi di cambio
        $this->CAMBIO_PLATINUM = $config["platinum"];
        $this->CAMBIO_GOLD = $config["gold"];
        $this->CAMBIO_SILVER = $config["silver"];

        // Inizializza le proprietà pubbliche
        $this->platinum = $platinum;
        $this->gold = $gold;
        $this->silver = $silver;
        $this->copper = $copper;
    }

    /**
     * Calcola il totale in copper considerando tutte le monete.
     * @return int Totale in monete di rame.
     */
    public function get_totalcopper(): int
    {
        return ($this->platinum * $this->CAMBIO_PLATINUM) +
            ($this->gold * $this->CAMBIO_GOLD) +
            ($this->silver * $this->CAMBIO_SILVER) +
            $this->copper;
    }

    /**
     * Aggiunge monete all'attuale oggetto Cash.
     * @param Cash $totalCash Oggetto Cash da aggiungere.
     */
    public function addCash(Cash $totalCash)
    {
        $this->platinum += $totalCash->platinum;
        $this->gold += $totalCash->gold;
        $this->silver += $totalCash->silver;
        $this->copper += $totalCash->copper;
    }

    /**
     * Gestisce il pagamento.
     * @param Cash $totalCash Importo da pagare.
     * @param bool $canReceiveChange Se vero, calcola il resto. Altrimenti, richiede pagamento esatto.
     * @throws \Exception Se il pagamento non può essere effettuato.
     */
    public function processPayment(Cash $totalCash, bool $canReceiveChange)
    {
        $totalCopperToPay = $totalCash->get_totalcopper(); // Totale in copper da pagare
        $totalCopper = $this->get_totalcopper(); // Totale in copper disponibile

        if ($totalCopper < $totalCopperToPay) {
            throw new \Exception("Fondi insufficienti per effettuare il pagamento.");
        }

        if ($canReceiveChange) {
            $this->pagaConCambio($totalCopper, $totalCopperToPay);
        } else {
            $this->payExactAmount($totalCash);
        }
    }

    /**
     * Calcola il resto dopo un pagamento.
     * @param int $totalCopper Totale disponibile in copper.
     * @param int $totalCopperToPay Totale da pagare in copper.
     */
    private function pagaConCambio(int $totalCopper, int $totalCopperToPay)
    {
        // Imposta il resto come il totale rimanente in copper
        $this->platinum = 0;
        $this->gold = 0;
        $this->silver = 0;
        $this->copper = $totalCopper - $totalCopperToPay;

        // Aggiorna i valori delle monete
        $this->refreshValuta();
    }

    /**
     * Effettua un pagamento esatto, sottraendo le monete richieste.
     * @param Cash $totalCash Oggetto Cash che rappresenta il pagamento.
     * @throws \Exception Se non ci sono abbastanza monete delle denominazioni corrette.
     */
    private function payExactAmount(Cash $totalCash)
    {
        // Controlla se le monete disponibili sono sufficienti
        if (
            $this->platinum < $totalCash->platinum ||
            $this->gold < $totalCash->gold ||
            $this->silver < $totalCash->silver ||
            $this->copper < $totalCash->copper
        ) {
            throw new \Exception("Fondi insufficienti nelle denominazioni richieste per il pagamento esatto.");
        }

        // Sottrae le monete richieste
        $this->platinum -= $totalCash->platinum;
        $this->gold -= $totalCash->gold;
        $this->silver -= $totalCash->silver;
        $this->copper -= $totalCash->copper;
    }
}

class CronologiaPagamento implements \JsonSerializable
{
    public static function from_objectvars(array $evento): self
    {
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $evento['date']);
        return new self($date, $evento["platinum"], $evento["gold"], $evento["silver"], $evento["copper"], $evento["description"], $evento["type"]);
    }

    public \DateTime $date;
    public int $platinum;
    public int $gold;
    public int $silver;
    public int $copper;
    public string $description;
    public string $type;

    public function __construct(
        \DateTime $date,
        int $platinum,
        int $gold,
        int $silver,
        int $copper,
        string $description,
        string $type
    ) {
        $this->date = $date;
        $this->platinum = $platinum;
        $this->gold = $gold;
        $this->silver = $silver;
        $this->copper = $copper;
        $this->description = $description;
        $this->type = $type;
    }
    public function jsonSerialize()
    {
        $c = get_object_vars($this);
        $c["date"] = $this->date->format('Y-m-d H:i:s');
        return $c;
    }


    public function haQuestaData(string $data): bool
    {
        return $data === $this->date->format('Y-m-d H:i:s');
    }
}

class TransazioneSospesa implements \JsonSerializable
{
    public int $copper; // Totale in rame
    public string $description; // Descrizione della transazione

    public function __construct(
        int $copper,
        string $description
    ) {
        $this->copper = $copper;
        $this->description = $description;
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}


class Inventario implements \JsonSerializable
{
    public string $itemName;
    public int $quantity;
    public string $description;

    public function __construct(
        string $itemName,
        int $quantity,
        string $description
    ) {
        $this->itemName = $itemName;
        $this->quantity = $quantity;
        $this->description = $description;
    }
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
<?php
namespace BLL;

class Personaggio
{
    private string $baseName;
    private PersonaggioDTO $personaggio;
    private string $filePath;

    private int $CAMBIO_PLATINUM;
    private int $CAMBIO_GOLD;
    private int $CAMBIO_SILVER;

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
        $config = self::loadCambi();
        $this->CAMBIO_PLATINUM = $config["platinum"];
        $this->CAMBIO_GOLD = $config["gold"];
        $this->CAMBIO_SILVER = $config["silver"];

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
    public function manageCharacterCoins(
        string $transactionType,
        Cash $soldi,
        string $description,
        bool $canReceiveChange = false,
        string $itemdescription = ""
    ) {

        $isAdding = null;
        $shouldAdjustCurrency = false;

        switch ($transactionType) {
            case "debt": //sto avviando un debito quindi ricevo soldi
            case "received":
            case "settle_credit": //sto incasssando un credito quindi ricevo soldi
                $isAdding = true;
                $shouldAdjustCurrency = true;
                break;

            case "credit": //sto avviando un credito quindi perdo soldi
            case "spent":
            case "settle_debt": //sto incasssando un debito quindi ricevo soldi
                $isAdding = false;
                $shouldAdjustCurrency = true;
                break;

            default:
                throw new \Exception("Tipo di transazione non supportata.");
        }

        if ($shouldAdjustCurrency) {
            $this->manageCurrency(
                $isAdding,
                $soldi->platinum,
                $soldi->gold,
                $soldi->silver,
                $soldi->copper,
                $canReceiveChange
            );
        }

        $totalCopperManaging =
            $soldi->platinum * $this->CAMBIO_PLATINUM +
            $soldi->gold * $this->CAMBIO_GOLD +
            $soldi->silver * $this->CAMBIO_SILVER +
            $soldi->copper;
        switch ($transactionType) {
            case "debt":
            case "credit":
                $this->personaggio->suspended[$transactionType][] =
                    new TransazioneSospesa(
                        $totalCopperManaging,
                        preg_replace("/\r\n|\n|\r/", " - ", $description),
                    );

                break;

            case "settle_debt":
            case "settle_credit":
                // Rimuovi il debito o il credito sanato
                $transactionKey =
                    $transactionType === "settle_debt" ? "debt" : "credit";
                $found = false;
                foreach ($this->personaggio->suspended[$transactionKey] as $key => $transaction) {
                    if (
                        $transaction->copper === $totalCopperManaging &&
                        $transaction->description === $itemdescription
                    ) {
                        unset(
                            $this->personaggio->suspended[$transactionKey][$key]
                            );
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    throw new \Exception("Contratto non trovato.");
                }
                $this->personaggio->suspended[$transactionKey] = array_values(
                    $this->personaggio->suspended[$transactionKey]
                ); // Reindex array
                break;
        }

        $this->personaggio->history[] = new CronologiaPagamento(
            new \DateTime(),
            $soldi->platinum,
            $soldi->gold,
            $soldi->silver,
            $soldi->copper,
            $description,
            strtoupper($transactionType),
        );

        $this->save();

        return Response::retOK(
            "Transazione ($transactionType) eseguita correttamente."
        );

    }

    function manageCurrency(
        bool $isAdding,
        int $platinum = 0,
        int $gold = 0,
        int $silver = 0,
        int $copper = 0,
        bool $canReceiveChange = true
    ) {
        if ($isAdding) {
            $this->personaggio->cash->platinum += $platinum;
            $this->personaggio->cash->gold += $gold;
            $this->personaggio->cash->silver += $silver;
            $this->personaggio->cash->copper += $copper;
        } else {
            if ($canReceiveChange) {
                $totalCopperToPay =
                    $platinum * $this->CAMBIO_PLATINUM +
                    $gold * $this->CAMBIO_GOLD +
                    $silver * $this->CAMBIO_SILVER +
                    $copper;
                $totalCopper = $this->get_totalcopper();
                if ($totalCopper < $totalCopperToPay) {
                    throw new \Exception(
                        "Non hai abbastanza monete per effettuare il pagamento esatto"
                    );
                }

                $this->personaggio->cash->platinum = 0;
                $this->personaggio->cash->gold = 0;
                $this->personaggio->cash->silver = 0;
                $this->personaggio->cash->copper = $totalCopper - $totalCopperToPay;

                $this->refreshCambio();
            } else {
                if (
                    $this->personaggio->cash->platinum < $platinum ||
                    $this->personaggio->cash->gold < $gold ||
                    $this->personaggio->cash->silver < $silver ||
                    $this->personaggio->cash->copper < $copper
                ) {
                    throw new \Exception(
                        "Non hai le monete della denominazione corretta per effettuare il pagamento esatto"
                    );
                }
                $this->personaggio->cash->platinum -= $platinum;
                $this->personaggio->cash->gold -= $gold;
                $this->personaggio->cash->silver -= $silver;
                $this->personaggio->cash->copper -= $copper;
            }
        }
    }

    public function refreshCambio()
    {
        $totalCopper = $this->get_totalcopper();
        $this->personaggio->cash = $this->ConvertValuta($totalCopper);
        $this->save();
    }

    public function ConvertValuta(int $copper): Cash
    {
        return self::ConvertiValuta($copper, $this->CAMBIO_PLATINUM, $this->CAMBIO_GOLD, $this->CAMBIO_SILVER);
    }

    public static function _ConvertValuta(int $copper): Cash
    {
        $config = self::loadCambi();
        $C_PLATINUM = $config["platinum"];
        $C_GOLD = $config["gold"];
        $C_SILVER = $config["silver"];
        return self::ConvertiValuta($copper, $C_PLATINUM, $C_GOLD, $C_SILVER);
    }
    private static function ConvertiValuta(int $copper, int $C_PLATINUM, int $C_GOLD, int $C_SILVER): Cash
    {
        $platinum = floor($copper / $C_PLATINUM);
        $copper -= $platinum * $C_PLATINUM;

        $gold = floor($copper / $C_GOLD);
        $copper -= $gold * $C_GOLD;

        $silver = floor($copper / $C_SILVER);
        $copper = $copper % $C_SILVER;

        return new Cash($platinum, $gold, $silver, $copper);
    }
    function get_totalcopper(): int
    {
        $cash = $this->personaggio->cash;
        if ($cash != null) {
            $totalCopper = ($cash->platinum * $this->CAMBIO_PLATINUM) +
                ($cash->gold * $this->CAMBIO_GOLD) +
                ($cash->silver * $this->CAMBIO_SILVER) +
                $cash->copper;

            return $totalCopper;
        }
        return 0;
    }


    public function getData(bool $encode = true)
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
            "totalcopper" => $this->get_totalcopper(),
        ];

        foreach ($this->personaggio->history as $cronologia) {
            $personaggioArray["history"][] = $cronologia->jsonSerialize();
        }

        foreach ($this->personaggio->suspended as $tipo => $transazioni) {
            foreach ($transazioni as $transazione) {
                $converted = $this->ConvertValuta($transazione->copper);
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
    public int $platinum;
    public int $gold;
    public int $silver;
    public int $copper;

    public function __construct(int $platinum, int $gold, int $silver, int $copper)
    {
        $this->platinum = $platinum;
        $this->gold = $gold;
        $this->silver = $silver;
        $this->copper = $copper;
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
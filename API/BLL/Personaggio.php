<?php
namespace BLL;
class Personaggio
{
    private $baseName;
    private $data;
    private $filePath;

    //quanti copper sono?
    private $CAMBIO_PLATINUM;
    private $CAMBIO_GOLD;
    private $CAMBIO_SILVER;

    public static function Crea($nome)
    {
        if(empty($nome))
            throw new \Exception("Valore nome non valido");

        $baseName = self::GetBaseName($nome);

        if (file_exists(Repository::getFileName($baseName))) {
            throw new \Exception($nome." esiste");
        }
        $p = Repository::getObj("personaggi");
        $p[] = ["basename" =>$baseName, "nome" => $nome];
        $p = Repository::putObj("personaggi",$p);
        
        file_put_contents(
            Repository::getFileName($baseName),
            json_encode([
                "name" => $nome,
                "cash" => [
                    "platinum" => 0,
                    "gold" => 0,
                    "silver" => 0,
                    "copper" => 0,
                ]
            ])
        );
        
        return Response::retOK($nome." creato");
    }

    public static function Elimina($baseName)
    {
        if (!file_exists(Repository::getFileName($baseName))) {
            throw new \Exception($baseName." non esiste ");
        }

        $p = Repository::getObj("personaggi");

        $index = array_search($baseName, $p);
        if ($index !== false) {
            unset($p[$index]);
            $p = array_values($p);
        } else {
            throw new \Exception($baseName." non trovato nell'elenco");
        }

        Repository::putObj("personaggi", $p);
        
        return Response::retOK($baseName." cancellato");
    }


    public static function GetBaseName($baseName)
    {
        return strtoupper(trim(preg_replace("/\s+/", "_", $baseName)));
    }

    public function __construct($baseName)
    {
        $config = Repository::getObj("configmoney");
        $this->CAMBIO_PLATINUM = $config["platinum"];
        $this->CAMBIO_GOLD = $config["gold"];
        $this->CAMBIO_SILVER = $config["silver"];

        $this->baseName = $baseName;
        $this->filePath = Repository::getFileName($baseName);

        $this->loadData();
    }

    private function loadData()
    {
        if (file_exists($this->filePath)) {
            $json = file_get_contents($this->filePath);
            $this->data = json_decode($json, true);
        } else {
            throw new \Exception("Personaggio non trovato");
        }
    }

    public function save()
    {
        file_put_contents($this->filePath, json_encode($this->data));
    }

    public function manageCharacterCoins(
        $transactionType,
        $platinum,
        $gold,
        $silver,
        $copper,
        $description,
        $canReceiveChange = false,
        $itemdescription = "",
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
                $platinum,
                $gold,
                $silver,
                $copper,
                $description,
                $canReceiveChange
            );
        }

        $totalCopperManaging =
            $platinum * $this->CAMBIO_PLATINUM +
            $gold * $this->CAMBIO_GOLD +
            $silver * $this->CAMBIO_SILVER +
            $copper;
        switch ($transactionType) {
            case "debt":
            case "credit":
                $this->data["suspended"][$transactionType][] = [
                    "copper" => $totalCopperManaging,
                    "description" => preg_replace("/\r\n|\n|\r/", " - ", $description),
                ];

                break;

            case "settle_debt":
            case "settle_credit":
                // Rimuovi il debito o il credito sanato
                $transactionKey =
                    $transactionType === "settle_debt" ? "debt" : "credit";
                $found = false;
                foreach (
                    $this->data["suspended"][$transactionKey]
                    as $key => $transaction
                ) {
                    if (
                        $transaction["copper"] === $totalCopperManaging &&
                        $transaction["description"] === $itemdescription
                    ) {
                        unset(
                            $this->data["suspended"][$transactionKey][$key]
                        );
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    throw new \Exception("Contratto non trovato.");
                }
                $this->data["suspended"][$transactionKey] = array_values(
                    $this->data["suspended"][$transactionKey]
                ); // Reindex array
                break;
        }

        $this->data["history"][] = [
            "date" => date("Y-m-d H:i:s"),
            "platinum" => $platinum,
            "gold" => $gold,
            "silver" => $silver,
            "copper" => $copper,
            "description" => $description,
            "type" => strtoupper($transactionType),
        ];

        $this->save();

        return Response::retOK(
            "Transazione ($transactionType) eseguita correttamente."
        );

    }

    function manageCurrency(
        $isAdding,
        $platinum = 0,
        $gold = 0,
        $silver = 0,
        $copper = 0,
        $canReceiveChange = true
    ) {
        if ($isAdding) {
            $this->data["cash"]["platinum"] += $platinum;
            $this->data["cash"]["gold"] += $gold;
            $this->data["cash"]["silver"] += $silver;
            $this->data["cash"]["copper"] += $copper;
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

                $this->data["cash"]["platinum"] = 0;
                $this->data["cash"]["gold"] = 0;
                $this->data["cash"]["silver"] = 0;
                $this->data["cash"]["copper"] = $totalCopper - $totalCopperToPay;

                $this->refreshCambio();
            } else {
                if (
                    $this->data["cash"]["platinum"] < $platinum ||
                    $this->data["cash"]["gold"] < $gold ||
                    $this->data["cash"]["silver"] < $silver ||
                    $this->data["cash"]["copper"] < $copper
                ) {
                    throw new \Exception(
                        "Non hai le monete della denominazione corretta per effettuare il pagamento esatto"
                    );
                }
                $this->data["cash"]["platinum"] -= $platinum;
                $this->data["cash"]["gold"] -= $gold;
                $this->data["cash"]["silver"] -= $silver;
                $this->data["cash"]["copper"] -= $copper;
            }
        }
    }

    function refreshCambio()
    {
        $this->data["cash"] = $this->ConvertValuta($this->get_totalcopper());
        $this->save();

    }

    private function ConvertValuta($copper)
    {
        $ret_data["platinum"] = floor(
            $copper / $this->CAMBIO_PLATINUM
        );
        $copper -= $ret_data["platinum"] * $this->CAMBIO_PLATINUM;

        $ret_data["gold"] = floor($copper / $this->CAMBIO_GOLD);
        $copper -= $ret_data["gold"] * $this->CAMBIO_GOLD;

        $ret_data["silver"] = floor($copper / $this->CAMBIO_SILVER);
        $ret_data["copper"] = $copper % $this->CAMBIO_SILVER;

        return $ret_data;
    }

    function get_totalcopper()
    {
        // Inizializza le variabili per il calcolo
        $platinum = isset($this->data["cash"]["platinum"])
            ? $this->data["cash"]["platinum"]
            : 0;
        $gold = isset($this->data["cash"]["gold"]) ? $this->data["cash"]["gold"] : 0;
        $silver = isset($this->data["cash"]["silver"]) ? $this->data["cash"]["silver"] : 0;
        $copper = isset($this->data["cash"]["copper"]) ? $this->data["cash"]["copper"] : 0;

        // Calcola il totale
        $totali = $platinum * $this->CAMBIO_PLATINUM;
        $totali += $gold * $this->CAMBIO_GOLD;
        $totali += $silver * $this->CAMBIO_SILVER;
        $totali += $copper;

        return $totali;
    }

    public function getData($encode = true)
    {
        $ret = $this->data;
        $ret["totalcopper"] = $this->get_totalcopper();
        $ret["basename"] = $this->baseName;
        
        if (isset($ret["suspended"]))
            foreach ($ret["suspended"] as $tipo =>$obj) {
                foreach ($obj as $key => $tran) {
                    $cop = $tran["copper"];

                    $ret["suspended"][$tipo][$key] = $this->ConvertValuta($tran["copper"]);
                    $ret["suspended"][$tipo][$key]['description'] = $tran['description'];
                    $ret["suspended"][$tipo][$key]['totalCopper'] = $cop;
                } 
            }
        
        if ($encode)
            return json_encode($ret);
        return $ret;
    }


        
    function deleteCronologia($data) {
        $trovato = false;
        foreach ($this->data["history"] as $key => $item) {
            if ($item['date'] == $data) {
                unset($this->data["history"][$key]);
                $trovato = true;
                break;
            }
        }
        if ($trovato) {
            // Reindirizza l'array dopo aver rimosso l'elemento
            $this->data["history"] = array_values($this->data["history"]);
            $this->save();
            return Response::retOK();
        } else {
            throw new \Exception('Elemento non trovato; Nessuna eliminazione');
        }
    }
}

?>

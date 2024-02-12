<?php

class Traduzione {

    private static string $path = __DIR__."/lang";
    
    /** @var array La lingua corrente */
    public $corrente = [];
    
    /** @var string lingua della pagina */
    public string $lang;

    public function __construct($l){
        if (empty($l)) return;
        
        $this->lang = $l;

        $files = glob(self::$path."/{$l}/*.json");
        foreach ($files as $file) {
            $this->corrente = array_merge($this->corrente, json_decode(file_get_contents($file), true));
        }
    }

    public static function listaLingue() {
        $result = [];
        $dirs = array_filter(glob(self::$path.'/*'), 'is_dir');
        foreach ($dirs as $dir) {
            $lingua = strtolower(basename($dir));
            if (!str_starts_with($lingua, '_'))
                $result[] = $lingua;
        }

        return $result;
    }

    /**
     * Tenta di tradurre una stringa (identificatore di traduzione) nella lingua corrente impostata per l'istanza.
     * @param string $sz L'identificatore della stringa da tradurre
     * @return string La stringa tradotta se disponibile; altrimenti, restituisce l'identificatore originale
     */
    function traduci($sz) {
        if (isset($this->corrente[$sz]) && !empty($this->corrente[$sz]))
            return $this->corrente[$sz]; 
        return $sz;
    }
}

?>

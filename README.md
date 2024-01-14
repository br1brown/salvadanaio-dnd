# Template-Sito
Il template fornisce una base solida per lo sviluppo di siti web generici, integrando una comunicazione backend-frontend chiara e sicura. Essendo progettato per essere flessibile è ideale per progetti che necessitano di una separazione netta tra le funzionalità lato client e lato server anche per interfacciarsi con terze parti.

## Compatibilità: *8.1.4*
**Importante**: Utilizza la versione 8.1.4 per garantire la massima compatibilità.

## Comunicazione con API
Questo template è caratterizzato da una particolare configurazione nella comunicazione con le API:
- **Comunicazione Esterna tramite HTTP**: Le API sono configurate per essere comunicate esclusivamente via HTTP, come se fossero su un server esterno. Questo approccio è sovrapponibile a un ambiente di produzione reale, separando le preoccupazioni tra frontend e backend.

    Per questa comunicazione, il template si affida alle **librerie cURL**. È fondamentale assicurarsi che **la connessione all'indirizzo dell'API sia aperta** e non bloccata da eventuali firewall o impostazioni di rete.

### Struttura del Template
Il template è suddiviso in due componenti principali:

1. **API Backend**
   - Le API sono collocate all'interno della cartella principale pur funzionando come un punto di accesso esterno.
   - **Middleware di Autenticazione e Gestione CORS (`BLL\auth_and_cors_middleware.php`):**
     - Questo componente si occupa dell'autenticazione tramite API key e della configurazione del CORS.
     - **Autenticazione API**: Utilizza un file di testo (`APIKeys.txt`) nella cartella `BLL/auth_settings/` per memorizzare e verificare le chiavi API. Blocca l'accesso con un codice di risposta HTTP 403 in caso di chiavi non valide o assenti.
     - **Gestione CORS**: Configura le politiche CORS tramite un file JSON (`CORSconfig.json`). Permette la comunicazione cross-origin solo da origini specificate e supporta metodi e header HTTP selezionati.
   - **Gestione dei Dati**:
     - La classe `BLL\Repository` facilita la comunicazione con i dati, offrendo diverse funzioni per l'interfaccia con i file. È possibile modificare l'approccio per utilizzare un database.
     - La classe `BLL\Response` è utilizzata per formattare risposte standard, inclusi messaggi di errore e conferma.
   - **Inclusione di File Comuni**:
     - Vengono inclusi file come `Repository.php`, `Response.php` e `funzioni_comuni.php` per supportare varie funzionalità comuni nel progetto.
   - **Endpoint Esempio**:
     - Gli endpoint `social.php` e `anagrafica.php` illustrano l'uso pratico delle funzioni di comunicazione dati.

2. **Frontend**
   - La classe `Service` aiuta a interagire con le API e a gestire funzionalità comuni, inclusa la manipolazione degli URL.
   - Include una libreria per convertire Markdown in HTML.
   - Il file `websettings.json` contiene impostazioni di base per il funzionamento del sito, valori per i <meta> compresi.


### Esempi Pratici

Per visualizzare un esempio pratico di come il template [Guarda un esempio](https://occhioalmondo.altervista.org/template-sito/).
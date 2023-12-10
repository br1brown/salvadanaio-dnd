
# Template-Sito

Il template ideale come punto di partenza per lo sviluppo di siti web generici, progettato per essere flessibile.

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
   - Gestiscono l'interazione con i dati, prevalentemente in formato JSON, è non in modo obbligato.
    - La classe `BLL\Repository` in `funzioni_comuni.php` facilita la comunicazione con i dati.
        - La classe `BLL\Repository` ha varie funzioni per interfacciarsi con i file ma nulla vieta di cambiare l'approccio per connettersi a un database.
    - La classe `BLL\Response` è usata per la formattazzione degli errori o dei messaggi di Ok.
   - Gli endpoint `social.php` e `anagrafica.php` dimostrano come utilizzare la funzione di comunicazione dati.

2. **Frontend**
   - La classe `Service` aiuta a interagire con le API e a gestire funzionalità comuni, inclusa la manipolazione degli URL.
   - Include una libreria per convertire Markdown in HTML.
   - Il file `websettings.json` contiene impostazioni di base per il funzionamento del sito, valori per i <meta> compresi.
   - `template_php` mostra la struttura richiesta per le pagine del sito.


### Esempi Pratici

Per visualizzare un esempio pratico di come il template [Guarda un esempio](https://occhioalmondo.altervista.org/template-sito/).
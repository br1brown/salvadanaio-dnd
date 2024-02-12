# Template per Sito Web

Questo template offre una solida base per lo sviluppo di siti web, fornendo già molte funzioni utili.
È progettato con flessibilità in mente, rendendolo ideale per progetti che richiedono una distinta separazione delle funzionalità client-server.

## Compatibilità
- **Versione Consigliata:** 8.1.4. È importante utilizzare questa versione per assicurare la piena compatibilità del template.

## Comunicazione API
Il template si distingue per la sua configurazione nella comunicazione con le API, che sono impostate per funzionare come se fossero ospitate su un server esterno. Questo permette di effettuare chiamate alle stesse sia dal sito web che da applicazioni di terze parti, _(utilizzando le librerie cURL per il frontend)_.

### Struttura
Il template è organizzato in due componenti principali:

1. **API Backend**
   - Collocato nella cartella `API` sotto la rout principale, funziona come un accesso esterno.
   - **Middleware di Autenticazione e Gestione CORS (`BLL\auth_and_cors_middleware.php`):**
     - Gestisce l'autenticazione tramite API key e la configurazione CORS, utilizzando un file di testo (`APIKeys.txt`) per le chiavi API e un file JSON (`CORSconfig.json`) per le politiche CORS.
   - **Gestione Dati:**
     - La classe `BLL\Repository` facilita la gestione dei dati, con opzioni per adattarsi a diversi sistemi di archiviazione.
     - `BLL\Response` standardizza le risposte, inclusi errori e conferme.
   - **Inclusione File Comuni:**
     - Supporta funzionalità comuni che si trovano in `funzioni_comuni.php`.
   - **Esempi di Endpoint:**
     - `social.php` e `anagrafica.php` dimostrano l'utilizzo delle API per la comunicazione dati.

2. **Frontend**
   - La classe `Service` semplifica l'interazione con le API e la gestione delle funzionalità comuni, inclusa la manipolazione degli URL.
   - Integra una libreria per convertire il Markdown in HTML.
   - `websettings.json` contiene le impostazioni di base del sito, inclusi i valori per i tag <meta>.
   - La gestione multilingua avviene tramite vari file JSON suddivisi per lingua all'interno di `FE_utils/lang/{codice lingua ISO 639-1}`,
    - Se la cartella inizia con un carattere `_` la lingue verrà esclusa.
   - Funzioni di traduzione (`traduci` in JavaScript e analogamente in `$service`) permettono di ottenere stringhe tradotte dai file JSON.

### Esempi Pratici
Per vedere il template in azione, visita [Guarda un esempio](https://occhioalmondo.altervista.org/template-sito/)



--- --- ---


# Creare progetti dal template
Se stai iniziando un nuovo progetto e vuoi utilizzare il template come fondamento, ma non sai come fare, esegui questo script nella cartella della repository.
```bash
git checkout -b main
git remote add template https://github.com/br1brown/template-sito.git
git fetch template
git branch template template/main
git pull template main --allow-unrelated-histories
git merge --squash template
git commit -m "Template Importato"
```

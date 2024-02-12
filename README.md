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

Se stai iniziando un nuovo progetto e vuoi utilizzare il template come fondamento, ma non sai come fare, segui questi passi per configurare il tuo ambiente di sviluppo. Questa guida assume che tu abbia Git sul PC e anche solo una conoscenza di base.

## 1. Inizia con il TUO Main
Prima di tutto, prepara il tuo ambiente di lavoro principale, lo qui ho chiamato basicamente `main`.
```bash
git checkout -b main
```

## 2. Aggiungi il Remote del Template
Ora che hai il tuo branch `main` configurato, procedi aggiungendo il mio template come un remote nel tuo progetto Git.
```bash
git remote add template https://github.com/br1brown/template-sito.git
```

## 3. Crea un Branch per il Template
Poi, crea un branch locale che servirà da riferimento al main del template.
```bash
git fetch template
git branch template template/main
```
Questo aiuta a separare il contenuto del template dalle tue modifiche personali.

## 4. Integra il Template nel Tuo Progetto
Tira le modifiche dal template al tuo progetto. Utilizza `--allow-unrelated-histories` per permettere a Git di fondere le storie non correlate, dato che stai iniziando un nuovo progetto.
```bash
git pull template main --allow-unrelated-histories
```

## 5. Prova il Merge dal Template
Poi, giusto per sicurezza, prova a mergiare dal branch `template` al tuo `main` per confermare che tutto si integri correttamente.
```bash
git merge template
```
E poi committa

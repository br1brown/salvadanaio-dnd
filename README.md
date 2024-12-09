# Template per Sito Web

Questo template fornisce una base robusta e versatile per lo sviluppo di siti web, con funzionalità preconfigurate e una chiara separazione tra client e server.

## Compatibilità
- **Versione consigliata:** 8.1.4 (utilizzare questa versione garantisce piena compatibilità).

## Comunicazione API
Il template è configurato per comunicare con API simulate come server esterni. Questo approccio consente l’integrazione sia con il sito web che con applicazioni di terze parti.

### Autenticazione API
- **Sicurezza avanzata:** Supporta il token **Bearer** per il frontend e il backend.
  - **Frontend:** il token viene gestito autonomamente in ogni chiamata API, funzioni nel file `manageAPI.js`.
  - **Backend:** il middleware `auth_and_cors_middleware.php` gestisce l’autenticazione e i permessi CORS.

---

## Struttura del Template

### 1. **Backend API**
Le API sono collocate nella cartella `API` e seguono una struttura modulare.

#### Middleware di Autenticazione e CORS
- **Posizione:** `BLL/auth_and_cors_middleware.php`.
- **Funzionalità:**
  - Gestisce autenticazione tramite **API key** e token **Bearer**.
  - Configurazioni esterne per:
    - **pwd.txt** (password),
    - **APIKeys.txt** (chiavi API nate per evitare recupero dei dati tramite crawler),
    - **CORSconfig.json** (politiche CORS).

#### Gestione Dati
- La classe `BLL\Repository` facilita l’accesso ai dati e supporta diversi sistemi di archiviazione.
- La classe `BLL\Response` standardizza le risposte API (es. errori o successi).

#### Logging
- **Classe dedicata:** `Logging`.
  - Scrive eventi di sistema (es. errori, avvisi) nei file di log con timestamp e categorie.
  - Supporta parametri extra per fornire dettagli contestuali.

#### Endpoint API
Gli endpoint seguono una struttura standard con funzioni per metodi HTTP (`GET`, `POST`, `PUT`, `DELETE`):
```php
<?php
include __DIR__ . '/BLL/auth_and_cors_middleware.php';

function esegui[metodo]()
{
    possoProcedere(); // Verifica autenticazione
    // Logica per gestire la richiesta
}

include __DIR__ . '/BLL/gestione_metodi.php';
```

- **Esempi di file API:** `social.php`, `anagrafica.php`.

---

### 2. **Frontend**
Il frontend è organizzato per offrire una gestione semplice delle API e della configurazione.

#### Caratteristiche principali
- **Classe `Service`:** Facilita l’interazione con le API e la manipolazione degli URL.
- **Gestione multilingua:** File JSON separati per lingua in `FE_utils/lang/{codice lingua ISO 639-1}`.
  - Le lingue con prefisso `_` sono escluse.
  - Funzioni di traduzione:
    - **JavaScript:** `traduci`.
    - **PHP:** metodo `$service`.
- **Markdown:** Integra una libreria per convertire Markdown in HTML.
- **Configurazione sito:** File `websettings.json` con impostazioni globali (es. tag `<meta>`).

---

## Esempi Pratici
- **Visualizza una demo:** [Guarda un esempio](https://occhioalmondo.altervista.org/template-sito/).

---

## Creazione di Progetti dal Template
Per iniziare un nuovo progetto basato su questo template, esegui il seguente script nella repository:
```bash
git checkout -b main
git remote add template https://github.com/br1brown/template-sito.git
git fetch template
git branch template template/main
git pull template main --allow-unrelated-histories
git merge --squash template
git commit -m "Template Importato"
```
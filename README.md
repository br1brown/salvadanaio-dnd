# Salvadanaio D&D

## Introduzione
**Salvadanaio D&D** è un'applicazione web creata per semplificare la gestione delle finanze per i giocatori di *Dungeons & Dragons* e i Dungeon Master. Grazie a questo strumento, è possibile monitorare e gestire le ricchezze dei personaggi in maniera efficace, utilizzando file JSON per la memorizzazione dei dati anziché un database convenzionale.

## Funzionalità principali
- **Gestione Facilitata**: Controllo agevole dei personaggi e delle loro risorse finanziarie.
- **Interfaccia Utente Intuitiva**: Facilita operazioni rapide e complesse attraverso un'interfaccia chiara.
- **Transazioni Automatizzate**: Gestione delle transazioni finanziarie con calcolo automatico dei cambi.
- **Facile Configurazione**: Personalizza facilmente l'esperienza di gioco secondo le tue preferenze.
- **Versatile**: Ideale per giocatori di D&D di tutti i livelli, dai principianti agli esperti.

## Guida Rapida all'Installazione
1. Clonare il repository nel proprio server, assicurandosi che sia compatibile con PHP 8.1.4 o versioni testate.
2. Modificare il file `API\data\configmoney.json` per adeguarlo alle proprie necessità di gestione valutaria.
3. Avviare il server e accedere all'applicazione tramite un browser web.

## Aggiungere Nuovi Personaggi
Per aggiungere nuovi personaggi, utilizzare una richiesta POST all'indirizzo `/API/characters` inserendo nel form il nome desiderato nella chiave `nome`. Assicurarsi di impostare correttamente l'header `X-Api-Key` usando le chiavi che si trovano nel file di configurazione `API\BLL\auth_settings\APIKeys.txt` per garantire il corretto funzionamento della richiesta.
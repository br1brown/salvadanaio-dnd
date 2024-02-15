let traduzione = []
var traduzioneCaricata = new Promise((resolve, reject) => {
    fetch(_pathtraduzione)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            traduzione = data;
            console.log('Traduzione caricata:', traduzione);
            resolve(); // Risolve la promessa
        })
        .catch(error => {
            console.error('Errore nel caricare la traduzione');
            //reject(error); // Rifiuta la promessa in caso di errore
            resolve(); // Risolve la promessa con l'oggetto traduzione
        });
});

// Funzione per tradurre una chiave
function traduci(chiave, ...args) {

    let stringaTradotta = traduzione[chiave] || chiave;

    // Sostituisce ogni segnaposto {n} con il corrispondente valore fornito
    args.forEach((valore, indice) => {
        stringaTradotta = stringaTradotta.replace(`{${indice}}`, valore);
    });

    return stringaTradotta;
}
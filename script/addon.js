// Questo script utilizza la libreria SweetAlert2 per mostrare avvisi personalizzati.
// La documentazione della libreria è disponibile su: https://sweetalert2.github.io/


function getTemplateUrl(type, params = null) {
    return "FE_utils/template/" + type + MakeGetQueryString(params);
}


function refreshcambio(basename) {
    apiCall("refresh_cambio", { basename }, function () {
        location.reload();
    });
}

function creditTransaction(basename, isCredit) {
    manageTransaction(basename, isCredit ? 'credito' : 'debito');
}


function manageMoney(basename, isReceiving) {
    manageTransaction(basename, isReceiving ? 'ricevi' : 'spendi', isReceiving);
}


function manageTransaction(basename, actionType, isReceiving = null) {
    var templateParams = {};
    if (isReceiving != null) {
        templateParams = { spendi: (isReceiving ? "0" : "1") }
    }


    const actionWord = traduci(actionType);
    $.get(getTemplateUrl("insert", templateParams), function (template) {
        SweetAlert.fire({
            title: actionWord + ":",
            html: template,
            confirmButtonText: actionWord + "!",
            focusConfirm: false,
            showCancelButton: true,
            preConfirm: () => {
                const platinum = Swal.getPopup().querySelector('#platinum').value;
                const gold = Swal.getPopup().querySelector('#gold').value;
                const silver = Swal.getPopup().querySelector('#silver').value;
                const copper = Swal.getPopup().querySelector('#copper').value;
                const description = Swal.getPopup().querySelector('#description').value;
                const canReceiveChange = isReceiving == false ? Swal.getPopup()?.querySelector('#canReceiveChange').checked : false;

                if (description == "" || (!description))
                    Swal.showValidationMessage(traduci('descrizioneMancante'));

                var bindCorrect = true;
                validanum = function (sznum, nome) {
                    if (sznum == "")
                        return 0;
                    var Num = parseFloat(sznum);
                    if (isNaN(Num)) {
                        bindCorrect = false;
                        Swal.showValidationMessage(traduci('erroreValidazione') + ' ' + nome);
                        return 0;
                    }
                    if (Num < 0) {
                        bindCorrect = false;
                        Swal.showValidationMessage(traduci('azioneNonPermessa') + ':' + ' ' + traduci('valoreNegativo'));
                    }
                    return Num;
                }

                var platinumNum = validanum(platinum, traduci("platino"));
                var goldNum = validanum(gold, traduci("oro"));
                var silverNum = validanum(silver, traduci("argento"));
                var copperNum = validanum(copper, traduci("rame"));
                if (bindCorrect && (platinumNum + goldNum + silverNum + copperNum == 0)) {
                    Swal.showValidationMessage(traduci('nessunaVariazione'));
                }

                return { platinum, gold, silver, copper, description, canReceiveChange };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                apiCall("manage/" + actionWord, {
                    basename,
                    platinum: result.value.platinum,
                    gold: result.value.gold,
                    silver: result.value.silver,
                    copper: result.value.copper,
                    canReceiveChange: result.value.canReceiveChange,
                    description: result.value.description
                }, function () {
                    location.reload();
                }, "POST");
            }
        });
    });
}


function sanaContratto(basename, isCredit, platinum, gold, silver, copper, itemdescription) {
    var word = (isCredit ? 'credito' : 'debito');
    SweetAlert.fire({
        title: traduci('seiSicuro'),
        html: traduci("Vuoi esaurire il " + word) + "<br>" + platinum + "p " + gold + "g " + silver + "s " + copper + "c?<br><small>" + traduci("modificheSuSoldi") + "</small>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: traduci('procedi') + '!',
        cancelButtonText: traduci('annulla')
    }).then((result) => {
        if (result.isConfirmed) {
            apiCall("manage/sana_" + (isCredit ? 'credito' : 'debito'), {
                basename,
                itemdescription,
                platinum,
                gold,
                silver,
                copper,
                description: word + " sanato"
            }, function () {
                location.reload();
            }, "POST");
        }
    });
}

function deleteSingleHistory(basename, datastoriacancellare, descrizione) {
    SweetAlert.fire({
        title: traduci('seiSicuro') + '?',
        html: traduci("vuoiEliminareQuesto") + " '" + descrizione + "'?<br><small>" + traduci("nonInficiaSulleSommeSomme") + "</small>",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: traduci('elimina') + '!',
        cancelButtonText: traduci('annulla')
    }).then((result) => {
        if (result.isConfirmed) {
            apiCall("delhistory", {
                basename,
                data: datastoriacancellare,
            }, function () {
                location.reload();
            }, "POST");
        }
    });
}
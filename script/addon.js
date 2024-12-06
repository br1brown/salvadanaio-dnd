// Costanti e helper per migliorare leggibilità e riutilizzo
const SweetAlert = Swal; // Alias per chiarezza

const getTemplateUrl = (type, params = null) =>
    `template/${type}${MakeGetQueryString(params)}`;

const validateNumber = (value, fieldName) => {
    if (value === "") return 0;
    const num = parseFloat(value);
    if (isNaN(num)) throw new Error(`${traduci('erroreValidazione')} ${fieldName}`);
    if (num < 0) throw new Error(`${traduci('azioneNonPermessa')}: ${traduci('valoreNegativo')}`);
    return num;
};

const extractFormValues = (isReceiving) => {
    const popup = Swal.getPopup();
    const platinum = popup.querySelector('#platinum').value;
    const gold = popup.querySelector('#gold').value;
    const silver = popup.querySelector('#silver').value;
    const copper = popup.querySelector('#copper').value;
    const description = popup.querySelector('#description').value;
    const canReceiveChange = !isReceiving ? popup.querySelector('#canReceiveChange')?.checked : false;

    if (!description) throw new Error(traduci('descrizioneMancante'));

    const values = {
        platinum: validateNumber(platinum, traduci("platino")),
        gold: validateNumber(gold, traduci("oro")),
        silver: validateNumber(silver, traduci("argento")),
        copper: validateNumber(copper, traduci("rame")),
        description,
        canReceiveChange,
    };

    const total = values.platinum + values.gold + values.silver + values.copper;
    if (total === 0) throw new Error(traduci('nessunaVariazione'));

    return values;
};

const showAlert = async (title, confirmText, templateParams, isReceiving, apiEndpoint, additionalData = {}) => {
    try {
        const template = await $.get(getTemplateUrl("insert", templateParams));
        const result = await SweetAlert.fire({
            title: `${title}:`,
            html: template,
            confirmButtonText: `${confirmText}!`,
            focusConfirm: false,
            showCancelButton: true,
            preConfirm: () => {
                try {
                    return extractFormValues(isReceiving);
                } catch (err) {
                    Swal.showValidationMessage(err.message);
                    return false;
                }
            }
        });

        if (result.isConfirmed) {
            apiCall(apiEndpoint, { ...result.value, ...additionalData }, () => location.reload(), "POST");
        }
    } catch (err) {
        console.error(err);
    }
};

// Funzione per gestire transazioni generiche
function manageTransaction(basename, isReceiving = null) {
    actionType = isReceiving ? 'ricevi' : 'spendi';
    const actionWord = traduci(actionType);
    const templateParams = isReceiving != null ? { checkResto: isReceiving ? "0" : "1" } : {};
    showAlert(actionWord, actionWord, templateParams, isReceiving, `manage/${actionType}`, { basename });
}

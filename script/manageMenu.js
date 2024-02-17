function ApplicaMenu(OpenMenuSelector, LeftClick, opzioniMenu) {
    var eventoClick = LeftClick ? 'click' : 'contextmenu';
    var uniqueIdCounter = 0; // Inizializza un contatore per i nomi univoci

    $(document).on(eventoClick, OpenMenuSelector, function (event) {
        if (!LeftClick)
            event.preventDefault(); // Questa linea impedisce al browser di aprire il suo menu contestuale

        event.stopPropagation();
        $('.context-menu').remove();
        var selectorSanitized = OpenMenuSelector.replace(/[^a-zA-Z0-9]/g, '_');

        let menuHtml = '<div class="context-menu"><ul>';
        opzioniMenu.forEach(opzione => {
            var uniqueFunctionName = 'func_' + selectorSanitized + uniqueIdCounter++;
            window[uniqueFunctionName] = opzione.function;
            menuHtml += `<li class=text-light onclick="${uniqueFunctionName}(${$(OpenMenuSelector).data('context-args')})">${opzione.text}</li>`;
        });
        menuHtml += '</ul></div>';

        $('body').append(menuHtml);
        $('.context-menu').css({ top: event.pageY, left: event.pageX }).show();
    });

    $(document).on('click', function () {
        $('.context-menu').remove();
    });
};

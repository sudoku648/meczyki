import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables-functions';

$(function() {
    const $dataTable = $('#match-games-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "data": "dateTime", "name": "dateTime", "targets": 2, "orderable": true },
            { "data": "gameType", "name": "gameType", "targets": 3, "orderable": false },
            { "data": "teams", "name": "teams", "targets": 4, "orderable": false },
        ]),
        ajax: {
            url: "/match_games/fetch",
        },
        order: [[2, 'desc']]
    });

    const inputName           = 'matchGames[]';
    const dataAttribute       = 'data-matchGameId';
    const checkSingleSelector = 'input[id^="checkbox_matchGame_"]';
    const checkAllId          = 'checkbox_matchGames_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#match-games-delete-batch',
    ];
    const buttonsSelectors    = [
        '#match-games-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var matchGameId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, matchGameId);
        });
        setMainCheckbox(checkSingleSelector, checkAllSelector);
    });

    $(document).on('change', checkAllSelector, function() {
        var checkbox = $(this);

        $(checkSingleSelector).each(function () {
            $(this).prop('checked', checkbox.is(':checked'));
            addOrRemoveInputFromForms($(this), dataAttribute, inputSelector, inputName, formSelectors);
        });
    });

    $(document).on('change', checkSingleSelector, function() {
        var checkbox = $(this);
        addOrRemoveInputFromForms(checkbox, dataAttribute, inputSelector, inputName, formSelectors);
        setMainCheckbox(checkSingleSelector, checkAllSelector);
    });

    $(document).on('change', formSelectors, function() {
        checkInputs(inputSelector, buttonsSelectors);
    });
});

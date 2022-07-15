import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables';

$(function() {
    const $dataTable = $('#match-games-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "dateTime", "targets": 2, "orderable": false },
            { "name": "gameType", "targets": 3, "orderable": false },
            { "name": "teams", "targets": 4, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_match_games",
        }
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

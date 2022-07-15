import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables-functions';

$(function() {
    const $dataTable = $('#game-types-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_game_types",
        }
    });

    const inputName           = 'gameTypes[]';
    const dataAttribute       = 'data-gameTypeId';
    const checkSingleSelector = 'input[id^="checkbox_gameType_"]';
    const checkAllId          = 'checkbox_gameTypes_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#game-types-delete-batch',
    ];
    const buttonsSelectors    = [
        '#game-types-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var gameTypeId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, gameTypeId);
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

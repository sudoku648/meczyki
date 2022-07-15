import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables';

$(function() {
    const $dataTable = $('#clubs-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_clubs",
        }
    });

    const inputName           = 'clubs[]';
    const dataAttribute       = 'data-clubId';
    const checkSingleSelector = 'input[id^="checkbox_club_"]';
    const checkAllId          = 'checkbox_clubs_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#clubs-delete-batch',
    ];
    const buttonsSelectors    = [
        '#clubs-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var clubId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, clubId);
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

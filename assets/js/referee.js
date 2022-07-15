import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables-functions';

$(function() {
    const $dataTable = $('#referees-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_referees",
        }
    });

    const inputName           = 'referees[]';
    const dataAttribute       = 'data-refereeId';
    const checkSingleSelector = 'input[id^="checkbox_referee_"]';
    const checkAllId          = 'checkbox_referees_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#referees-delete-batch',
    ];
    const buttonsSelectors    = [
        '#referees-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var refereeId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, refereeId);
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

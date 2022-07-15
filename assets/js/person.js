import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables';

$(function() {
    const $dataTable = $('#people-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
            { "name": "functions", "targets": 3, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_people",
        }
    });

    const inputName           = 'people[]';
    const dataAttribute       = 'data-personId';
    const checkSingleSelector = 'input[id^="checkbox_person_"]';
    const checkAllId          = 'checkbox_people_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#people-delete-batch',
    ];
    const buttonsSelectors    = [
        '#people-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var personId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, personId);
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

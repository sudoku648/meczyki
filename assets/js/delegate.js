import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables-functions';

$(function() {
    const $dataTable = $('#delegates-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_delegates",
        }
    });

    const inputName           = 'delegates[]';
    const dataAttribute       = 'data-delegateId';
    const checkSingleSelector = 'input[id^="checkbox_delegate_"]';
    const checkAllId          = 'checkbox_delegates_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#delegates-delete-batch',
    ];
    const buttonsSelectors    = [
        '#delegates-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var delegateId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, delegateId);
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

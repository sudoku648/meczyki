import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables';

$(function() {
    const $dataTable = $('#users-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "username", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_users",
        }
    });

    const inputName           = 'users[]';
    const dataAttribute       = 'data-userId';
    const checkSingleSelector = 'input[id^="checkbox_user_"]';
    const checkAllId          = 'checkbox_users_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#users-activate-batch',
        'form#users-deactivate-batch',
        'form#users-delete-batch',
    ];
    const buttonsSelectors    = [
        '#users-activate-batch-btn',
        '#users-deactivate-batch-btn',
        '#users-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var userId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, userId);
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

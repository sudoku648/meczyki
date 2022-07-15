import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables';

$(function() {
    const $dataTable = $('#referee-observers-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_referee_observers",
        }
    });

    const inputName           = 'refereeObservers[]';
    const dataAttribute       = 'data-refereeObserverId';
    const checkSingleSelector = 'input[id^="checkbox_refereeObserver_"]';
    const checkAllId          = 'checkbox_refereeObservers_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#referee-observers-delete-batch',
    ];
    const buttonsSelectors    = [
        '#referee-observers-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var refereeObserverId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, refereeObserverId);
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

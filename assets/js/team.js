import {checkInputs, setMainCheckbox, addOrRemoveInputFromForms, removeInput} from './dataTables-functions';

$(function() {
    const $dataTable = $('#teams-list');

    $dataTable.dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
            { "name": "club", "targets": 3, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_teams",
        }
    });

    const inputName           = 'teams[]';
    const dataAttribute       = 'data-teamId';
    const checkSingleSelector = 'input[id^="checkbox_team_"]';
    const checkAllId          = 'checkbox_teams_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';
    const inputSelector       = 'input[name=\''+inputName+'\']';
    const formSelectors       = [
        'form#teams-delete-batch',
    ];
    const buttonsSelectors    = [
        '#teams-delete-batch-btn',
    ];

    checkInputs(inputSelector, buttonsSelectors);

    $dataTable.DataTable().on('draw', function () {
        $(inputSelector).each(function () {
            var teamId = $(this).attr('value');
            removeInput(inputSelector, formSelectors, teamId);
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

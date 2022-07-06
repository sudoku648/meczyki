$(function() {
    $('#game-types-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_game_types",
        }
    });

    const deleteButton = $('#game-types-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_gameType_"]', function() {
        var gameTypeId = $(this).attr('data-gameTypeId');

        if ($(this).is(":checked")) {
            var newInput = "<input name='gameTypes[]' type='hidden' value='"+gameTypeId+"'>";
            $('form#game-types-delete-batch').append(newInput);
        } else {
            $("input[value='"+gameTypeId+"']").remove();
        }

        if ($('input[id^="checkbox_gameType_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

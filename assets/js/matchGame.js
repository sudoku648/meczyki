$(function() {
    $('#match-games-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "dateTime", "targets": 2, "orderable": false },
            { "name": "gameType", "targets": 3, "orderable": false },
            { "name": "teams", "targets": 4, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_match_games",
        }
    });

    const deleteButton = $('#match-games-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_matchGame_"]', function() {
        var matchGameId = $(this).attr('data-matchGameId');

        if ($(this).is(":checked")) {
            var newInput = $("<input name='matchGames[]' type='hidden' value='"+matchGameId+"'>");
            $('form#match-games-delete-batch').append(newInput);
        } else {
            $("input[value='"+matchGameId+"']").remove();
        }

        if ($('input[id^="checkbox_matchGame_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

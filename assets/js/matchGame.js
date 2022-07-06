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

    const $deleteButton       = $('#match-games-delete-batch-btn');
    const checkSingleSelector = 'input[id^="checkbox_matchGame_"]';
    const checkAllId          = 'checkbox_matchGames_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';

    $deleteButton.attr('disabled', 'disabled');

    $(document).on('change', checkSingleSelector+', '+checkAllSelector, function() {
        var all = $(checkSingleSelector).length;

        if ($(this).attr('id').startsWith(checkAllId)) {
            if ($(this).is(':checked')) {
                $(checkSingleSelector).each(function() {
                    $(this).prop('checked', true);
                });
            } else {
                $(checkSingleSelector).each(function() {
                    $(this).prop('checked', false);
                });
            }
        }

        var checked = $(checkSingleSelector).filter(':checked').length;

        $(checkSingleSelector).each(function() {
            var matchGameId = $(this).attr('data-matchGameId');

            $('input[value="'+matchGameId+'"]').remove();

            if ($(this).is(':checked')) {
                var newInput = '<input name="matchGames[]" type="hidden" value="'+matchGameId+'">';

                $('form#match-games-delete-batch').append(newInput);
            }
        });

        if (checked > 0) {
            $deleteButton.removeAttr('disabled');

            if (checked === all) {
                $(checkAllSelector).prop({'checked': true, 'indeterminate': false});
            } else {
                $(checkAllSelector).prop({'checked': false, 'indeterminate': true});
            }
        } else {
            $deleteButton.attr('disabled', 'disabled');

            $(checkAllSelector).prop({'checked': false, 'indeterminate': false});
        }
    });
});

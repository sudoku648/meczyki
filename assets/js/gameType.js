$(function() {
    $('#game-types-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_game_types",
        }
    });

    const $deleteButton       = $('#game-types-delete-batch-btn');
    const checkSingleSelector = 'input[id^="checkbox_gameType_"]';
    const checkAllId          = 'checkbox_gameTypes_all';
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
            var gameTypeId = $(this).attr('data-gameTypeId');

            $('input[value="'+gameTypeId+'"]').remove();

            if ($(this).is(':checked')) {
                var newInput = '<input name="gameTypes[]" type="hidden" value="'+gameTypeId+'">';

                $('form#game-types-delete-batch').append(newInput);
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

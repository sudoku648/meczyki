$(function() {
    $('#clubs-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_clubs",
        }
    });

    const $deleteButton       = $('#clubs-delete-batch-btn');
    const checkSingleSelector = 'input[id^="checkbox_club_"]';
    const checkAllId          = 'checkbox_clubs_all';
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
            var clubId = $(this).attr('data-clubId');

            $('input[value="'+clubId+'"]').remove();

            if ($(this).is(':checked')) {
                var newInput = '<input name="clubs[]" type="hidden" value="'+clubId+'">';

                $('form#clubs-delete-batch').append(newInput);
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

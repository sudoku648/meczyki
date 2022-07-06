$(function() {
    $('#users-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "username", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_users",
        }
    });

    const $activateButton     = $('#users-activate-batch-btn');
    const $deactivateButton   = $('#users-deactivate-batch-btn');
    const $deleteButton       = $('#users-delete-batch-btn');
    const checkSingleSelector = 'input[id^="checkbox_user_"]';
    const checkAllId          = 'checkbox_users_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';

    $activateButton.attr('disabled', 'disabled');
    $deactivateButton.attr('disabled', 'disabled');
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
            var userId = $(this).attr('data-userId');

            $('input[value="'+userId+'"]').remove();

            if ($(this).is(':checked')) {
                var newInput = '<input name="users[]" type="hidden" value="'+userId+'">';

                $('form#users-activate-batch').append(newInput);
                $('form#users-deactivate-batch').append(newInput);
                $('form#users-delete-batch').append(newInput);
            }
        });

        if (checked > 0) {
            $activateButton.removeAttr('disabled');
            $deactivateButton.removeAttr('disabled');
            $deleteButton.removeAttr('disabled');

            if (checked === all) {
                $(checkAllSelector).prop({'checked': true, 'indeterminate': false});
            } else {
                $(checkAllSelector).prop({'checked': false, 'indeterminate': true});
            }
        } else {
            $activateButton.attr('disabled', 'disabled');
            $deactivateButton.attr('disabled', 'disabled');
            $deleteButton.attr('disabled', 'disabled');

            $(checkAllSelector).prop({'checked': false, 'indeterminate': false});
        }
    });
});

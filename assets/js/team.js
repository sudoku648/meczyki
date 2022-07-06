$(function() {
    $('#teams-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
            { "name": "club", "targets": 3, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_teams",
        }
    });

    const $deleteButton       = $('#teams-delete-batch-btn');
    const checkSingleSelector = 'input[id^="checkbox_team_"]';
    const checkAllId          = 'checkbox_teams_all';
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
            var teamId = $(this).attr('data-teamId');

            $('input[value="'+teamId+'"]').remove();

            if ($(this).is(':checked')) {
                var newInput = '<input name="teams[]" type="hidden" value="'+teamId+'">';

                $('form#teams-delete-batch').append(newInput);
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

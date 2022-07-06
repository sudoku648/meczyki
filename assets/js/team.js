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

    const deleteButton = $('#teams-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_team_"]', function() {
        var teamId = $(this).attr('data-teamId');

        if ($(this).is(":checked")) {
            var newInput = "<input name='teams[]' type='hidden' value='"+teamId+"'>";
            $('form#teams-delete-batch').append(newInput);
        } else {
            $("input[value='"+teamId+"']").remove();
        }

        if ($('input[id^="checkbox_team_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

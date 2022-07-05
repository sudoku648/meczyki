$(function() {
    $('#clubs-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_clubs",
        }
    });

    const deleteButton = $('#clubs-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_club_"]', function() {
        var clubId = $(this).attr('data-clubId');

        if ($(this).is(":checked")) {
            var newInput = $("<input name='clubs[]' type='hidden' value='"+clubId+"'>");
            $('form#clubs-delete-batch').append(newInput);
        } else {
            $("input[value='"+clubId+"']").remove();
        }

        if ($('input[id^="checkbox_club_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

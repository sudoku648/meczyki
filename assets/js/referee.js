$(function() {
    $('#referees-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_referees",
        }
    });

    const deleteButton = $('#referees-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_referee_"]', function() {
        var refereeId = $(this).attr('data-refereeId');

        if ($(this).is(":checked")) {
            var newInput = $("<input name='referees[]' type='hidden' value='"+refereeId+"'>");
            $('form#referees-delete-batch').append(newInput);
        } else {
            $("input[value='"+refereeId+"']").remove();
        }

        if ($('input[id^="checkbox_referee_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

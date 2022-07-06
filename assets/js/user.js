$(function() {
    $('#users-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "username", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_users",
        }
    });

    const activateButton = $('#users-activate-batch-btn');
    const deactivateButton = $('#users-deactivate-batch-btn');
    const deleteButton = $('#users-delete-batch-btn');

    activateButton.attr('disabled', 'disabled');
    deactivateButton.attr('disabled', 'disabled');
    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_user_"]', function() {
        var userId = $(this).attr('data-userId');

        if ($(this).is(":checked")) {
            var newInput = "<input name='users[]' type='hidden' value='"+userId+"'>";
            $('form#users-activate-batch').append(newInput);
            $('form#users-deactivate-batch').append(newInput);
            $('form#users-delete-batch').append(newInput);
        } else {
            $("input[value='"+userId+"']").remove();
        }

        if ($('input[id^="checkbox_user_"]').filter(':checked').length > 0) {
            activateButton.removeAttr('disabled');
            deactivateButton.removeAttr('disabled');
            deleteButton.removeAttr('disabled');
        } else {
            activateButton.attr('disabled', 'disabled');
            deactivateButton.attr('disabled', 'disabled');
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

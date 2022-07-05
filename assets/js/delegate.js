$(function() {
    $('#delegates-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_delegates",
        }
    });

    const deleteButton = $('#delegates-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_delegate_"]', function() {
        var delegateId = $(this).attr('data-delegateId');

        if ($(this).is(":checked")) {
            var newInput = $("<input name='delegates[]' type='hidden' value='"+delegateId+"'>");
            $('form#delegates-delete-batch').append(newInput);
        } else {
            $("input[value='"+delegateId+"']").remove();
        }

        if ($('input[id^="checkbox_delegate_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

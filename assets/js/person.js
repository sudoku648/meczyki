$(function() {
    $('#people-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
            { "name": "functions", "targets": 3, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_people",
        }
    });

    const deleteButton = $('#people-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_person_"]', function() {
        var personId = $(this).attr('data-personId');

        if ($(this).is(":checked")) {
            var newInput = $("<input name='people[]' type='hidden' value='"+personId+"'>");
            $('form#people-delete-batch').append(newInput);
        } else {
            $("input[value='"+personId+"']").remove();
        }

        if ($('input[id^="checkbox_person_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

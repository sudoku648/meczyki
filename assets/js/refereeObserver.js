$(function() {
    $('#referee-observers-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_referee_observers",
        }
    });

    const deleteButton = $('#referee-observers-delete-batch-btn');

    deleteButton.attr('disabled', 'disabled');

    $(document).on('change', 'input[id^="checkbox_refereeObserver_"]', function() {
        var refereeObserverId = $(this).attr('data-refereeObserverId');

        if ($(this).is(":checked")) {
            var newInput = $("<input name='refereeObservers[]' type='hidden' value='"+refereeObserverId+"'>");
            $('form#referee-observers-delete-batch').append(newInput);
        } else {
            $("input[value='"+refereeObserverId+"']").remove();
        }

        if ($('input[id^="checkbox_refereeObserver_"]').filter(':checked').length > 0) {
            deleteButton.removeAttr('disabled');
        } else {
            deleteButton.attr('disabled', 'disabled');
        }
    });
});

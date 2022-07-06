$(function() {
    $('#delegates-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_delegates",
        }
    });

    const $deleteButton       = $('#people-delete-batch-btn');
    const checkSingleSelector = 'input[id^="checkbox_person_"]';
    const checkAllId          = 'checkbox_people_all';
    const checkAllSelector    = 'input[id="'+checkAllId+'"]';

    $deleteButton.attr('disabled', 'disabled').parent('form').addClass('cursor-not-allowed');

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
            var personId = $(this).attr('data-personId');

            $('input[value="'+personId+'"]').remove();

            if ($(this).is(':checked')) {
                var newInput = '<input name="people[]" type="hidden" value="'+personId+'">';

                $('form#people-delete-batch').append(newInput);
            }
        });

        if (checked > 0) {
            $deleteButton.removeAttr('disabled').parent('form').removeClass('cursor-not-allowed');

            if (checked === all) {
                $(checkAllSelector).prop({'checked': true, 'indeterminate': false});
            } else {
                $(checkAllSelector).prop({'checked': false, 'indeterminate': true});
            }
        } else {
            $deleteButton.attr('disabled', 'disabled').parent('form').addClass('cursor-not-allowed');

            $(checkAllSelector).prop({'checked': false, 'indeterminate': false});
        }
    });
});

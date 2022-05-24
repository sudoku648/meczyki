import 'bootstrap';

$(function() {
    $(document).on('submit', 'form[data-confirmation]', function(event) {
        var $form = $(this);
        var $confirm = $('#staticBackdrop');

        if ($confirm.data('result') !== 'yes') {
            event.preventDefault();

            $confirm
                .off('click', '#btnYes')
                .on('click', '#btnYes', function() {
                    $confirm.data('result', 'yes');
                    var $button = $form.find('button[type="submit"]');

                    $button.removeClass(function(index, css) {
                        return (css.match (/(^|\s)icon-\S+/g) || []).join(' ');
                    });
                    $button.html('Usuwanie...');

                    $form.trigger('submit');
                })
                .modal('show');
        }
    });
});

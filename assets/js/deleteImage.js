function deleteImage(element) {
    var url = $(element).attr('href');
    var container = $(element).closest('.image-container');

    $.ajax({
        method: 'POST',
        url: url,
        beforeSend: function() {
            $(element).hide();
            container.fadeOut(800);
        },
    })
    .done(function(data) {
        container.html(data.message).show().delay(2000).fadeOut(800, function() {
            $(this).remove();
        });
    });
}

$(function() {
    $('.delete-image').on('click', function(e) {
        e.preventDefault();

        deleteImage(this);
    })
});

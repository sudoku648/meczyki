$(function() {
    var pos = 1;

    $($.fn.dataTable.tables()).each(function() {
        var table = $(this);

        table.find('thead th').each(function() {
            if ($(this).hasClass('s')) {
                var title = $(this).text();
                var search = table.dataTable().api().column(pos - 1).search();
                $(this).html(
                    "<input value='"+search+"' id='table-input"+pos+"' type='text' class='form-control' placeholder='"+title+"' />"
                );
            }

            pos++;
        });
    });

    pos = 1;
    $($.fn.dataTable.tables()).each(function() {
        var table = $(this);

        table.dataTable().api().columns().every(function() {
            var that = this;

            $('#table-input'+pos).on('keyup change clear', function() {
                if (that.search() !== this.value) {
                    that
                        .search(this.value)
                        .draw();
                }
            });
            pos++;
        });
    });
});

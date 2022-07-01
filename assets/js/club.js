$(function() {
    $('#clubs-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 1, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_clubs",
        }
    });
});

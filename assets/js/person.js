$(function() {
    $('#people-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 1, "orderable": false },
            { "name": "functions", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_people",
        }
    });
});

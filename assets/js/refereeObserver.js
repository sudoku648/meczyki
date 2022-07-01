$(function() {
    $('#referee-observers-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "fullName", "targets": 1, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_referee_observers",
        }
    });
});

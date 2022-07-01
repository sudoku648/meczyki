$(function() {
    $('#users-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "username", "targets": 1, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_users",
        }
    });
});

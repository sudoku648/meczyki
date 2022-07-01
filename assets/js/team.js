$(function() {
    $('#teams-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 1, "orderable": false },
            { "name": "club", "targets": 2, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_teams",
        }
    });
});

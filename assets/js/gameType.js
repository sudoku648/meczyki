$(function() {
    $('#game-types-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "name", "targets": 1, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_game_types",
        }
    });
});

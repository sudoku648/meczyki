$(function() {
    $('#match-games-list').dataTable({
        columnDefs: $.fn.dataTable.defaults.columnDefs.concat([
            { "name": "dateTime", "targets": 1, "orderable": false },
            { "name": "gameType", "targets": 2, "orderable": false },
            { "name": "teams", "targets": 3, "orderable": false },
        ]),
        ajax: {
            url: "/ajax/fetch_match_games",
        }
    });
});

$(function() {
    var pos = 1;

    var lang = $('html').attr('lang');
    if (lang === 'pl') lang = '';
    else lang = '/'+lang;

    var table = $('#referees-list').dataTable({
        pageLength: 10,
        stateSave: true,
        language: {
            "oAria": {
                "sSortAscending": ": aktywuj, aby posortować kolumnę rosnąco",
                "sSortDescending": ": aktywuj, aby posortować kolumnę malejąco"
            },
            "oPaginate": {
                "sFirst": "Pierwsza",
                "sLast": "Ostatnia",
                "sNext": "Następna",
                "sPrevious": "Poprzednia"
            },
            "sEmptyTable": "Brak danych",
            "sInfo": "Pozycje od _START_ do _END_ z _TOTAL_ łącznie",
            "sInfoEmpty": "Pozycji 0 z 0 dostępnych",
            "sInfoFiltered": "(filtrowanie spośród _MAX_ dostępnych pozycji)",
            "sInfoPostFix": "",
            "sDecimal": "",
            "sThousands": ",",
            "sLengthMenu": "Pokaż _MENU_ pozycji",
            "sLoadingRecords": "Ładowanie...",
            "sProcessing": "Przetwarzanie...",
            "sSearch": "Szukaj:",
            "sSearchPlaceholder": "Wpisz coś...",
            "sUrl": "",
            "sZeroRecords": "Nie znaleziono żadnych pasujących indeksów"
        },
        info: true,
        lengthMenu: [[5, 10, 20, 50, -1], [5, 10, 20, 50, 'Wszystko']],
        paginationType: 'full_numbers',
        columnDefs: [
            { "name": "lp", "targets": 0, "searchable": false, "orderable": false, "width": "1px" },
            { "name": "fullName", "targets": 1, "orderable": false },
            { "name": "buttons", "targets": -1, "searchable": false, "orderable": false, "class": "buttons" }
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: "/ajax/fetch_referees",
            type: "POST"
        },
        searching: true,
        order: [[1, 'asc']]
    });

    $('#referees-list thead th').each(function() {
        if ($(this).hasClass('s')) {
            var title = $(this).text();
            $(this).html("<input value='"+table.api().column(pos - 1).search()+"' id='table-input"+pos+"' type='text' class='form-control' placeholder='"+title+"' />");
        }

        pos++;
    });

    pos = 1;
    table.api().columns().every(function() {
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

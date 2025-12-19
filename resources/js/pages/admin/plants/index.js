$('#plantTable').DataTable({
    processing: false,
    serverSide: true,
    ajax: "/admin/plants",
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false,
        className: 'text-center'
    },
    {
        data: 'plant_name',
        name: 'plant_name',
        searchable: true,
        orderable: false,
    },
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false,
        className: 'text-center'
    }
    ]
});

$('#plantExport').on('click', function (e) {
    e.preventDefault();
    let params = $('#plantTable').DataTable().ajax.params();
    let value = params.search.value;
    window.location.href = '/plants/export?param=' + value;
});
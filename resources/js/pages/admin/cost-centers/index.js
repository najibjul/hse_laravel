$('#costCenterTable').DataTable({
    processing: false,
    serverSide: true,
    ajax: "/admin/cost-centers",
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false,
        width: '1%'
    },
    {
        data: 'costCenter',
        name: 'costCenter',
        orderable: false,
        searchable: false
    },
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
    }],
    columnDefs: [
        { targets: [0, 2], className: 'text-center' }
    ]
});

$('#costCenterExport').on('click', function (e) {
    e.preventDefault();
    let params = $('#costCenterTable').DataTable().ajax.params();
    let value = params.search.value;
    window.location.href = '/cost-centers/export?param=' + value;
});
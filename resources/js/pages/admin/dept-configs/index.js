$('#dept-config').DataTable({
    processing: false,
    serverSide: true,
    ajax: "/admin/dept-config",
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'admin',
        name: 'admin',
        searchable: true
    },
    {
        data: 'department',
        name: 'department',
        searchable: true
    },
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
    }
    ],
    columnDefs: [
        {
            targets: [3],
            className: 'text-center'
        }]
});

$('#admin-department-export').on('click', function (e) {
    e.preventDefault();
    let params = $('#dept-config').DataTable().ajax.params();
    let value = params.search.value;
    window.location.href = '/dept-config/export?param=' + value;
});
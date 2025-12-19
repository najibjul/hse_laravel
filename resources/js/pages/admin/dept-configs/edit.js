$('#admin-dept-config').DataTable({
    processing: false,
    serverSide: true,
    ajax: "/admin/dept-config/create",
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'name',
        name: 'name',
        searchable: true
    },
    {
        data: 'nip',
        name: 'nip',
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
        }
    ]
});

let dataAdmin = $('#data-admin').data('admin');

$('#master-departemen-table').DataTable({
    processing: false,
    serverSide: true,
    ajax: `/admin/master-departemen-table/${dataAdmin}`,
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'department_name',
        name: 'department_name',
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
            targets: [2],
            className: 'text-center'
        }
    ]
});

$('#akses-departemen-table').DataTable({
    processing: false,
    serverSide: true,
    ajax: `/admin/akses-departemen-table/${dataAdmin}`,
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
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
            targets: [2],
            className: 'text-center'
        }
    ]
});
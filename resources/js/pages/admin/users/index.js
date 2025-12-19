$('#userTable').DataTable({
    processing: false,
    serverSide: true,
    ajax: "/admin/users",
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false,
    },
    {
        data: 'name',
        name: 'name',
        orderable: false,
        searchable: false
    },
    {
        data: 'nip',
        name: 'nip',
        orderable: false,
        searchable: false,
    },
    {
        data: 'email',
        name: 'email',
        orderable: false,
        searchable: false,
    },
    {
        data: 'costCenter',
        name: 'costCenter',
        orderable: false,
        searchable: false,
    },
    {
        data: 'department',
        name: 'department',
        orderable: false,
        searchable: false,
    },
    {
        data: 'role',
        name: 'role',
        orderable: false,
        searchable: false,
    },
    {
        data: 'position',
        name: 'position',
        orderable: false,
        searchable: false,
    },
    {
        data: 'plant',
        name: 'plant',
        orderable: false,
        searchable: false,
    },
    {
        data: 'leader',
        name: 'leader',
        orderable: false,
        searchable: false
    },
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false,
    }]
});

$('#userExport').on('click', function (e) {
    e.preventDefault();
    let params = $('#userTable').DataTable().ajax.params();
    let value = params.search.value;
    window.location.href = '/users/export?param=' + value;
});
$('#departmentTable').DataTable({
    processing: false,
    serverSide: true,
    ajax: "/admin/departments",
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false,
        className: 'text-center'

    },
    {
        data: 'department_name',
        name: 'department_name',
        orderable: false,
        searchable: false
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

$('#departmentExport').on('click', function (e) {
    e.preventDefault();
    let params = $('#departmentTable').DataTable().ajax.params();
    let value = params.search.value;
    window.location.href = '/departments/export?param=' + value;
});
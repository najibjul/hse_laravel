const qrpTable = $('#qrpTable').DataTable({
    processing: false,
    serverSide: true,
    dom: 'lrtip',
    ajax: {
        url: '/daily-checking',
        data: function (d) {
            d.cari_user = $('#cari-user').val();
            d.cari_aktifitas = $('#cari-aktifitas').val();
            d.cari_area = $('#cari-area').val();
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.cari_faktor = $('#cari-faktor').val();
            d.cari_cek = $('#cari-cek').val();
            d.cari_status = $('#cari-status').val();
        }
    },
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'user',
        name: 'user',
        searchable: true,
        orderable: false,
    },
    {
        data: 'description',
        name: 'description',
        searchable: true,
        orderable: false
    },
    {
        data: 'area',
        name: 'area',
        searchable: true,
        orderable: false
    },
    {
        data: 'created_at',
        name: 'created_at',
        searchable: true,
        orderable: false,
    },
    {
        data: 'factor',
        name: 'factor',
        searchable: true,
        orderable: false,
    },
    {
        data: 'check_status',
        name: 'check_status',
        searchable: true,
        orderable: false,
    },
    {
        data: 'status',
        name: 'status',
        searchable: true,
        orderable: false,
    },
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
    }
    ],
    columnDefs: [
        { targets: [4,5,6,7,8], className: 'text-center' }
    ]
});

$('#start_date, #end_date').on('change', function (e) {
    e.preventDefault();
    if ($('#start_date').val != "" && $('#end_date').val != "") {
        qrpTable.draw();
    }
})

let debounceTimer;
$('#cari-user, #cari-aktifitas, #cari-area').on('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        qrpTable.draw();
    }, 1000); 
})

$('#cari-faktor, #cari-cek, #cari-status').on('change', function (e) {
    e.preventDefault();
    qrpTable.draw();
})

$('#qrpExport').on('click', function (e) {
    e.preventDefault();

    $('#export-cari-user').val($('#cari-user').val());
    $('#export-cari-aktifitas').val($('#cari-aktifitas').val());
    $('#export-cari-area').val($('#cari-area').val());
    $('#export-start-date').val($('#start_date').val());
    $('#export-end-date').val($('#end_date').val());
    $('#export-cari-faktor').val($('#cari-faktor').val());
    $('#export-cari-cek').val($('#cari-cek').val());
    $('#export-cari-status').val($('#cari-status').val());

    $('#qrpExportForm').submit();
});

// $('#qrpExport').on('click', function (e) {
//     e.preventDefault();
    
//     window.location.href = '/qrp/export?cari_user=' + $('#cari-user').val() + '&cari_aktifitas=' + $('#cari-aktifitas').val() + '&cari_area=' + $('#cari-area').val() + '&start_date=' + $('#start_date').val()+ '&end_date=' + $('#end_date').val() + '&cari_faktor=' + $('#cari-faktor').val() + '&cari_cek=' + $('#cari-cek').val() + '&cari_status=' + $('#cari-status').val();
// });
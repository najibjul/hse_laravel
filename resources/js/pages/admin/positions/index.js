const positionTable = $('#positionTable').DataTable({
    processing: false,
    serverSide: true,
    ajax: {
        url: "/admin/positions",
        data: function (d) {
            d.isQrpEnabled = $('#isQrpEnabled').val();
        }
    },
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex',
        orderable: false,
        searchable: false
    },
    {
        data: 'position_name',
        name: 'position_name',
        orderable: false,
        searchable: true
    },
    {
        data: 'safety_comitee',
        name: 'safety_comitee',
        orderable: false,
        searchable: false,
        className: 'text-center'
    },
    {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false,
        className: 'text-center'
    }],
    initComplete: function () {
        let select = $(
            `<label>
                    <select id="isQrpEnabled" class="form-select w-auto form-select-sm ms-3">
                        <option value="">-Safety Comitee-</option>
                        <option value="1">Ya</option>
                        <option value="0">Tidak</option>
                    </select>
                </label>`
        );
        $('.dt-search').append(select);

        $('#isQrpEnabled').on('change', function () {
            positionTable.ajax.reload();
        });
    }
});

$('#positionExport').on('click', function (e) {
    e.preventDefault();
    let params = $('#positionTable').DataTable().ajax.params();
    let value = params.search.value;
    let qrpEnabled = $('#isQrpEnabled').val();
    window.location.href = '/positions/export?param=' + value + '&isQrpEnabled=' + qrpEnabled;
});
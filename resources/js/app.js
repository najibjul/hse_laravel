import $ from 'jquery';
window.$ = window.jQuery = $;

import select2 from 'select2';
import 'select2/dist/css/select2.min.css';

select2();

import 'bootstrap';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-bs5';

import Swal from 'sweetalert2';
window.Swal = Swal;

import '../assets/fonts/tabler-icons.min.css';
import '../assets/css/style.css';

const page = document.body.dataset.title;

// console.log(page);

$(function () {

    const qrpTable = $('#qrpTable').DataTable({
        processing: false,
        serverSide: true,
        ajax: {
            url: '/daily-checking',
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
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
            searchable: true
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
            searchable: true
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
        ]
    });

    if (page == 'Daily Checking') {
        document.querySelector('.dt-search').innerHTML += `<input type="date" class="form-control w-auto form-control-sm ms-3" id="start_date"><span class="ps-2">s/d</span><input type="date" class="form-control w-auto form-control-sm" id="end_date">`;
    }

    $('#start_date').on('change', function () {
        if ($('#start_date').val != "" && $('#end_date').val != "") {
            qrpTable.draw();
        }
    })

    $('#end_date').on('change', function () {
        if ($('#start_date').val != "" && $('#end_date').val != "") {
            qrpTable.draw();
        }
    })

    $('#qrpExport').on('click', function (e) {
        e.preventDefault();
        let params = $('#qrpTable').DataTable().ajax.params();
        let value = params.search.value;
        window.location.href = '/qrp/export?param=' + value + '&start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val();
    });

    $('#userTable').DataTable({
        processing: false,
        serverSide: true,
        ajax: "/admin/users",
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
            data: 'email',
            name: 'email',
            searchable: true
        },
        {
            data: 'costCenter',
            name: 'costCenter',
            searchable: true
        },
        {
            data: 'department',
            name: 'department',
            searchable: true
        },
        {
            data: 'role',
            name: 'role',
            searchable: true
        },
        {
            data: 'position',
            name: 'position',
            searchable: true
        },
        {
            data: 'plant',
            name: 'plant',
            searchable: true
        },
        {
            data: 'leader',
            name: 'leader',
            searchable: true
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
        ]
    });

    $('#userExport').on('click', function (e) {
        e.preventDefault();
        let params = $('#userTable').DataTable().ajax.params();
        let value = params.search.value;
        window.location.href = '/users/export?param=' + value;
    });

    $('#departmentExport').on('click', function (e) {
        e.preventDefault();
        let params = $('#departmentTable').DataTable().ajax.params();
        let value = params.search.value;
        window.location.href = '/departments/export?param=' + value;
    });

    $('#costCenterExport').on('click', function (e) {
        e.preventDefault();
        let params = $('#costCenterTable').DataTable().ajax.params();
        let value = params.search.value;
        window.location.href = '/cost-centers/export?param=' + value;
    });

    $('#positionExport').on('click', function (e) {
        e.preventDefault();
        let params = $('#positionTable').DataTable().ajax.params();
        let value = params.search.value;
        window.location.href = '/positions/export?param=' + value;
    });

    $('#plantExport').on('click', function (e) {
        e.preventDefault();
        let params = $('#plantTable').DataTable().ajax.params();
        let value = params.search.value;
        window.location.href = '/plants/export?param=' + value;
    });

    $('#departmentTable').DataTable({
        processing: false,
        serverSide: true,
        ajax: "/admin/departments",
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
        ]
    });

    $('#plantTable').DataTable({
        processing: false,
        serverSide: true,
        ajax: "/admin/plants",
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'plant_name',
            name: 'plant_name',
            searchable: true
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
        ]
    });

    $('#costCenterTable').DataTable({
        processing: false,
        serverSide: true,
        ajax: "/admin/cost-centers",
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'costCenter',
            name: 'costCenter',
            searchable: true
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
        ]
    });

    $('#positionTable').DataTable({
        processing: false,
        serverSide: true,
        ajax: "/admin/positions",
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'position_name',
            name: 'position_name',
            searchable: true
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
        ]
    });

    $('#department-select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Departemen',
        allowClear: true,
        ajax: {
            url: '/admin/department-master',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(department => ({
                        id: department.id,
                        text: department.department_name
                    }))
                };
            },
            cache: true
        },
    });

    $('#position-select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Posisi',
        allowClear: true,
        ajax: {
            url: '/admin/position-master',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(position => ({
                        id: position.id,
                        text: position.position_name
                    }))
                };
            },
            cache: true
        },
    });

    $('#cost-center-select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Cost Center',
        allowClear: true,
        ajax: {
            url: '/admin/cost-center-master',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(cost_center => ({
                        id: cost_center.id,
                        text: cost_center.cost_center_name
                    }))
                };
            },
            cache: true
        },
    });

    $('#plant-select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Plant',
        allowClear: true,
        ajax: {
            url: '/admin/plant-master',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(plant => ({
                        id: plant.id,
                        text: plant.plant_name
                    }))
                };
            },
            cache: true
        },
    });

    $('#leader-select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Leader',
        allowClear: true,
        ajax: {
            url: '/admin/leader-master',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(user => ({
                        id: user.id,
                        text: user.name + " (" + user.nip + ")"
                    }))
                };
            },
            cache: true
        },
    });


    const departmentId = $('#department-select').data('department');
    if (departmentId) {
        $.ajax({
            type: 'GET',
            url: `/admin/department-master/${departmentId}`,
        }).then(function (data) {
            const option = new Option(data.department_name, data.id, true, true);
            $('#department-select').append(option).trigger('change');
        });
    }

    const positionId = $('#position-select').data('position');
    if (positionId) {
        $.ajax({
            type: 'GET',
            url: `/admin/position-master/${positionId}`,
        }).then(function (data) {
            const option = new Option(data.position_name, data.id, true, true);
            $('#position-select').append(option).trigger('change');
        });
    }

    const costCenterId = $('#cost-center-select').data('cost-center');
    if (costCenterId) {
        $.ajax({
            type: 'GET',
            url: `/admin/cost-center-master/${costCenterId}`,
        }).then(function (data) {
            const option = new Option(data.cost_center_name, data.id, true, true);
            $('#cost-center-select').append(option).trigger('change');
        });
    }

    const plantId = $('#plant-select').data('plant');
    if (plantId) {
        $.ajax({
            type: 'GET',
            url: `/admin/plant-master/${plantId}`,
        }).then(function (data) {
            const option = new Option(data.plant_name, data.id, true, true);
            $('#plant-select').append(option).trigger('change');
        });
    }

    const leaderId = $('#leader-select').data('leader');
    if (leaderId) {
        $.ajax({
            type: 'GET',
            url: `/admin/leader-master/${leaderId}`,
        }).then(function (data) {
            const option = new Option(data.name, data.id, true, true);
            $('#leader-select').append(option).trigger('change');
        });
    }
});
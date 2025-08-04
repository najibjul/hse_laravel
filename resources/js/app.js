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

$(function () {
    $('#department-select').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Departemen',
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
});

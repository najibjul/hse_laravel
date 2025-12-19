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

$(function () {

    if (document.getElementById('daily-checking')) {
        import('./pages/dailyChecking');
    }

    if (document.getElementById('master-user-index')) {
        import('./pages/admin/users/index');
    }

    if (document.getElementById('master-user-create')) {
        import('./pages/admin/users/create');
    }

    if (document.getElementById('master-department-index')) {
        import('./pages/admin/departments/index');
    }

    if (document.getElementById('master-cost-center-index')) {
        import('./pages/admin/cost-centers/index');
    } 

    if (document.getElementById('master-position-index')) {
        import('./pages/admin/positions/index');
    }    

    if (document.getElementById('master-plant-index')) {
        import('./pages/admin/plants/index');
    }    

    if (document.getElementById('master-dept-conf-index')) {
        import('./pages/admin/dept-configs/index');
    }    

    if (document.getElementById('dept-config-edit')) {
        import('./pages/admin/dept-configs/edit');
    }    
});
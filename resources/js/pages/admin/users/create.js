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
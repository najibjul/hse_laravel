@extends('layouts.app', ['title' => 'Daily Checking'])
@section('content')
    @if ($agent->isDesktop())
        <div class="page-header" id="daily-checking">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Daily Checking</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Safety Comitee</a></li>
                            <li class="breadcrumb-item" aria-current="page">Daily Checking</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Daily Checking</h4>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="d-flex justify-content-end pe-4">
                        @if (auth()->user()->position?->is_qrp_enabled)
                            <button type="button" class="btn btn-success rounded" data-bs-toggle="modal"
                                data-bs-target="#addCheckingModal">
                                <div class="d-block d-md-none d-lg-none">
                                    <div class="ti ti-plus"></div>
                                </div>
                                <div class="d-none d-md-block d-lg-block">
                                    <i class="ti ti-plus"></i> Pengecekan
                                </div>
                            </button>
                        @endif
                        <button type="button" id="qrpExport" class="btn btn-info ms-2 rounded"><i
                                class="ti ti-file-export"></i> Export</button>
                    </div>

                    <form id="qrpExportForm" action="{{ route('qrp.export') }}" method="post" target="_blank">
                        @csrf
                        <input type="hidden" name="cari_user" id="export-cari-user">
                        <input type="hidden" name="cari_aktifitas" id="export-cari-aktifitas">
                        <input type="hidden" name="cari_area" id="export-cari-area">
                        <input type="hidden" name="start_date" id="export-start-date">
                        <input type="hidden" name="end_date" id="export-end-date">
                        <input type="hidden" name="cari_faktor" id="export-cari-faktor">
                        <input type="hidden" name="cari_cek" id="export-cari-cek">
                        <input type="hidden" name="cari_status" id="export-cari-status">
                    </form>

                    {{-- <form id="formExport" method="POST" action="{{ route('export.store') }}">
                        @csrf
                    </form> --}}
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="qrpTable">
                        <thead class="table-success">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Aktifitas / Problem</th>
                                <th class="text-center">Area</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Faktor</th>
                                <th class="text-center">Status Cek</th>
                                <th class="text-center">Status Terakhir</th>
                                <th class="text-center">Opsi</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="text" id="cari-user" class="form-control form-control-sm"
                                        placeholder="Cari user ..."></td>
                                <td><input type="text" id="cari-aktifitas" class="form-control form-control-sm"
                                        placeholder="Cari aktifitas ..."></td>
                                <td><input type="text" id="cari-area" class="form-control form-control-sm"
                                        placeholder="Cari area ..."></td>
                                <td>
                                    <input type="date" class="form-control w-auto form-control-sm" id="start_date">
                                    <div class="py-2 text-center">s/d</div>
                                    <input type="date" class="form-control w-auto form-control-sm" id="end_date">
                                </td>
                                <td>
                                    <select id="cari-faktor" class="form-select form-select-sm">
                                        <option value="">-Filter faktor-</option>
                                        @foreach ($factors as $factor)
                                            <option value="{{ $factor->id }}">{{ $factor->factor_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="cari-cek" class="form-select form-select-sm">
                                        <option value="">-Filter cek-</option>
                                        <option value="OK">OK</option>
                                        <option value="NG">NG</option>
                                    </select>
                                </td>
                                <td>
                                    <select id="cari-status" class="form-select form-select-sm">
                                        <option value="">-Filter status-</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    @elseif($agent->isMobile())
        <div class="mb-3">
            <h4>DAILY CHECKING</h4>
        </div>
        <form action="{{ route('qrp.daily-checking') }}" method="GET">
            <div class="d-flex gap-2 mb-4">
                <input type="text" name="search" class="form-control  search rounded-pill"
                    value="{{ $search }}" placeholder="Cari...">
                <button type="submit" class="btn  btn-warning rounded-pill" type="submit">
                    <i class="ti ti-search"></i>
                </button>
                @if (auth()->user()->position?->is_qrp_enabled)
                    <button type="button" class="btn  btn-success rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#addCheckingModal">
                        <i class="ti ti-plus"></i>
                    </button>
                @endif
                <button type="button" class="btn btn-info rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#filter">
                    <i class="ti ti-filter"></i>
                </button>
            </div>
        </form>

        <div class="modal fade" id="filter" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('qrp.daily-checking') }}" method="GET">
                        <div class="modal-body">
                            <select name="check_status" class="form-control rounded-pill mb-4" autocomplete="off">
                                <option value="">-Status pengecekan-</option>
                                <option {{ request('check_status') == 'OK' ? 'selected' : '' }} value="OK">OK</option>
                                <option {{ request('check_status') == 'NG' ? 'selected' : '' }} value="NG">NG</option>
                            </select>

                            <select name="safety_comitee_status" class="form-control rounded-pill mb-4" autocomplete="off">
                                <option value="">-Status safety comitee-</option>
                                @foreach ($statuses as $status)
                                    <option {{ request('safety_comitee_status') == $status->id ? 'selected' : '' }} value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach                                
                            </select>

                            <div class="d-flex">
                                <input type="date" class="form-control rounded-pill" name="start_date" value="{{ request('start_date') }}">
                                <div class="mx-3 mt-2 text-nowrap">S/d</div>
                                <input type="date" class="form-control rounded-pill" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-info rounded-pill">Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($dailyChecks as $dailyCheck)
            <a href="{{ $dailyCheck->check_status == 'NG' ? route('qrp.qrp-form-detail', encrypt($dailyCheck->id)) : 'javascript:void(0)' }}"
                @if ($dailyCheck->check_status == 'OK') onclick="statusOk(event)" @endif>
                <div class="card card-hover " style="border-radius: 15px;">
                    <div class="card-body ">
                        <div><i class="ti ti-user"></i> {{ $dailyCheck->user?->name }} ({{ $dailyCheck->user?->nip }})
                        </div>
                        <div><i class="ti ti-alert-circle"></i>
                            {{ $dailyCheck->activity ? $dailyCheck->activity : $dailyCheck->qrpDetail->description }}</div>
                        <div><i class="ti ti-building-community"></i> {{ $dailyCheck->area }}</div>
                        <div><i class="ti ti-calendar-event"></i>
                            {{ \Carbon\Carbon::parse($dailyCheck->created_at)->translatedFormat('d M Y H:i') }}</div>
                        <div class="badge {{ $dailyCheck->check_status == 'NG' ? 'bg-danger' : 'bg-success' }}">
                            {{ $dailyCheck->check_status }}</div>
                        <div class="{{ $dailyCheck->qrpDetail?->qrpStatus->class }}">
                            {{ $dailyCheck->qrpDetail?->qrpStatus->name }}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach

        {{ $dailyChecks->links('vendor.pagination.bootstrap-5') }}
    @endif

    <div class="modal fade" id="addCheckingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Lakukan Pengecekan</h5>
                    <button onclick="resetChecking()" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="man" class="d-block">
                        <h2 class="text-success fw-bold mt-1 mb-4 ms-2">MAN</h2>
                        Apakah jumlah personil, kompetensi & pengalaman kerja sudah sesuai?
                    </div>
                    <div id="machine" class="d-none">
                        <h2 class="text-success fw-bold mt-1 mb-4 ms-2">MACHINE</h2>
                        Apakah peralatan yang akan digunakan sudah sesuai dan kondisi layak pakai?
                    </div>
                    <div id="material" class="d-none">
                        <h2 class="text-success fw-bold mt-1 mb-4 ms-2">MATERIAL</h2>
                        Apakah material atau bahan sudah sesuai dengan spesifikasi yang akan digunakan?
                    </div>
                    <div id="method" class="d-none">
                        <h2 class="text-success fw-bold mt-1 mb-4 ms-2">METHOD</h2>
                        Apakah proses kerja, dan komunikasi yang dilakukan sudah memadai?
                    </div>
                    <div id="environment" class="d-none">
                        <h2 class="text-success fw-bold mt-1 mb-4 ms-2">ENVIRONMENT</h2>
                        Apakah faktor fisika dan kebersihan area sudah proper?
                    </div>
                    <div id="dailyCheckForm" class="d-none">
                        <form method="POST" action="{{ route('qrp.daily-checking-post') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label">Aktifitas</label>
                                <textarea required name="activity" class="form-control" placeholder="Masukan aktifitas pekerjaan Anda ..."></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Area</label>
                                <input required type="text" class="form-control" name="area"
                                    placeholder="Masukan area...">
                            </div>

                            <button type="submit" class="btn btn-success w-100 my-3">Simpan
                                pengecekan</button>
                        </form>
                    </div>
                </div>
                <div id="modalFooterChecking" class="modal-footer">
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-danger me-2" href="{{ route('qrp.qrp-form') }}">TIDAK</a>
                        <button type="button" class="btn btn-success" onclick="nextCheck()">YA <i
                                class="ti ti-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let man = document.getElementById('man');
        let machine = document.getElementById('machine');
        let material = document.getElementById('material');
        let method = document.getElementById('method');
        let environment = document.getElementById('environment');
        let dailyCheckForm = document.getElementById('dailyCheckForm');
        let modalFooterChecking = document.getElementById('modalFooterChecking');

        function nextCheck() {
            fetch("{{ route('qrp.change-factor') }}");

            if (man.classList.contains("d-block")) {
                man.classList.remove("d-block");
                man.classList.add("d-none");
                machine.classList.remove("d-none");
                machine.classList.add("d-block");

            } else if (machine.classList.contains("d-block")) {
                machine.classList.remove("d-block");
                machine.classList.add("d-none");
                material.classList.remove("d-none");
                material.classList.add("d-block");


            } else if (material.classList.contains("d-block")) {
                material.classList.remove("d-block");
                material.classList.add("d-none");
                method.classList.remove("d-none");
                method.classList.add("d-block");


            } else if (method.classList.contains("d-block")) {
                method.classList.remove("d-block");
                method.classList.add("d-none");
                environment.classList.remove("d-none");
                environment.classList.add("d-block");

            } else if (environment.classList.contains("d-block")) {
                environment.classList.remove("d-block");
                environment.classList.add("d-none");
                dailyCheckForm.classList.remove("d-none");
                dailyCheckForm.classList.add("d-block");
                modalFooterChecking.classList.add("d-none");

            }
        }

        function resetChecking() {
            man.classList.remove("d-none");
            man.classList.add("d-block");
            machine.classList.remove("d-block");
            machine.classList.add("d-none");
            material.classList.remove("d-block");
            material.classList.add("d-none");
            method.classList.remove("d-block");
            method.classList.add("d-none");
            environment.classList.remove("d-block");
            environment.classList.add("d-none");
            dailyCheckForm.classList.remove("d-block");
            dailyCheckForm.classList.add("d-none");
            modalFooterChecking.classList.remove("d-none");
            modalFooterChecking.classList.add("d-block");
        }

        function statusOk(event) {
            event.preventDefault();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Status OK',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        }
    </script>
@endpush

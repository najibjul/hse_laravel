@extends('layouts.app', ['title' => 'Daily Checking'])
@section('content')
    @if ($agent->isDesktop())
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Daily Checking</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Quick Risk Prediction</a></li>
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
                <div class="row mb-4">
                    <form action="{{ route('qrp.daily-checking') }}" method="get">
                        <div class="d-flex justify-content-end">
                            <input type="text" class="form-control  search w-auto me-1" name="search" placeholder="Cari ..."
                                value="{{ $search }}">

                            <button type="submit" class="btn btn-warning me-3 ">
                                <i class="ti ti-search"></i>
                            </button>

                            <button type="button" class="btn btn-success " data-bs-toggle="modal"
                                data-bs-target="#addCheckingModal">
                                <div class="d-block d-md-none d-lg-none">
                                    <div class="ti ti-plus"></div>
                                </div>
                                <div class="d-none d-md-block d-lg-block">
                                    Lakukan pengecekan
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table-success">
                            <tr>
                                <th>No</th>
                                @if(auth()->user()->role_id != 3 or auth()->user()->deptHead)
                                    <th>User</th>
                                @endif
                                <th>Aktifitas / Problem</th>
                                <th>Area</th>
                                <th>Tanggal</th>
                                <th>Faktor</th>
                                <th>Status Cek</th>
                                <th>Status QRP</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dailyChecks as $dailyCheck)
                                <tr>
                                    <th>{{ $loop->iteration + ($dailyChecks->currentPage() - 1) * $dailyChecks->perPage() }}</th>
                                    @if(auth()->user()->role_id != 3 or auth()->user()->deptHead)
                                        <td>{{ $dailyCheck->user->name }} ({{ $dailyCheck->user->nip }})</td>
                                    @endif
                                    <td>{{ $dailyCheck->activity ? $dailyCheck->activity : $dailyCheck->qrpDetail->description }}</td>
                                    <td>{{ $dailyCheck->area }}</td>
                                    <td>{{ \Carbon\Carbon::parse($dailyCheck->created_at)->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="text-uppercase">
                                            {{ $dailyCheck->factor?->factor_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill {{ $dailyCheck->check_status == 'OK' ? 'bg-success' : 'bg-danger' }}">{{ $dailyCheck->check_status }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="{{ $dailyCheck->qrpDetail?->qrpStatus->class }}">{{ $dailyCheck->qrpDetail?->qrpStatus->name }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('qrp.qrp-form-detail', encrypt($dailyCheck->id)) }}"
                                            class="btn btn-warning  {{ $dailyCheck->check_status == 'OK' ? 'd-none' : '' }}">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                    {{ $dailyChecks->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    @elseif($agent->isMobile())

    <div class="mb-3">
        <h4>DAILY CHECKING</h4>
    </div>
        <form action="{{ route('qrp.daily-checking') }}" method="GET">
        <div class="d-flex gap-2 mb-4">
            <input type="text" name="search" class="form-control  search rounded-pill" value="{{ $search }}" placeholder="Cari...">
            <button type="submit" class="btn  btn-warning rounded-pill" type="submit">
                <i class="ti ti-search"></i>
            </button>
            <button type="button" class="btn  btn-success rounded-pill" data-bs-toggle="modal" data-bs-target="#addCheckingModal">
                <i class="ti ti-plus"></i>
            </button>
        </div>
        </form>

        @foreach($dailyChecks as $dailyCheck)
            <a href="{{ $dailyCheck->check_status == 'NG' ? route('qrp.qrp-form-detail', encrypt($dailyCheck->id)) : 'javascript:void(0)' }}" @if($dailyCheck->check_status == 'OK') onclick="statusOk(event)" @endif >
                <div class="card card-hover " style="border-radius: 15px;">
                    <div class="card-body ">
                        <div><i class="ti ti-user"></i> {{ $dailyCheck->user->name }} ({{ $dailyCheck->user->nip }})</div>
                        <div><i class="ti ti-alert-circle"></i> {{ $dailyCheck->activity ? $dailyCheck->activity : $dailyCheck->qrpDetail->description }}</div>
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
                                <textarea required name="activity" class="form-control"
                                    placeholder="Masukan aktifitas pekerjaan Anda ..."></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Area</label>
                                <input required type="text" class="form-control" name="area" placeholder="Masukan area...">
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

        function statusOk(event){
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

@push('styles')
<style>
    .card-hover:hover {
    transform: scale(1.03);
    box-: 0 10px 20px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}
</style>
@endpush
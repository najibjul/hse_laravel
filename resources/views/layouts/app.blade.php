<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ isset($title) ? $title . ' - HSE' : 'HSE' }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
    <meta name="keywords"
        content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
    <meta name="author" content="CodedThemes">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')


</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light" id="main-font-link" style="font-family: tabler-icons">

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="#" class="b-brand text-primary">
                    <img src="{{ asset('assets/images/logo-dark.svg') }}" class="img-fluid" alt="logo">
                </a>
            </div>
            <div class="navbar-content">
                <ul class="pc-navbar">
                    <li class="pc-item {{ route('dashboard') && 'active' }}">
                        <a href="{{ route('dashboard') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                            <span class="pc-mtext">Dashboard</span>
                        </a>
                    </li>


                    <li class="pc-item pc-caption">
                        <label>Safety Comitee</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item {{ Route::is('qrp.*') ? 'active' : '' }}">
                        <a href="{{ route('qrp.daily-checking') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-shield-check"></i></span>
                            <span class="pc-mtext">Daily Checking</span>
                        </a>
                    </li>


                    {{-- <li class="pc-item pc-caption">
                        <label>Safety Patrol</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-calendar"></i></span>
                            <span class="pc-mtext">Jadwal Patrol</span>
                        </a>
                    </li>
                    <li class="pc-item">
                        <a href="#" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-alert-circle"></i></span>
                            <span class="pc-mtext">Temuan Patrol</span>
                        </a>
                    </li> --}}


                    @if (auth()->user()->role_id == 2 or auth()->user()->role_id == 1)
                        <li class="pc-item pc-caption">
                            <label>Admin Menu</label>
                            <i class="ti ti-dashboard"></i>
                        </li>
                        <li class="pc-item {{ Route::is('admin.users.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-users"></i></span>
                                <span class="pc-mtext">Master User</span>
                            </a>
                        </li>
                        <li class="pc-item {{ Route::is('admin.departments.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.departments.index') }}" class="pc-link">
                                <span class="pc-micon"><i class="ti ti-building-skyscraper"></i></span>
                                <span class="pc-mtext">Master Department</span>
                            </a>
                        </li>
                    @endif


                </ul>
            </div>
        </div>
    </nav>
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-mail">
                                @if ($totalNotification > 0)
                                    <span class="badge bg-success rounded small"
                                        style="position: fixed; margin-top: -13px;  margin-left: -15px;">
                                        {{ $totalNotification }}</span>
                                @endif
                            </i>
                        </a>
                        <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header d-flex align-items-center justify-content-between">
                                <h5 class="m-0">Pesan</h5>
                                <a href="#!" class="pc-head-link bg-transparent"><i
                                        class="ti ti-x text-danger"></i></a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-header px-0 text-wrap header-notification-scroll position-relative"
                                style="max-height: calc(100vh - 215px)">
                                <div class="list-group list-group-flush w-100">
                                    @forelse ($notifications as $notification)
                                        <form id="notificationUpdate{{ $notification->id }}" method="POST"
                                            action="{{ route('notification.update', $notification->id) }}"
                                            class="d-none">
                                            @csrf
                                            @method('patch')
                                        </form>

                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById(`notificationUpdate{{ $notification->id }}`).submit();"
                                            class="list-group-item list-group-item-action">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <img src=" {{ asset('assets/images/user/avatar-2.jpg') }}"
                                                        alt="user-image" class="user-avtar">
                                                </div>
                                                <div class="flex-grow-1 ms-1">
                                                    {{-- <span class="float-end text-muted">3:00 AM</span> --}}
                                                    <p class="text-body mb-1">{{ $notification->title }}</p>
                                                    <span class="text-muted">{{ $notification->body }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="flex-grow-1 ms-1 text-center">
                                            Tidak ada pesan
                                        </div>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"
                            aria-expanded="false">
                            <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image"
                                class="user-avtar">
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('/assets/images/user/avatar-2.jpg') }}" alt="user-image"
                                            class="user-avtar wid-35">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                                        <span>{{ auth()->user()->role->role_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" id="mysrpTabContent">
                                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                    aria-labelledby="drp-t1" tabindex="0">

                                    <a href="{{ route('profile.index') }}" class="dropdown-item">
                                        <i class="ti ti-user"></i>
                                        <span>Profile</span>
                                    </a>

                                    <a href="javascript:void(0)"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                        class="dropdown-item">
                                        <i class="ti ti-power"></i>
                                        <span>Logout</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>

                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>


    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class="col-sm my-1">
                    <p class="m-0">Copyright ©️ EDP {{ now()->format('Y') }}</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session()->has('success'))
                Swal.fire({
                    title: "Berhasil",
                    text: `{{ session('success') }}`,
                    icon: "success",
                    confirmButtonColor: "#52c41a",
                });
            @endif

            @if (session()->has('error'))
                Swal.fire({
                    title: "Error",
                    text: `{{ session('error') }}`,
                    icon: "error",
                    confirmButtonColor: "#ff4d4f"
                });
            @endif

            layout_change('light');
            change_box_container('false');
            layout_rtl_change('false');
            preset_change("preset-1");
            font_change("Public-Sans");
        })
    </script>

    @stack('scripts')
</body>

</html>

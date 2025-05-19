<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | Mantis Bootstrap 5 Admin Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="auth-header">
                    <a href="#"><img src="/assets/images/logo-dark.svg" alt="img"></a>
                </div>
                <div class="card my-5 shadow">
                    <form action="{{ route('login-post') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-end mb-4">
                                <h3 class="mb-0"><b>Login</b></h3>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">NIP</label>
                                <input name="nip" type="text" class="form-control @error('nip') is-invalid @enderror"
                                    placeholder="User NIP">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Password</label>
                                <input name="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                            </div>
                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-success" type="checkbox" id="customCheckc1"
                                        checked="" name="remember">
                                    <label class="form-check-label text-muted" for="customCheckc1">Keep me sign
                                        in</label>
                                </div>
                            </div>
                            @if(session()->has('unauthenticated'))
                                <div class="alert alert-danger mt-4" role="alert">
                                    {{ session('unauthenticated') }}
                                </div>
                            @endif
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="auth-footer row">
                    <div class="col my-1">
                        <p class="m-0">Copyright © <a href="#">EDP {{ now()->format('Y') }}</a></p>
                    </div>
                    <div class="col-auto my-1">
                        <ul class="list-inline footer-link mb-0">
                            <li class="list-inline-item"><a href="#">Home</a></li>
                            <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                            <li class="list-inline-item"><a href="#">Contact us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/fonts/custom-font.js"></script>
    <script src="/assets/js/pcoded.js"></script>

    <script>
        layout_change('light');
        change_box_container('false');
        layout_rtl_change('false');
        preset_change('preset-1');
        font_change('Public-Sans');
    </script>
</body>

</html>
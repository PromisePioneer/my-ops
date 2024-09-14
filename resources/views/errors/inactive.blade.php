<!DOCTYPE html>
<html lang="en">
<head>
    <title>Akun Sudah Tidak Aktif</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico"/>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <!--end::Global Stylesheets Bundle-->
    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="auth-bg bgi-size-cover bgi-position-center bgi-no-repeat">
<div class="d-flex flex-column flex-root">
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="d-flex flex-column flex-center text-center p-10">
            <div class="card card-flush w-lg-650px py-5">
                <div class="card-body py-15 py-lg-20">
                    <div class="mb-14">
                        <a href="index.html" class="">
                            <img alt="Logo" src="{{ asset('assets/media/logos/custom-2.svg')}}" class="h-40px"/>
                        </a>
                    </div>
                    <div class="mb-11">
                        <a href="{{ url('/login') }}" class="btn btn-sm btn-primary">Go to Home Page</a>
                    </div>
                    <div class="mb-0">
                        <img src="{{ asset('assets/media/auth/membership.png')}}"
                             class="mw-100 mh-300px theme-light-show" alt=""/>
                        <img src="{{ asset('assets/media/auth/membership-dark.png')}}"
                             class="mw-100 mh-300px theme-dark-show"
                             alt=""/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js')}}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>

    {{-- CSS --}}
    <style>
        .page-lock {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999999;
            /* pastikan paling atas */
            background: rgba(255, 255, 255, 0.7);
            pointer-events: all;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-lock>* {
            pointer-events: none;
            /* anak tetap clickable jika perlu bisa diubah */
        }


        .page-lock-content {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn .2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }
    </style>

    @stack('styles')

    {{-- jQuery HANYA SEKALI disini --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    {{-- Vite untuk Echo & Reverb --}}
    @vite(['resources/js/app.js'])


    {{-- <link rel="stylesheet" href="{{ secure_asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet"
        href="{{ secure_asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/css/style.css') }}">

    <link rel="shortcut icon" href="{{ secure_asset('assets/images/favicon.png') }}"> --}}
</head>

<body>
    {{-- 🔒 GLOBAL PAGE LOCK (HARUS PALING ATAS) --}}
    <div id="global-page-lock" class="page-lock d-none">
        <div class="page-lock-content">
            <div class="spinner-border text-primary"></div>
        </div>
    </div>

    <div class="container-scroller">
        <x-navbar />

        <div class="container-fluid page-body-wrapper">
            <x-sidebar />

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                <x-footer />
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        window.PageLock = {
            show() {
                document.getElementById('global-page-lock')
                    ?.classList.remove('d-none');
            },

            hide() {
                document.getElementById('global-page-lock')
                    ?.classList.add('d-none');
            }
        };
    </script>
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>

    {{-- <script src="{{ secure_asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ secure_asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ secure_asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ secure_asset('assets/js/misc.js') }}"></script>
    <script src="{{ secure_asset('assets/js/settings.js') }}"></script>
    <script src="{{ secure_asset('assets/js/todolist.js') }}"></script>
    <script src="{{ secure_asset('assets/js/jquery.cookie.js') }}"></script> --}}

    @stack('scripts')
</body>

</html>

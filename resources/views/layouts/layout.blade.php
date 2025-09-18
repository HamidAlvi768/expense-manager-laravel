<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-url" content="{{ url('/') }}">

    <title>
        {{ $ApplicationSetting->item_short_name }}
        @if (isset($title) && !empty($title))
            {{ " | ".$title }}
        @endif
    </title>

    <!-- Custom styles for breadcrumb area -->
    <style>
        .content-header {
            position: relative;
            padding: 15px 0.5rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            margin: 0;
            background: none;
            font-size: 0.8rem;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        .breadcrumb-item + .breadcrumb-item {
            padding-left: 0.3rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            padding-right: 0.3rem;
        }

        .header-actions {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .content-header {
                padding: 10px 0.5rem;
            }
            
            .header-actions {
                position: relative;
                right: auto;
                top: auto;
                transform: none;
                margin-top: 0.5rem;
                justify-content: flex-end;
            }

            /* Modal positioning for mobile */
            .modal-open .modal {
                overflow-x: hidden;
                overflow-y: auto;
                top: 30%;
                left: 10%;
            }

            /* Make all card content text smaller on mobile */
            .card * {
                font-size: 0.75rem;
            }

            /* Keep headings slightly larger */
            .card h1, .card h2, .card h3, .card h4, .card h5, .card h6,
            .card .h1, .card .h2, .card .h3, .card .h4, .card .h5, .card .h6,
            .card .card-title {
                font-size: 1rem;
            }

            /* Keep buttons text readable */
            .card .btn {
                font-size: 0.8rem;
                padding: 0.375rem 0.75rem;
            }

            /* Keep form inputs readable */
            .card input, .card select, .card textarea {
                font-size: 0.8rem !important;
            }

            /* Keep table headers slightly more prominent */
            .card table th {
                font-size: 0.85rem;
            }
        }
    </style>

    @include('thirdparty.css_back')
    @yield('one_page_css')

    <link href="{{ asset('assets/css/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/mycustom.css') }}" rel="stylesheet">

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/layout.js') }}"></script>
    <script src="{{ asset('assets/js/custom/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/fullcalendar.min.js') }}"></script>
    @stack('header')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('layouts.header')
    @include('layouts.sidebar')
    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    @include('layouts.footer')
</div>
@include('thirdparty.js_back')
@yield('one_page_js')
@include('thirdparty.js_back_footer')
@stack('footer')
<script src="{{ URL::asset('assets\js\parsely.min.js') }}"></script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>@yield('title', 'Admin | Auth')</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/images/favicon.png') }}" />
    <link href="{{ asset('admin/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" />

    @yield('styles')
  </head>
  <body class="vh-100">
    @yield('content')

    <script src="{{ asset('admin/vendor/global/global.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/custom.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/deznav-init.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/demo.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/styleSwitcher.js') }}" type="text/javascript"></script>

    @yield('scripts')
  </body>
</html>

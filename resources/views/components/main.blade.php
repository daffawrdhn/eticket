<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="{{ asset('style/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/bootstrap.min.css') }}">

    <script>
        const APP_URL = "{{ asset('/') }}";
        const _token = "{{ csrf_token() }}";
    </script>

    <script src="{{ asset('style/js/popper.min.js') }}"></script>
    <script src="{{ asset('style/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('style/js/jquery-3.6.1.min.js') }}"></script>
    <script src="{{ asset('style/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('action/login/login.js') }}"></script>
    <script src="{{ asset('action/login/password_validate.js') }}"></script>
    
    <title>Document</title>
</head>

<body id="bg-alumunium">


    @yield('container')

</body>
</html>
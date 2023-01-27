<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('style/css/dashboard/style2.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/dashboard/style.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('style/css/login/style.css') }}">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js%22"> --}}
  


    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

    <script>
        const APP_URL = "{{ asset('/') }}";
        const _token = "{{ csrf_token() }}";
    </script>
    
    <script src="{{ asset('style/js/jquery-3.6.1.min.js') }}"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script src="{{ asset('style/js/jquery.table2excel.min.js') }}"></script>
    <script src="{{ asset('style/js/chart.min.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js%22"></script> --}}
    <script src="{{ asset('style/js/popper.js') }}"></script>
    <script src="{{ asset('style/js/popper.min.js') }}"></script>
    <script src="{{ asset('style/js/popper.min.js.map') }}"></script>
    <script src="{{ asset('style/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('style/js/select2.min.js') }}"></script>
    <script src="{{ asset('style/js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('style/js/script.js') }}"></script>
    <script src="{{ asset('action/modal/modal.js') }}"></script>
    <script src="{{ asset('action/login/login.js') }}"></script>
    <script src="{{ asset('action/login/password_validate.js') }}"></script>
    <script src="{{ asset('action/login/forgot_password.js') }}"></script>
    <script src="{{ asset('action/login/check_employee.js') }}"></script>

    
    
    
    <title>E-ticket</title>
</head>

<body id="bg-alumunium">


    @yield('container')

</body>
</html>
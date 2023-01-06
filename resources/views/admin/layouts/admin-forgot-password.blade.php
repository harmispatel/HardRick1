<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}"> 

    <title>Admin login</title>

    <!-- Scripts -->
    <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

    <!-- Styles -->
    
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="{{ asset('/public/assets/vendor/css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <!-- TOSTER MESSAGE CSS -->
    <link href="{{ asset('/public/assets/admin/css/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- custom css -->
    <link href="{{ asset('/public/assets/vendor/css/custom.css') }}" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!--  TOSTER MESSAGE JS -->
    <script src="{!! asset('/public/assets/admin/js/toastr/toastr.min.js') !!}"></script>

    <!-- font awesome-->
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/font-awesome/css/all.css') }}">
    

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

</head>
<body>
    <div id="app" style="display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100vh;
    overflow: auto;">
        
        
        <main class="" style="width:100%;height:100%">
            @yield('content')
        </main>
    </div>
</body>
</html>

<script>
    $(document).ready(function() {
        
        toastr.options.timeOut = 5000;
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
        @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @endif
    });
    // Show & Hide Password
    function ShowHidePassword()
    {
        var currentType = $('#password').attr('type');

        if(currentType == 'password')
        {
            $('#password').attr('type','text');
            $('#passIcon').html('');
            $('#passIcon').append('<i class="fa fa-eye"></i>');
        }
        else
        {
            $('#password').attr('type','password');
            $('#passIcon').html('');
            $('#passIcon').append('<i class="fa fa-eye-slash"></i>');
        }
    }

</script>




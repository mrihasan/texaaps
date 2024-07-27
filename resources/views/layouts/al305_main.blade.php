<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | {{ config('app.name', 'EIS') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

{{--<link rel="stylesheet" href="https://cdn.usebootstrap.com/bootstrap/4.1.1/css/bootstrap.min.css">--}}
<!-- Font Awesome Icons -->
    {{--        <link rel="stylesheet" href="{!! asset('AdminLTE-3.0.5/plugins/fontawesome-free/css/all.min.css')!!}">--}}
    <link rel="stylesheet" href="{!! asset('alte305/plugins/fontawesome-free/css/all.min.css')!!}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{!! asset('custom/css/custom.css')!!}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link rel="shortcut icon" href="{!! asset('/favicon.ico')!!}" type="image/x-icon">
    <link rel="icon" href="{!! asset('/favicon.ico')!!}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('supporting/toastr/toastr.min.css') }}">

    <style>
        .nav-child-indent,
        .text-sm,
        .nav-flat,
        .nav-compact
    </style>
    <style>
        /* Add this to your CSS file */
        .social-button-container {
            position: fixed;
            top: 5px;
            left: 60%;
            transform: translateX(-50%);
            z-index: 100011; /* Adjust the z-index value as needed */
        }

        .social-button {
            background-color: #fff;
            color: #3498db;
            border: none;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            position: relative;
            z-index: 1001; /* Set a higher z-index for the button */
        }

        .social-icons {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        .social-icon {
            display: block;
            margin: 1px;
            /*color: #fff;*/
            color: darkgreen;
            /*border: double;*/
            /*border-color: #0b2e13;*/
            text-decoration: none;
            font-size: 12px;
        }
    </style>
    {{--    <link rel="stylesheet" href="{!! asset('alte305/dist/css/adminlte.min.css')!!}">--}}

    @stack('css')
    <link rel="stylesheet" href="{!! asset('alte305/dist/css/adminlte.min.css')!!}">

</head>
<body onload="startTime();setdate()" class="hold-transition sidebar-mini text-sm">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{url('home')}}" class="nav-link">{{ __('all_settings.breadcrumb_home') }}</a>
            </li>
            @yield('breadcrumb')
        </ul>
        <ul class="navbar-nav ">
            <li class="nav-item " style="color: green">
                <strong><span class="mr-3 d-md-inline " id="today"></span><span id="time"></span></strong>
            </li>

        </ul>


        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <?php
            $branch_info = DB::table('branches')->get();
            if (Auth::user()->user_type_id == 1 && Auth::user()->employee == null) {
                $company = DB::table('branches')
                    ->select('id', 'title')
                    ->where('status', 'Active')
                    ->orderBy('title')->pluck('title', 'id')
                    ->toArray();
            } elseif (Auth::user()->user_type_id <= 2 && Auth::user()->employee != null) {
                $company = DB::table('branches')
                    ->select('branches.id', 'branches.title')
                    ->join('branch_user', 'branch_user.branch_id', '=', 'branches.id')
                    ->where('branch_user.user_id', Auth::user()->id)
                    ->orderBy('branches.title')->pluck('branches.title', 'branches.id')
                    ->toArray();
            }
            ?>


            <li>
                @if(Auth::user()->user_type_id<=2 && count($branch_info)==1)
                    <div class="btn-group ml-3">
                        <button type="button"
                                class="btn btn-success">{{ branch_info(session()->get('branch'))->title }}</button>
                    </div>
                @elseif(Auth::user()->user_type_id<=2)
                    <div class="btn-group">
                        <button type="button"
                                class="btn btn-success">{{ (session()->get('branch')!='all')? branch_info(session()->get('branch'))->title:'All'}}</button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle dropdown-icon"
                                    data-toggle="dropdown">
                            </button>
                            <div class="dropdown-menu">
                                {{--<a class="dropdown-item" href="#">Dropdown link</a>--}}
                                @foreach ($company as $com_id => $company_name)
                                    <a class="dropdown-item"
                                       href="{{ route('branchSwitch', $com_id) }}">{{$company_name}}</a>
                                @endforeach
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('branchSwitch', 'all') }}">All</a>
                            </div>
                        </div>
                    </div>
                @else
                @endif
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    {{--<img src="{!! asset('alte305/dist/img/user2-160x160.jpg')!!}"--}}
                    {{--class="user-image img-circle elevation-2" alt="User Image">--}}

                    @if(Auth::user()->imageprofile->image=='default_image.png' )
                        <img src="{!! asset( 'storage/image_profile/dummy-avatar-300x300'.'.jpg'. '?'. 'time='. time()) !!}"
                             class="user-image img-circle elevation-2">
                    @else
                        <img src="{!! asset( 'storage/image_profile/'. Auth::user()->imageprofile->image. '?'. 'time='. time()) !!}"
                             class="user-image img-circle elevation-2" alt="User Image">
                    @endif
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-gray">
                        @if(Auth::user()->imageprofile->image=='default_image.png' )
                            <img src="{!! asset( 'storage/image_profile/dummy-avatar-300x300'.'.jpg'. '?'. 'time='. time()) !!}"
                                 class="user-image img-circle elevation-2">
                        @else
                            <img src="{!! asset( 'storage/image_profile/'. Auth::user()->imageprofile->image. '?'. 'time='. time()) !!}"
                                 class="user-image img-circle elevation-2" alt="User Image">
                        @endif
                        <p>
                            {{ Auth::user()->name.' - '.Auth::user()->user_type->title }}
                            <small>Member
                                since {{ Carbon\Carbon::parse(Auth::user()->created_at)->format('d-M-Y') }}</small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="{{ url('myprofile') }}" class="btn btn-default btn-flat">Profile</a>
                        {{--                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-right">Sign out</a>--}}
                        <a class="btn btn-default btn-flat float-right" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>


        </ul>

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
@if(Auth::user()->user_type_id<=2)
    @include('layouts.sidebar')
    @else
    @include('layouts.sidebar_sc')
   @endif
    <div class="social-button-container">
        <div class="social-button" id="social-button">
            <i class="fa fa-snowflake"></i>
            <div class="social-icons">

                @can('AccountMgtAccess')
                    <a type="button" href="{{url('payment')}}" class="btn btn-warning social-icon"><i
                                class="fa fa-money-bill-wave"></i>Payment</a>
                    <a type="button" href="{{url('receipt')}}" class="btn btn-success social-icon"><i
                                class="fa fa-money-bill-alt"></i>Receipt</a>
                @endcan
                @can('ExpenseAccess')
                    <a type="button" href="{{url('expense/create')}}" class="btn btn-danger social-icon"><i
                                class="far fa-minus-square"></i>Expense</a>
                @endcan
                @can('SupplyAccess')
                    <a type="button" href="{{url('purchaseCreate')}}" class="btn btn-warning social-icon"><i
                                class="fa fa-cart-plus"></i>Purchase</a>
                    <a type="button" href="{{url('salesCreate')}}" class="btn btn-success social-icon"><i
                                class="fa fa-shopping-bag"></i>Sales</a>
                @endcan
            </div>
        </div>
    </div>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            @yield('maincontent')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Powered By : </b><a href="https://www.eidyict.com" target="_blank">Eidy ICT Solutions Ltd.</a>
        </div>
        <strong>Copyright &copy; 2020-<?php echo date('Y'); ?> <a href="#">{{ config('app.name', 'EIS') }} </a> .
        </strong> All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- jQuery -->
<script src="{!! asset('alte305/plugins/jquery/jquery.min.js')!!}" type="text/javascript"></script>
<!-- Bootstrap 4 -->
<script src="{!! asset('alte305/plugins/bootstrap/js/bootstrap.bundle.min.js')!!}" type="text/javascript"></script>
{{--<script src="https://cdn.usebootstrap.com/bootstrap/4.1.1/js/bootstrap.min.js" type="text/javascript"></script>--}}
{{--<script src="https://cdn.usebootstrap.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js" type="text/javascript"></script>--}}
<!-- AdminLTE App -->
<script src="{!! asset('alte305/dist/js/adminlte.min.js')!!}" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="{!! asset('alte305/dist/js/demo.js')!!}" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('supporting/toastr/toastr.min.js') }}"></script>


@stack('js')

@if (Session::has('flash_success'))
    <script>
        toastr.success('{{ Session::get('flash_success') }}', 'Success Alert', {timeOut: 7000, closeButton: true});
    </script>
@endif
@if (Session::has('flash_error'))
    <script>
        toastr.error('{{ Session::get('flash_error') }}', 'Error Alert', {timeOut: 19500, closeButton: true});
    </script>
@endif
@if (Session::has('error'))
    <script>
        toastr.error('{{ Session::get('error') }}', 'Error Alert', {timeOut: 19500, closeButton: true});
    </script>
@endif
@if (Session::has('flash_message'))
    <script>
        toastr.success('{{ Session::get('flash_message') }}', 'Success Alert', {timeOut: 7000, closeButton: true});
    </script>
@endif

<script>
    function setdate() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = dd + '-' + mm + '-' + yyyy;
        document.getElementById("today").innerHTML = today;
    }

    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var c = ((h > 12) ? 'PM' : 'AM');
        h = h % 12;
        if (h == 0) {
            h = 12;
        }
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('time').innerHTML =
            h + ":" + m + ":" + s + " " + c;
        var t = setTimeout(startTime, 1000);
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }
        ;  // add zero in front of numbers < 10
        return i;
    }

</script>
<script>
    var siteURL = "{{url('')}}";
</script>
<script>
    <!-- Add this to your HTML file, or include it in your JS file -->
    document.getElementById('social-button').addEventListener('mouseenter', function () {
        document.querySelector('.social-icons').style.display = 'flex';
    });

    document.getElementById('social-button').addEventListener('mouseleave', function () {
        document.querySelector('.social-icons').style.display = 'none';
    });
</script>

{{--Hotkey for open a url --}}
<script>
    $(document).ready(function() {
        $(document).on('keydown', function(e) {
            if ((event.key === 's' || event.key === 'S') && event.altKey) {
{{--                window.location.href = "{{ route('salesCreate') }}";--}}
                window.open("{{ route('salesCreate') }}", "_blank");
            }
            if ((event.key === 'p' || event.key === 'P') && event.altKey) {
                {{--window.location.href = "{{ route('purchaseCreate') }}";--}}
                window.open("{{ route('purchaseCreate') }}", "_blank");
            }
            if ((event.key === 'x' || event.key === 'X') && event.altKey) {
{{--                window.location.href = "{{ route('expenseCreate') }}";--}}
{{--                window.open("{{ route('expenseCreate') }}", "_blank");--}}
                window.open("{{ route('efa.expenseCreate', ['efa' => 'expense']) }}", "_blank");
            }
        });
    });
</script>

</body>
</html>

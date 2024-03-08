@extends('layouts.al305_main')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Error</a>
    </li>
@endsection

@section('maincontent')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="text-danger">404 | Not Found</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
    <div class="error-page">
        <h2 class="headline text-warning"> 404</h2>

        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! this page not found.</h3>

            <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="{{ route('home') }}">return to dashboard. </a>
            </p>

            {{--<p>--}}
                {{--You don't have the necessary permission to view this page.--}}
            {{--</p>--}}
            <p>
                {{--If you believe this is an error, please contact with System Admin.--}}
            </p>


        </div>
        <!-- /.error-content -->
    </div>
        </section>
    </div>
@endsection

@extends('layouts.al305_main')
@section('expense_mo','menu-open')
@section('expense','active')
@section('manage_expense','active')
@section('title','Manage Expense')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('expense') }}" class="nav-link">Expense</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Expense</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/sweetalert/sweetalert2.css') }}">

@endpush
@section('maincontent')

    <!-- Content placeholder -->
<div id="content_placeholder"></div>

<!-- Footer -->
<footer>
    <p>This is the footer</p>
</footer>
@endsection

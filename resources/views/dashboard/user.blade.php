@extends('layouts.al305_main')
@section('dashboard_mo','menu-open')
@section('dashboard','active')
@section('title','Dashboard')
@push('css')
@endpush
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Dashboard</a>
    </li>
@endsection

@section('maincontent')

    <h3>User Dashboard</h3>

@endsection
@push('js')
@endpush
@extends('layouts.al305_main')
@section('permission_mo','menu-open')
@section('permission','active')
@section('manage_permission','active')
@section('title','Permission ')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('permission')}}" class="nav-link">Permission</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Permission</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Show Permission</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        ID
                    </th>
                    <td>
                        {{ $permission->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Title
                    </th>
                    <td>
                        {{ $permission->title }}
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                        class="fa fa-arrow-left"
                        aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
        </div>
    </div>
@endsection
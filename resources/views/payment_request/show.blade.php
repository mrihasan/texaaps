@extends('layouts.al305_main')
@section('superadmin_mo','menu-open')
@section('superadmin','active')
@section('manage_branch','active')
@section('title','Manage Branch')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link"> Branch</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Branch</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Show Branch</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        ID
                    </th>
                    <td>
                        {{ $branch->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Title
                    </th>
                    <td>
                        {{ $branch->title }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Contact No1
                    </th>
                    <td>
                        {{ $branch->contact_no1 }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Contact No2
                    </th>
                    <td>
                        {{ $branch->contact_no2 }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Address
                    </th>
                    <td>
                        {{ $branch->address }}
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        {{ $branch->status }}
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                        class="fa fa-arrow-left"
                        aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
            <a href="{{ url('branch/' . $branch->id . '/edit') }}"
               class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span
                        class="far fa-edit"
                        aria-hidden="true"></span></a>

        </div>
    </div>
@endsection
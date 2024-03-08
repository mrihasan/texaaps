@extends('layouts.al305_main')
@section('superadmin_mo','menu-open')
@section('superadmin','active')
@section('manage_user_type','active')
@section('title','User Type ')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('role')}}" class="nav-link">Role</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Role</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Show Role</h3>
        </div>

        <div class="card-body">
            <div class="mb-2">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>
                            ID
                        </th>
                        <td>
                            {{ $user_type->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Title
                        </th>
                        <td>
                            {{ $user_type->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Roles
                        </th>
                        <td>
                            @foreach($user_type->user_type_role as $key => $roles)
                                <span class="label label-info label-many">{{($key+1).' . '. $roles->title }}</span><br/>
                            @endforeach
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                    class="fa fa-arrow-left"
                    aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
        </div>
    </div>

@endsection

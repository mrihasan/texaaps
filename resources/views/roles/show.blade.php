@extends('layouts.al305_main')
@section('user_mo','menu-open')
@section('user','active')
@section('manage_role','active')
@section('title','Role ')
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
                            {{ $role->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Title
                        </th>
                        <td>
                            {{ $role->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Permissions
                        </th>
                        <td>
                            @foreach($role->permissions as $key => $permissions)
                                <span class="label label-info label-many">{{($key+1).' . '. $permissions->title }}</span><br/>
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
            {{--                    @can('geo_location-access')--}}
            {!! Form::open([
                'method'=>'DELETE',
                'url' => ['role', $role->id],
                'style' => 'display:inline'
            ]) !!}
            {!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-xs fa-pull-right',
                    'title' => 'Delete',
                    'onclick'=>'return confirm("Confirm delete?")'
            ))!!}
            {!! Form::close() !!}
            <a href="{{ url('role/' . $role->id . '/edit') }}"
               class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span
                        class="far fa-edit"
                        aria-hidden="true"></span></a>

        </div>
    </div>

@endsection
@extends('layouts.al305_main')
@section('superadmin_mo','menu-open')
@section('superadmin','active')
@section('manage_user_type','active')
@section('title','User Type ')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('user-type')}}" class="nav-link">User Type</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add User Type</a>
    </li>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">
@endpush
@section('maincontent')

    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add User Type</h3>
                </div>
                <form action="{{ route("user-type.store") }}" method="POST" enctype="multipart/form-data" id="saveForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="control-label col-md-4 text-right">Title:
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-6">
                            <input type="text" id="title" name="title" class="form-control" autofocus
                                   value="{{ old('title', isset($role) ? $role->title : '') }}" required>
                            </div>
                            @if($errors->has('title'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </em>
                            @endif
                        </div>

                        <div class="form-group row {{ $errors->has('roles') ? ' has-error' : '' }}">
                            <label class="control-label col-md-4 text-right">Select Roles:
                                <span class="required"> * </span>
                            </label>
                            <div class="col-md-6">
                                <select name="roles[]" class="form-control select2" id="roles" required multiple="multiple">
                                    <option value="">Select User type</option>
                                    @foreach($roles as $id => $role)
                                        {{--<option value="{{$user_type->id}}">{{$user_type->title}}</option>--}}
                                        <option value="{{$role->id}}">{{$role->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('roles'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('roles') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                class="fa fa-arrow-left"
                                aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                        <button type="submit" class="btn btn-success float-right" id="saveButton"><i
                                class="fa fa-save"
                                aria-hidden="true"></i> Save
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>

    {{--prevent multiple form submits (Jquery needed)--}}
    <script>
        $('#saveForm').submit(function () {
            $("#saveButton", this)
                .html("Please Wait...")
                .attr('disabled', 'disabled');
            return true;
        });
    </script>
@endpush

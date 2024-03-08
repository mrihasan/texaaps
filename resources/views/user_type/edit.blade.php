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
        <a href="#" class="nav-link">Update User Type</a>
    </li>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />--}}
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />--}}
    <link rel="stylesheet" href="{{ asset('alte305/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
@endpush
@section('maincontent')

    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Update User Type</h3>
                </div>
                {!! Form::model($user_type,['method'=>'PATCH', 'route'=>['user-type.update',$user_type->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
                @csrf
                <div class="card-body">

                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                        <label class="control-label col-md-4 text-right">Title:
                            <span class="required"> * </span>
                        </label>
                        <div class="col-md-6">
                            <input type="text" id="title" name="title" class="form-control"
                                   value="{{ old('title', isset($user_type) ? $user_type->title : '') }}" required>
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
                                    <option
                                            value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user_type) && $user_type->user_type_role->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
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

                {!! Form::close() !!}

            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>--}}
    <script src="{!! asset('alte305/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')!!}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            $('.duallistbox').bootstrapDualListbox()


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

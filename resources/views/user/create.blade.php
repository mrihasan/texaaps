@extends('layouts.al305_main')
@section('user_mo','menu-open')
@section('user','active')
@section('manage_Admin','active')
@section('title','Add Admin User')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('user')}}" class="nav-link">Admin User</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Admin User</a>
    </li>
@endsection
@push('css')

<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">

@endpush
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Admin User</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {!! Form::open(['url' => 'user', 'class' => 'form-horizontal','id'=>'saveForm']) !!}
                {{ csrf_field() }}
                {!! Form::hidden('user_type', 1 )!!}
                {{--{!! Form::hidden('password_confirmation', '123456' )!!}--}}

                <div class="card-body">

                    <div class="form-group row {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label text-md-right">Full Name :
                            <span class="required"> * </span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name"
                                   value="{{ old('name') }}" autocomplete="false" autofocus
                                   placeholder="Full Name" required onfocus="true">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('cell_phone') ? ' has-error' : '' }}">
                        <label for="cell_phone" class="col-md-4 control-label text-right">Mobile Number :
                            <span class="required"> ** </span></label>
                        <div class="col-md-6">
                            {!! Form::text('cell_phone',null, array('placeholder' => 'cell phone number','class' => 'form-control',
                            'pattern'=>'[0]{1}[1]{1}[0-9]{9}','maxlength'=>'11')) !!}
                            <span class="help-block">11 Digit and Start with 01
                                    </span>
                            @if ($errors->has('cell_phone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('cell_phone') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email"
                               class="col-md-4 control-label text-right">Email : <span
                                    class="required"> ** </span></label>
                        <div class="col-md-6">
                            <input id="email" readonly type="email"  class="form-control" name="email" value="{{ old('email') }}"
                                   onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly');
                    // fix for mobile safari to show virtual keyboard https://jsfiddle.net/danielsuess/n0scguv6/
                                    this.blur();    this.focus();  }"/>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 control-label text-right"></label>
                        <div class="col-md-6">
                            <span class="help-block"><strong>** Mobile Number or Email any one is required</strong></span>
                        </div>
                    </div>


                    <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label text-right">Password : <span
                                    class="required"> * </span></label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password"
                                   placeholder="Password" required autocomplete="off">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm" class="col-md-4 control-label text-right">Confirm Password : <span
                                    class="required"> * </span></label>
                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" placeholder="Confirm Password">
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    {{--<div class="form-group row {{ $errors->has('user_type') ? ' has-error' : '' }}">--}}
                    {{--<label class="control-label col-md-4 text-right">User type:--}}
                    {{--<span class="required"> * </span>--}}
                    {{--</label>--}}
                    {{--<div class="col-md-6">--}}
                    {{--<select name="user_type" class="form-control" id="user_type" required>--}}
                    {{--<option value="">Select User type</option>--}}
                    {{--@foreach($user_types as $user_type)--}}
                    {{--<option value="{{$user_type->id}}">{{$user_type->title}}</option>--}}
                    {{--@endforeach--}}
                    {{--</select>--}}
                    {{--@if ($errors->has('user_type'))--}}
                    {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('user_type') }}</strong>--}}
                    {{--</span>--}}
                    {{--@endif--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--                    <div class="form-group row{{ $errors->has('roles') ? 'has-error' : '' }}">--}}
                    {{--                        <label for="roles" class="col-md-4 control-label text-right">Roles :<span--}}
                    {{--                                    class="required"> * </span></label>--}}
                    {{--                        <select name="roles[]" id="roles" class="form-control col-md-6 select2" multiple="multiple"--}}
                    {{--                                 required>--}}
                    {{--                            --}}{{--<option value="" selected disabled>Please select Role/s</option>--}}
                    {{--                            @foreach($roles as $id => $roles)--}}
                    {{--                                <option value="{{ $id }}" >{{ $roles }}</option>--}}
                    {{--                            @endforeach--}}
                    {{--                        </select>--}}
                    {{--                        @if($errors->has('roles'))--}}
                    {{--                            <em class="invalid-feedback">--}}
                    {{--                                {{ $errors->first('roles') }}--}}
                    {{--                            </em>--}}
                    {{--                        @endif--}}
                    {{--                    </div>--}}


                    <div class="form-group row {{ $errors->has('gender') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label text-md-right">Gender : <span
                                    class="required"> * </span></label>
                        <div class=" col-md-6 mt-radio-inline">
                            <label class="mt-radio">
                                {{ Form::radio('gender', 'Male',true) }} Male
                                <span></span>
                            </label>
                            <label class="mt-radio">
                                {{ Form::radio('gender', 'Female') }} Female
                                <span></span>
                            </label>
                            <label class="mt-radio">
                                {{ Form::radio('gender', 'Others') }} Others
                                <span></span>
                            </label>
                        </div>

                        @if ($errors->has('gender'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 control-label text-right">Web Access:<span
                                    class="required"> * </span></label>
                        <div class="mt-radio-inline">
                            <label class="mt-radio">
                                <input type="radio" name="web_access"
                                       value="1">Yes
                                <span></span>
                            </label>
                            <label class="mt-radio">
                                <input type="radio" name="web_access"
                                       value="0" checked>No
                                <span></span>
                            </label>
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
<!-- InputMask for Date picker-->
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
</script>


<script>
    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#joining_date').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y'
        });
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

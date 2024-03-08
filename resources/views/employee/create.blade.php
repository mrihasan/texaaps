@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee','active')
@section('add_employee','active')
@section('title','Add Employee')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee')}}" class="nav-link">Employee</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add employee</a>
    </li>
@endsection

@push('css')
{{--<link href="{{ asset('custom/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />--}}
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}">


@endpush
@section('maincontent')
    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">Add Employee</h3>
            </div>
            <div class="card-body">
                {!! Form::open(array('route' => 'employee.store','method'=>'POST','class'=>'form-horizontal','id'=>'saveForm')) !!}
                {{ csrf_field() }}
                {{--<div class="form-group row {{ $errors->has('user_id') ? ' has-error' : '' }}">--}}
                {{--<label for="user_id" class="col-md-4 control-label text-md-right">Select--}}
                {{--User:<span class="required"> * </span></label>--}}
                {{--<div class="col-md-6">--}}
                {{--{{ Form::select('user_id', $user,null, ['class'=>'form-control select2bs4','autofocus'=>'autofocus' ] ) }}--}}
                {{--@if ($errors->has('user_id'))--}}
                {{--<span class="help-block"><strong>{{ $errors->first('user_id') }}</strong></span>--}}
                {{--@endif--}}
                {{--</div>--}}
                {{--</div>--}}
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
                        <span class="help-block">11 Digit and Start with 01</span>
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
                        <input id="email" readonly type="email" class="form-control" name="email"
                               value="{{ old('email') }}"
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
                        <span class="help-block">Minimum 6 Digit </span>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('branch_id') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Administrative Branch :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        <select name="branch_id" class="form-control select2" style="width: 100%;" id="branch_id">
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (session()->get('brand')) == $branch->id ? 'selected' : '' }}>{{ $branch->title}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('branch_id'))
                            <span class="help-block"><strong>
                                {{ $errors->first('branch_id') }}
                                </strong></span>
                        @endif

                    </div>
                </div>
                <div class="form-group row{{ $errors->has('access_branch') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Can Access Branches :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        <select name="access_branch[]" class="form-control select2" style="width: 100%;" required
                                multiple="multiple">
                            <option value="">Select Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ (session()->get('brand')) == $branch->id ? 'selected' : '' }}>{{ $branch->title}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('access_branch'))
                            <span class="help-block"><strong>
                                {{ $errors->first('access_branch') }}
                                </strong></span>
                        @endif

                    </div>
                </div>


                <div class="form-group row {{ $errors->has('salary_amount') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Salary Amount :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::number('salary_amount', null,['class'=>'form-control ', 'placeholder'=>'Enter Amount']) !!}
                        @if ($errors->has('salary_amount'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('salary_amount') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('bonus_amount') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Bonus ( % of Salary Amount :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::number('bonus_amount', null,['class'=>'form-control ', 'placeholder'=>'Enter Amount','min'=>0,'max'=>100]) !!}
                        @if ($errors->has('bonus_amount'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('bonus_amount') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('designation') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Designation :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::text('designation', null,['class'=>'form-control ', 'placeholder'=>'Enter Designation']) !!}
                        @if ($errors->has('designation'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('designation') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('id_number') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">ID Number :</label>
                    <div class="col-md-6">
                        {!! Form::text('id_number', null,['class'=>'form-control ', 'placeholder'=>'Enter ID number']) !!}
                        @if ($errors->has('id_number'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('id_number') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 control-label text-md-right">Religion :<span
                                class="required"> * </span></label>
                    <div class="col-sm-6">
                        <select name="religion" class="form-control" id="religion" required>
                            <option value="">Select Religion</option>
                            <option value="Islam" {{ (isset($employee) && $employee->religion == 'Islam') ? ' selected' : '' }}>
                                Islam
                            </option>
                            <option value="Hinduism" {{ (isset($employee) && $employee->religion == 'Hinduism') ? ' selected' : '' }}>
                                Hinduism
                            </option>
                            <option value="Christianity" {{ (isset($employee) && $employee->religion == 'Christianity') ? ' selected' : '' }}>
                                Christianity
                            </option>
                            <option value="Others" {{ (isset($employee) && $employee->religion == 'Others') ? ' selected' : '' }}>
                                Others
                            </option>
                        </select>
                    </div>
                </div>
            </div>
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

        {{--<div class="form-group row {{ $errors->has('joining_day') ? ' has-error' : '' }}">--}}
        {{--<label class="col-md-4 control-label text-md-right">Joining Day : <span class="required"> * </span></label>--}}
        {{--<div class="col-md-6 input-group date" id="joining_day" data-target-input="nearest">--}}
        {{--<input type="text" class="form-control datetimepicker-input" name="joining_day"--}}
        {{--value="{{ old('joining_day') }}" data-target="#joining_day"/>--}}
        {{--<div class="input-group-append" data-target="#joining_day"--}}
        {{--data-toggle="datetimepicker">--}}
        {{--<div class="input-group-text"><i class="fa fa-calendar"></i></div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@if ($errors->has('joining_day'))--}}
        {{--<span class="help-block">--}}
        {{--<strong>{{ $errors->first('joining_day') }}</strong>--}}
        {{--</span>--}}
        {{--@endif--}}

        {{--</div>--}}


        <!-- /.card-body -->
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-outline-dark"><i
                            class="fa fa-arrow-left"
                            aria-hidden="true"></i> {{ __('all_settings.Back') }}</a>

                <button type="submit" class="btn btn-info float-right" id="saveButton"><i
                            class="fa fa-save"
                            aria-hidden="true"></i> Save
                </button>
            </div>
            <!-- /.card-footer -->
            {!! Form::close() !!}
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

{{--prevent multiple form submits (Jquery needed)--}}
<script>
    $('#saveForm').submit(function () {
        $("#saveButton", this)
            .html("Please Wait...")
            .attr('disabled', 'disabled');
        return true;
    });
</script>


<script>
    //Initialize Select2 Elements
    $('.select2').select2()

    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#joining_day').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y'
        });

    })

</script>


@endpush
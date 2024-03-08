@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee','active')
@section('manage_employee','active')
@section('title','Update Employee')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee')}}" class="nav-link">Employee</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Update employee</a>
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
                <h3 class="card-title">Update Employee- {{ $employee->user->name}}</h3>
            </div>
            <div class="card-body">
                {!! Form::model($employee,['method'=>'PATCH', 'route'=>['employee.update',$employee->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
                {{ csrf_field() }}
                <div class="form-group row d-none {{ $errors->has('user_id') ? ' has-error' : '' }}">
                    <label for="user_id" class="col-md-4 control-label text-md-right">Select
                        User:<span class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('user_id', $user,$employee->user_id, ['class'=>'form-control  ' ] ) }}

                    @if ($errors->has('user_id'))
                            <span class="help-block"><strong>{{ $errors->first('user_id') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group row{{ $errors->has('branch_id') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Administrative Branch :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                            {{ Form::select('branch_id', $branches, $employee->branch_id,['class'=>'form-control select2'] ) }}
                        @if($errors->has('branch_id'))
                            <span class="help-block"><strong>
                                {{ $errors->first('branch_id') }}
                                </strong></span>
                        @endif

                    </div>
                </div>
                <div class="form-group row{{ $errors->has('access_branch') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Can Access Branches :</label>
                    <div class="col-md-6">
                        <select name="access_branch[]" class="form-control select2" style="width: 100%;" required
                                multiple="multiple">
                            @foreach($branches as $id => $branch)
                                <option value="{{ $id }}" {{ (isset($employee) && $employee->user->branches->contains($id)) ? 'selected' : '' }}>{{ $branch }}</option>
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
                        <select name="religion" class="form-control select2bs4" id="religion" required>
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
                {{--<div class="form-group row {{ $errors->has('joining_day') ? ' has-error' : '' }}">--}}
                    {{--<label class="col-md-4 control-label text-md-right">Joining Day : <span--}}
                                {{--class="required"> * </span></label>--}}
                    {{--<div class="col-md-6 input-group date" id="joining_day" data-target-input="nearest">--}}
                        {{--<input type="text" class="form-control datetimepicker-input" name="joining_day"--}}
                               {{--value="{{Carbon\Carbon::parse(date('Y-m-d ', strtotime($employee->joining_day)))->format('dd-mm-YYYY')}}"--}}
                               {{--data-target="#joining_day"/>--}}
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
                <hr/>

                <div class="form-group row {{ $errors->has('last_working_day') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Last working day : </label>
                    <div class="col-md-6 input-group date" id="last_working_day" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="last_working_day"
                               value="{{ ($employee->last_working_day!=null)? Carbon\Carbon::parse($employee->last_working_day)->format('d-m-Y'):'' }}"
                               data-target="#last_working_day"/>
                        <div class="input-group-append" data-target="#last_working_day"
                             data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    @if ($errors->has('last_working_day'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('last_working_day') }}</strong>
                                    </span>
                    @endif

                </div>


            </div>

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
//            date: moment(),
            format: 'DD-MM-YYYY',
        });
        $('#last_working_day').datetimepicker({
//            date: moment(),
            format: 'DD-MM-YYYY',
        });

    })

</script>


@endpush
@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee_mo','active')
@section('employee_salary','active')
@section('title','Update Employee Payslip')
@push('css')
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee')}}" class="nav-link">Employee</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee_salary')}}" class="nav-link">Employee Salary</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Update Employee Payslip</a>
    </li>
@endsection
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Update Employee Payslip</h3>
                </div>
                {!! Form::model($employee_salary,['method'=>'PATCH', 'route'=>['employee_salary.update',$employee_salary->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
                {{ csrf_field() }}
                {!! Form::hidden('salary_type', 'Payslip' )!!}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right "> Employee:</label>
                                <div class="col-md-7 ">
                                    <label class="col-md-5 control-label text-md-right "> {{$employee_salary->user->name}}</label>
                                    {{--{!! Form::select('user_id', $user, null, array('class' => 'form-control')) !!}--}}

                                    {{--@if ($errors->has('user_id'))--}}
                                    {{--<span class="help-block"><strong>{{ $errors->first('user_id') }}</strong></span>--}}
                                    {{--@endif--}}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row{{ $errors->has('create_date') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right">Create Date : <span
                                            class="required"> * </span></label>
                                <div class="col-md-7 input-group date" id="create_date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" name="create_date"
                                           value="{{ isset($employee_salary) ? $employee_salary->created_at : old('create_date') }}" data-target="#create_date"/>
                                    {{--                                  {!! Form::input('text', 'create_date', \Carbon\Carbon::now()->format('d-M-Y'),['class'=>'form-control']) !!}--}}
                                    <div class="input-group-append" data-target="#create_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                @if ($errors->has('create_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('create_date') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6">

                            <div class="form-group row">
                                <label class="control-label text-md-right col-md-5">Select Month : <span
                                            class="required"> * </span></label>
                                <div class="col-md-7">
                                    <select class="bs-select form-control" name="salary_month">
                                        <option value="01" {{($employee_salary->salary_month==1) ? 'selected' : ''}}>January
                                        </option>
                                        <option value="02" {{($employee_salary->salary_month==2) ? 'selected' : ''}}>February
                                        </option>
                                        <option value="03" {{($employee_salary->salary_month==3) ? 'selected' : ''}}>March
                                        </option>
                                        <option value="04" {{($employee_salary->salary_month==4) ? 'selected' : ''}}>April
                                        </option>
                                        <option value="05" {{($employee_salary->salary_month==5) ? 'selected' : ''}}>May
                                        </option>
                                        <option value="06" {{($employee_salary->salary_month==6) ? 'selected' : ''}}>June
                                        </option>
                                        <option value="07" {{($employee_salary->salary_month==7) ? 'selected' : ''}}>July
                                        </option>
                                        <option value="08" {{($employee_salary->salary_month==8) ? 'selected' : ''}}>August
                                        </option>
                                        <option value="09" {{($employee_salary->salary_month==9) ? 'selected' : ''}}>
                                            September
                                        </option>
                                        <option value="10" {{($employee_salary->salary_month==10) ? 'selected' : ''}}>October
                                        </option>
                                        <option value="11" {{($employee_salary->salary_month==11) ? 'selected' : ''}}>November
                                        </option>
                                        <option value="12" {{($employee_salary->salary_month==12) ? 'selected' : ''}}>December
                                        </option>
                                    </select>
                                </div>
                            </div>



                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="control-label text-md-right col-md-5">Select Year : <span
                                            class="required"> * </span> </label>
                                <div class="col-md-7">
                                    <input class="date-own form-control" type="number" name="year"
                                           value="<?php echo date('Y') ?>">
                                </div>
                            </div>



                            <div class="form-group row{{ $errors->has('salary_amount') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right"> Salary Amount : <span
                                            class="required"> * </span></label>
                                <div class=" col-md-7">
                                    {!! Form::number('salary_amount', null,['class'=>'form-control input-circle','placeholder'=>'Please Enter Salary Amount']) !!}
                                    @if ($errors->has('salary_amount'))
                                        <span class="help-block"><strong>{{ $errors->first('salary_amount') }}</strong></span>
                                    @endif
                                </div>
                            </div>
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

            {{--</form>--}}
            {!! Form::close() !!}
            <!-- END FORM-->
            </div>
        </div>
    </div>
@endsection
@push('js')
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>

<script>

    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#create_date').datetimepicker({
//            date: moment(),
            format: 'DD-MM-Y H:mm:ss',
            // minDate: '03/06/2019',
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

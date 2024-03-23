@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee','active')
@section('add_employee_salary','active')
@section('title','Add Employee Salary')
@push('css')
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}">

@endpush

@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee')}}" class="nav-link">Employee</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee_salary')}}" class="nav-link">Employee Salary</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Employee Salary</a>
    </li>
@endsection
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Employee Salary</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {!! Form::open(['url' => 'employee_salary', 'class' => 'form-horizontal','id'=>'saveForm']) !!}
                {{ csrf_field() }}
                {!! Form::hidden('type', 'Salary Payment' )!!}
                {!! Form::hidden('salary_type', 'Salary' )!!}

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row {{ $errors->has('user_id') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right "> Select Employee: <span
                                            class="required"> * </span></label>
                                <div class="col-md-7 ">
                                    {!! Form::select('user_id', $user, null, array('class' => 'form-control select2bs4','id'=>'user_id','autofocus'=>'autofocus')) !!}

                                    @if ($errors->has('user_id'))
                                        <span class="help-block"><strong>{{ $errors->first('user_id') }}</strong></span>
                                    @endif
                                    <span id="salaryamount" style="color: #9f1447"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row{{ $errors->has('create_date') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right">Payment Date : <span
                                            class="required"> * </span></label>
                                <div class="col-md-7 input-group date" id="create_date" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" name="create_date"
                                           value="{{ old('create_date') }}" data-target="#create_date"/>
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
                                    <select class="bs-select form-control" name="salary_month" id="salary_month">
                                        <option value="01" {{($current_month=='02') ? 'selected' : ''}}>January
                                        </option>
                                        <option value="02" {{($current_month=='03') ? 'selected' : ''}}>February
                                        </option>
                                        <option value="03" {{($current_month=='04') ? 'selected' : ''}}>March
                                        </option>
                                        <option value="04" {{($current_month=='05') ? 'selected' : ''}}>April
                                        </option>
                                        <option value="05" {{($current_month=='06') ? 'selected' : ''}}>May
                                        </option>
                                        <option value="06" {{($current_month=='07') ? 'selected' : ''}}>June
                                        </option>
                                        <option value="07" {{($current_month=='08') ? 'selected' : ''}}>July
                                        </option>
                                        <option value="08" {{($current_month=='09') ? 'selected' : ''}}>August
                                        </option>
                                        <option value="09" {{($current_month=='10') ? 'selected' : ''}}>
                                            September
                                        </option>
                                        <option value="10" {{($current_month=='11') ? 'selected' : ''}}>October
                                        </option>
                                        <option value="11" {{($current_month=='12') ? 'selected' : ''}}>November
                                        </option>
                                        <option value="12" {{($current_month=='01') ? 'selected' : ''}}>December
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row {{ $errors->has('holiday_weekend') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right"> Holiday & Weekend (days) : <span
                                            class="required">  </span></label>
                                <div class=" col-md-7">
                                    {!! Form::number('holiday_weekend', null,['class'=>'form-control input-circle','placeholder'=>'Please Enter days']) !!}
                                    {{--<span class="help-block">Regular, Family, Single, 500ml etc</span>--}}
                                    @if ($errors->has('holiday_weekend'))
                                        <span class="help-block"><strong>{{ $errors->first('holiday_weekend') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('working_day') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right"> Working Day (days) : <span
                                            class="required">  </span></label>
                                <div class=" col-md-7">
                                    {!! Form::number('working_day', null,['class'=>'form-control input-circle','placeholder'=>'Please Enter days']) !!}
                                    {{--<span class="help-block">Regular, Family, Single, 500ml etc</span>--}}
                                    @if ($errors->has('working_day'))
                                        <span class="help-block"><strong>{{ $errors->first('working_day') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('branch') ? ' has-error' : '' }}">
                                <label class="col-sm-5 control-label text-md-right"> Select Branch : <span
                                            class="required"> * </span></label>
                                <div class="col-sm-7">
                                    {{ Form::select('branch', $account, null,['class'=>'form-control '] ) }}
                                    @if ($errors->has('branch'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('branch') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('transaction_method') ? ' has-error' : '' }}">
                                <label class="col-sm-5 control-label text-md-right">Transaction Method : <span
                                            class="required"> * </span></label>
                                <div class="col-sm-7">
                                    {{ Form::select('transaction_method', $transaction_methods, null,['class'=>'form-control select2'] ) }}
                                    @if ($errors->has('transaction_method'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('transaction_method') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group row ">
                                <label class="control-label text-md-right col-md-5">Select Year : <span
                                            class="required"> * </span> </label>
                                <div class="col-md-7">
                                    <input class="date-own form-control" type="number" name="year" id="salary_year"
                                           value="<?php echo date('Y') ?>">
                                </div>
                            </div>


                            <div class="form-group row {{ $errors->has('leave_day') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right"> Leave (days) : <span
                                            class="required">  </span></label>
                                <div class=" col-md-7">
                                    {!! Form::number('leave_day', null,['class'=>'form-control input-circle','placeholder'=>'Please Enter days']) !!}
                                    {{--<span class="help-block">Regular, Family, Single, 500ml etc</span>--}}
                                    @if ($errors->has('leave_day'))
                                        <span class="help-block"><strong>{{ $errors->first('leave_day') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('absent_day') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label text-md-right"> Absent (days) : <span
                                            class="required">  </span></label>
                                <div class=" col-md-7">
                                    {!! Form::number('absent_day', null,['class'=>'form-control input-circle','placeholder'=>'Please Enter days']) !!}
                                    {{--<span class="help-block">Regular, Family, Single, 500ml etc</span>--}}
                                    @if ($errors->has('absent_day'))
                                        <span class="help-block"><strong>{{ $errors->first('absent_day') }}</strong></span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('bank_account') ? ' has-error' : '' }}">
                                <label class="col-sm-5 control-label text-md-right"> Select Account : <span
                                            class="required"> * </span></label>
                                <div class="col-sm-7">
                                    {{ Form::select('bank_account', $to_accounts, null,['class'=>'form-control '] ) }}
                                    @if ($errors->has('bank_account'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('bank_account') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('paidsalary_amount') ? ' has-error' : '' }}">

                                <label class="col-md-5 control-label text-md-right"> Paid Amount : <span
                                            class="required"> * </span></label>
                                <div class=" col-md-7">
                                    {!! Form::text('paidsalary_amount', null,['class'=>'form-control','id'=>'rest_salary']) !!}
                                    @if ($errors->has('paidsalary_amount'))
                                        <span class="help-block"><strong>{{ $errors->first('paidsalary_amount') }}</strong></span>
                                    @endif

                                </div>
                            </div>
                            {{--<div class="form-group row {{ $errors->has('salary_amount') ? ' has-error' : '' }}">--}}
                                {{--<label class="col-md-5 control-label text-md-right"> Salary Amount : <span--}}
                                            {{--class="required">  </span></label>--}}
                                {{--<div class=" col-md-7">--}}
                                    {{--{!! Form::number('salary_amount', null,['class'=>'form-control input-circle','placeholder'=>'Please Enter Salary Amount','id'=>'salaryamount']) !!}--}}
                                    {{--<span class="help-block">Regular, Family, Single, 500ml etc</span>--}}
                                    {{--@if ($errors->has('salary_amount'))--}}
                                        {{--<span class="help-block"><strong>{{ $errors->first('salary_amount') }}</strong></span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}


                            {{--<div class="form-group row">--}}
                                {{--<label class="col-md-5 control-label text-md-right">Comments :</label>--}}
                                {{--<div class="col-md-7">--}}
                                    {{--{!! Form::textarea('comments', null,['class'=>'form-control ', 'placeholder'=>'i.e: Check No : CA123456','rows'=>'3']) !!}--}}
                                {{--</div>--}}
                            {{--</div>--}}

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
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

<script>
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#create_date').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y H:mm:ss',
            // minDate: '03/06/2019',
        });

    })
</script>

<script>
    $(document).ready(function () {
        $("input[name='paidsalary_amount']").click(function () {
            var user_id = $('#user_id').val();
            var month = $('#salary_month').val();
            var year = $('#salary_year').val();
//            console.log(month);
            var token = $("input[name='_token']").val();
            $.ajax({
                url: "employ_salary_rest",
                method: 'POST',
                data: {user_id: user_id, month: month, year: year, _token: token},
//            dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $('#rest_salary').val(data);

//                $('#org_commission').val(data.org_commission);
                }

            });
        });
    });
</script>
<script>
    $("select[name='user_id']").change(function () {
        var user_id = $(this).val();
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "employ_salary_value",
            method: 'POST',
            data: {user_id: user_id, _token: token},
            success: function (data) {
//                $('#salaryamount').val(data.salary_amount);
                $('#salaryamount').text('Salary Amount : '+data.salary_amount);
            }
        });
    });
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

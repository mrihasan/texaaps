@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee_mo','active')
@section('add_payslip','active')
@section('title','Add Payslip')
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
        <a href="#" class="nav-link">Add Payslip</a>
    </li>
@endsection
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Payslip (All Active Employees)</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {!! Form::open(['url' => 'payslip_all_employee','method'=>'POST', 'class' => 'form-horizontal','id'=>'saveForm']) !!}
                {{ csrf_field() }}
{{--                {!! Form::hidden('slip_for', 'Payslip_all' )!!}--}}
{{--                {!! Form::hidden('type', 'Salary Payslip' )!!}--}}

                <div class="card-body">
                    <div class="form-group row {{ $errors->has('type') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label text-md-right">Type : <span
                                    class="required"> * </span></label>
                        <div class=" col-md-6 mt-radio-inline">
                            <label class="mt-radio">
                                {{ Form::radio('type', 'Salary Payslip',true,['autofocus'=>'autofocus' ] ) }} Salary Payslip
                                <span></span>
                            </label>
                            <label class="mt-radio">
                                {{ Form::radio('type', 'Bonus Payslip') }} Bonus Payslip
                                <span></span>
                            </label>
                        </div>

                        @if ($errors->has('type'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="form-group row {{ $errors->has('religion') ? ' has-error' : '' }}" id="religion" style='display:none;'>
                        <label class="col-md-4 control-label text-md-right">Religion : <span
                                    class="required"> * </span></label>
                        <div class=" col-md-6 mt-radio-inline">
                            <select name="religion" class="form-control" >
                                <option value="" >Select Religion</option>
                                <option value="Islam">Islam</option>
                                <option value="Hinduism">Hinduism</option>
                                <option value="Christianity">Christianity</option>
                            </select>
                        </div>

                        @if ($errors->has('religion'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('religion') }}</strong>
                                    </span>
                        @endif
                    </div>


                    <div class="form-group row">
                        <label class="control-label text-md-right col-md-4">Select Month : <span
                                    class="required"> * </span></label>
                        <div class="col-md-6">
                            <select class="bs-select form-control" name="salary_month">
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


                    <div class="form-group row">
                        <label class="control-label text-md-right col-md-4">Select Year : <span
                                    class="required"> * </span> </label>
                        <div class="col-md-6">
                            <input class="date-own form-control" type="number" name="year"
                                   value="<?php echo date('Y') ?>">
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
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Payslip (Missed Employee)</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {!! Form::open(['url' => 'payslip_single_employee','method'=>'POST', 'class' => 'form-horizontal','id'=>'saveForm']) !!}
                {{ csrf_field() }}
{{--                {!! Form::hidden('slip_for', 'Payslip_single' )!!}--}}
{{--                {!! Form::hidden('type', 'Salary Payslip' )!!}--}}

                <div class="card-body">
                    <div class="form-group row {{ $errors->has('type') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label text-md-right">Type : <span
                                    class="required"> * </span></label>
                        <div class=" col-md-6 mt-radio-inline">
                            <label class="mt-radio">
                                {{ Form::radio('type', 'Salary Payslip',true) }} Salary Payslip
                                <span></span>
                            </label>
                            <label class="mt-radio">
                                {{ Form::radio('type', 'Bonus Payslip') }} Bonus Payslip
                                <span></span>
                            </label>
                        </div>

                        @if ($errors->has('type'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div class="form-group row {{ $errors->has('user_id') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label text-md-right "> Select Employee: <span
                                    class="required"> * </span></label>
                        <div class="col-md-6 ">
                            {!! Form::select('user_id', $user, null, array('class' => 'form-control select2bs4')) !!}

                            @if ($errors->has('user_id'))
                                <span class="help-block"><strong>{{ $errors->first('user_id') }}</strong></span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="control-label text-md-right col-md-4">Select Month : <span
                                    class="required"> * </span></label>
                        <div class="col-md-6">
                            <select class="bs-select form-control" name="salary_month">
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


                    <div class="form-group row">
                        <label class="control-label text-md-right col-md-4">Select Year : <span
                                    class="required"> * </span> </label>
                        <div class="col-md-6">
                            <input class="date-own form-control" type="number" name="year"
                                   value="<?php echo date('Y') ?>">
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
    $(document).ready(function(){
        $('input[type="radio"]').click(function(){
            if(this.value=='Bonus Payslip'){
                $("#religion").show();
            }
            else{
                $("#religion").hide();
            }
        });
    });
    $(function () {
//        $('#user_type').on('change', function () {
//            if (this.value == 'Agent') {
//                $("#partner").show();
//                $("#select_units").show();
//            }
//           else if (this.value == 'Client') {
//                $("#partner").hide();
//                $("#select_units").hide();
//            }
//           else if (this.value != 'Agent') {
//                $("#partner").hide();
//                $("#select_units").show();
//            }
//           else if (this.value != 'Client') {
////                $("#partner").hide();
//                $("#select_units").show();
//            }
//            else {
//                $("#partner").hide();
//                $("#select_units").hide();
////                $("#select_units").hide();
//            }
//        });
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

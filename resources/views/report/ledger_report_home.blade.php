@extends('layouts.al305_main')
@section('report_mo','menu-open')
@section('report','active')
@section('ledger_report','active')
@section('title','Ledger Report')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('ledger_report_home')}}" class="nav-link">Ledger Report</a>
    </li>
@endsection

@push('css')
{{--<link href="{{ asset('custom/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />--}}
<!-- Tempusdominus Bbootstrap 4 -->
{{--<link rel="stylesheet"--}}
{{--href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">--}}
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}">


@endpush
@section('maincontent')
    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">User Wise Ledger Report</h3>
            </div>
            <div class="card-body">
                {!! Form::open(array('method' => 'get', 'url' => 'ledger_report_user','class'=>'form-horizontal')) !!}
                {{ csrf_field() }}
                <div class="form-group row {{ $errors->has('user') ? ' has-error' : '' }}">
                    <label for="user" class="col-md-4 control-label text-md-right">Select
                        User:<span class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('user', $user,null, ['class'=>'form-control select2bs4' ] ) }}
                        @if ($errors->has('user'))
                            <span class="help-block"><strong>{{ $errors->first('user') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group ">
                    {!! Form::hidden('start_date', null,['class'=>'StartDate','id'=>'StartDate'] )!!}
                    {!! Form::hidden('end_date', null,['class'=>'EndDate','id'=>'EndDate'] )!!}
                    <label class="control-label col-md-4 text-md-right">{{ __('all_settings.Select Date Ranges') }} :</label>
                    <div class="col-md-7 input-group " style="display: inline-block">
                        <button type="button" class="btn btn-default " id="reportrange">
                            <i class="far fa-calendar-alt"></i>
                            <span> </span>
                            <i class="fas fa-caret-down"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- /.card-body -->
            <div class="card-footer">
                {{--<button type="submit" class="btn btn-default">{{ __('all_settings.Back') }}</button>--}}
                <button type="submit" class="btn btn-info float-right">{{ __('all_settings.Search') }}</button>
            </div>
            <!-- /.card-footer -->
            {!! Form::close() !!}
        </div>
        <div class="card card-success col-md-8">
            <div class="card-header">
                <h3 class="card-title">Account Wise Ledger Report</h3>
            </div>
            <div class="card-body">
                {!! Form::open(array('method' => 'get', 'url' => 'ledger_report_account','class'=>'form-horizontal')) !!}
                {{ csrf_field() }}
                <div class="form-group row {{ $errors->has('account') ? ' has-error' : '' }}">
                    <label for="account" class="col-md-4 control-label text-md-right">Select
                        Account:<span class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('account', $account,null, ['class'=>'form-control select2bs4' ] ) }}
                        @if ($errors->has('account'))
                            <span class="help-block"><strong>{{ $errors->first('account') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group ">
                    {!! Form::hidden('start_date1', null,['class'=>'StartDate1','id'=>'StartDate1'] )!!}
                    {!! Form::hidden('end_date1', null,['class'=>'EndDate1','id'=>'EndDate1'] )!!}
                    <label class="control-label col-md-4 text-md-right">{{ __('all_settings.Select Date Ranges') }} :</label>
                    <div class="col-md-7 input-group " style="display: inline-block">
                        <button type="button" class="btn btn-default " id="reportrange1">
                            <i class="far fa-calendar-alt"></i>
                            <span> </span>
                            <i class="fas fa-caret-down"></i>
                        </button>
                    </div>
                </div>

            </div>

            <!-- /.card-body -->
            <div class="card-footer">
                {{--<button type="submit" class="btn btn-default">{{ __('all_settings.Back') }}</button>--}}
                <button type="submit" class="btn btn-success float-right">{{ __('all_settings.Search') }}</button>
            </div>
            <!-- /.card-footer -->
            {!! Form::close() !!}
        </div>

    </div>

@endsection
@push('js')
<!-- InputMask for Date picker-->
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
{{--<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>--}}
<!-- Tempusdominus Bootstrap 4 -->
{{--<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>--}}
<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>


<script>
    //    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
<script>
    var startDate;
    var endDate;
    var startDate1;
    var endDate1;
    $(document).ready(function () {
        $('#reportrange').daterangepicker(
            {
                startDate: moment().subtract(29,'days'),
                endDate: moment(),
                minDate: '02/01/2015',
                maxDate: '12/31/2050',
//                dateLimit: {days: 60},
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
//                    'Today': [moment(), moment()],
//                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
//                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract(29,'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                    'The year before last year': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')]
                },
                opens: 'right',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'DD/MM/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                startDate = start;
                endDate = end;
//                console.log(startDate.format('L'));
//                console.log(endDate.format('L'));
                $("#StartDate").val(moment(startDate).format('YYYY-MM-DD'));
                $("#EndDate").val(moment(endDate).format('YYYY-MM-DD'));
            }
        );
        //Set the initial state of the picker label
        $('#reportrange span').html(moment().subtract(29,'days').format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
        $("#StartDate").val(moment().subtract(29,'days').format('YYYY-MM-DD'));
        $("#EndDate").val(moment(endDate).format('YYYY-MM-DD'));

        $('#reportrange1').daterangepicker(
            {
                startDate1: moment().subtract(29,'days'),
                endDate1: moment(),
                minDate: '01/01/2015',
                maxDate: '12/31/2050',
//                dateLimit: {days: 60},
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
//                    'Today': [moment(), moment()],
//                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
//                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract(29,'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Last 6 Month': [moment().subtract(6, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                    'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                    'The year before last year': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')]
                },
                opens: 'right',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
                format: 'DD/MM/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                console.log("Callback has been called!");
                $('#reportrange1 span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
                startDate1 = start;
                endDate1 = end;
//                console.log(startDate.format('L'));
//                console.log(endDate.format('L'));
                $("#StartDate1").val(moment(startDate1).format('YYYY-MM-DD'));
                $("#EndDate1").val(moment(endDate1).format('YYYY-MM-DD'));
            }
        );
        //Set the initial state of the picker label
        $('#reportrange1 span').html(moment().subtract(29,'days').format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
        $("#StartDate1").val(moment().subtract(29,'days').format('YYYY-MM-DD'));
        $("#EndDate1").val(moment(endDate).format('YYYY-MM-DD'));

    });

</script>


@endpush
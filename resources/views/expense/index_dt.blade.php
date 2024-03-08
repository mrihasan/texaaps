@extends('layouts.al305_main')
@section('expense_mo','menu-open')
@section('expense','active')
@section('manage_expense','active')
@section('title','Manage Expense')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('expense') }}" class="nav-link">Expense</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage Expense</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
{{--<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">--}}
{{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">
{{--<link rel="stylesheet" href="{{ asset('supporting/sweetalert/sweetalert2.css') }}">--}}

@endpush
@section('maincontent')

    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">{{$header_title}} DT</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                       href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                       aria-selected="false">{{ __('all_settings.Search') }} DT</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    {!! $dataTable->table() !!}
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                     aria-labelledby="custom-tabs-one-profile-tab">
                    <div class="row justify-content-center">
                        <div class="card card-info col-md-8">
                            <div class="card-body">

                                {!! Form::open(array('method' => 'get', 'url' => 'expense_dt','class'=>'form-horizontal')) !!}
                                {!! Form::hidden('start_date', null,['class'=>'StartDate','id'=>'StartDate'] )!!}
                                {!! Form::hidden('end_date', null,['class'=>'EndDate','id'=>'EndDate'] )!!}

                                <div class="form-group ">
                                    <label class="control-label col-md-3 text-md-right">{{ __('all_settings.Select Date Ranges') }} :</label>
                                    <div class="col-md-6 input-group " style="display: inline-block">
                                        <button type="button" class="btn btn-default " id="reportrange">
                                            <i class="far fa-calendar-alt"></i>
                                            <span> </span>
                                            <i class="fas fa-caret-down"></i>
                                        </button>
                                        {{--<button id="saveBtn" type="submit"--}}
                                        {{--class="btn btn-info  searchButton float-right">--}}
                                        {{--Search--}}
                                        {{--</button>--}}
                                    </div>
                                </div>


                                <div class="card-footer">
                                    <button type="submit" class="btn btn-default">{{ __('all_settings.Back') }}</button>
                                    <button type="submit" class="btn btn-info float-right"><i class="fa fa-search"
                                                                                              aria-hidden="true"></i>
                                        Search
                                    </button>
                                </div>


                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
{{--<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>--}}
{{--<script type="text/javascript" src="{{ asset('supporting/sweetalert/sweetalert2.min.js') }}"></script>--}}
{!! $dataTable->scripts() !!}

<script>
    var startDate;
    var endDate;
    $(document).ready(function () {
        $('#reportrange').daterangepicker(
            {
                startDate: moment().subtract(29,'days'),
                endDate: moment(),
                minDate: '01/01/2015',
                maxDate: '12/31/2050',
//                dateLimit: {days: 60},
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1,'days'), moment().subtract(1,'days')],
                    'Last 7 Days': [moment().subtract(6,'days'), moment()],
                    'Last 30 Days': [moment().subtract(29,'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')]
                },
                opens: 'right',
                buttonClasses: ['btn btn-default'],
                applyClass: 'btn-small btn-primary',
                cancelClass: 'btn-small',
//                format: 'DD/MM/YYYY',
                format: 'DD-MM-Y',
//                format: 'dd/mm/yyyy',
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
                $("#StartDate").val(moment(startDate).format('YYYY-MM-DD'));
                $("#EndDate").val(moment(endDate).format('YYYY-MM-DD'));
            }
        );
        //Set the initial state of the picker label
//        $('#reportrange span').html('Please select Date Range');
        $('#reportrange span').html(moment().subtract(29,'days').format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
        $("#StartDate").val(moment().subtract(29,'days').format('YYYY-MM-DD'));
        $("#EndDate").val(moment(endDate).format('YYYY-MM-DD'));
//        $('#reportrange span').html(moment().format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
//        console.log(startDate);

//        $('#saveBtn').click(function(){
//            $("#StartDate").val(moment(startDate).format('YYYY-MM-DD'));
//            $("#EndDate").val(moment(endDate).format('YYYY-MM-DD'));
//            console.log(startDate.format('D MMMM YYYY') + ' - ' + endDate.format('D MMMM YYYY'));
//        });
    });
</script>


@endpush
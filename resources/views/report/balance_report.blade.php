@extends('layouts.al305_main')
@section('report_mo','menu-open')
@section('report','active')
@section('balance_report','active')
@section('title','Balance Report')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Balance Report</a>
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
    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">{{$title_date_range}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-sales-tab" data-toggle="pill"
                       href="#custom-tabs-one-sales" role="tab" aria-controls="custom-tabs-one-sales"
                       aria-selected="false">Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-purchase-tab" data-toggle="pill"
                       href="#custom-tabs-one-purchase" role="tab" aria-controls="custom-tabs-one-purchase"
                       aria-selected="false">Purchase</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-receipt-tab" data-toggle="pill"
                       href="#custom-tabs-one-receipt" role="tab" aria-controls="custom-tabs-one-receipt"
                       aria-selected="false">Receipt</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-payment-tab" data-toggle="pill"
                       href="#custom-tabs-one-payment" role="tab" aria-controls="custom-tabs-one-payment"
                       aria-selected="false">Payment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-expense-tab" data-toggle="pill"
                       href="#custom-tabs-one-expense" role="tab" aria-controls="custom-tabs-one-expense"
                       aria-selected="false">Expense</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-salary-tab" data-toggle="pill"
                       href="#custom-tabs-one-salary" role="tab" aria-controls="custom-tabs-one-salary"
                       aria-selected="false">Salary</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    {!! Form::open(array('method' => 'get', 'url' => 'balance_report','class'=>'form-horizontal')) !!}
                    {{ csrf_field() }}

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

                    <div class="table-scrollable">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th colspan="2" style="text-align:center">Balance Summary of Billed</th>
                                {{--<th style="text-align:center">Billed </th>--}}
                                <th colspan="4" style="text-align:center">Balance Summary of Ledger</th>
                                {{--<th style="text-align:center">Collected/Paid</th>--}}
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="col-md-4" style="text-align:right">Balance:&nbsp;&nbsp;</th>
                                <th class="col-md-2" style="text-align:right">{{$balance}}</th>
                                <th class="col-md-4" style="text-align:right">Balance:&nbsp;&nbsp;</th>
                                <th class="col-md-2" style="text-align:right">{{$balance_collect}}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <tr>
                                <td class="col-md-4" style="text-align:right">Balance b/d (brought down): &nbsp;&nbsp;
                                </td>
                                <td class="col-md-2" style="text-align:right"> {{$balance_bd}}</td>
                                <td class="col-md-4" style="text-align:right">Balance b/d (brought down): &nbsp;&nbsp;
                                </td>
                                <td class="col-md-2" style="text-align:right">{{$balance_bd_collect}}</td>
                            </tr>
                            <tr>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong> Total Sales : &nbsp;&nbsp;</strong></td>
                                <td class="col-md-2" style="text-align:right"> {{$total_salesamount}}</td>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong> Total Receipt : &nbsp;&nbsp;</strong></td>
                                <td class="col-md-2"
                                    style="text-align:right; color: green">{{$total_salesamount_collect}}</td>
                            </tr>
                            <tr>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong> Total Purchase : &nbsp;&nbsp;</strong></td>
                                <td class="col-md-2" style="text-align:right"> {{$total_purchaseamount}}</td>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong> Total Payment : &nbsp;&nbsp;</strong></td>
                                <td class="col-md-2"
                                    style="text-align:right; color: red">{{$total_purchaseamount_paid}}</td>
                            </tr>
                            <tr>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong>Total Expense :&nbsp;&nbsp;</strong></td>
                                <td class="col-md-2" style="text-align:right">{{$total_expense}}</td>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong>Total Expense :&nbsp;&nbsp;</strong></td>
                                <td class="col-md-2" style="text-align:right; color: red">{{$total_expense}}</td>
                            </tr>
                            <tr>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong>Total Employee Salary :&nbsp;&nbsp;</strong></td>
                                <td class="col-md-2" style="text-align:right">{{$total_salary}}</td>
                                <td class="col-md-4" style="text-align:right">
                                    ({{Carbon\Carbon::parse($start_date)->format('d-M-Y').' to '.Carbon\Carbon::parse($end_date)->format('d-M-Y')}}
                                    )
                                    <strong>Total Employee Salary :&nbsp;&nbsp;</strong></td>
                                <td class="col-md-2" style="text-align:right; color: red">{{$total_salary}}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{--<button type="submit" class="btn btn-default">{{ __('all_settings.Back') }}</button>--}}
                        <button type="submit" class="btn btn-info float-right">{{ __('all_settings.Search') }}</button>
                    </div>
                    <!-- /.card-footer -->
                    {!! Form::close() !!}
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-sales" role="tabpanel"
                     aria-labelledby="custom-tabs-one-sales-tab">
                    <table class="table dataTables table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-1">S.No</th>
                            <th class="col-md-7"> Sales to</th>
                            <th class="col-md-2">Transaction Date</th>
                            <th class="col-md-2">Invoice Total</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4" style="text-align:right">Total Sales: ৳ {{$total_salesamount}}
                                &nbsp;&nbsp;
                            </th>
                            {{--<th style="text-align:right"> ৳ {{$total_salesamount_collect}}</th>--}}
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($sales as $key=>$sec)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{$sec->user->name}}</td>
                                <td>{{Carbon\Carbon::parse($sec->transaction_date)->format('d-M-Y')}}</td>
                                <td style="text-align:right">{{ $sec->invoice_total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-purchase" role="tabpanel"
                     aria-labelledby="custom-tabs-one-purchase-tab">
                    <table class="table dataTables table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-1">S.No</th>
                            <th class="col-md-7"> Purchase From</th>
                            <th class="col-md-2">Transaction Date</th>
                            <th class="col-md-2">Bill Amount</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4" style="text-align:right">Total Purchase :
                                ৳ {{$total_purchaseamount}} &nbsp;&nbsp;
                            </th>
                            {{--<th  style="text-align:right"> ৳ {{$total_purchaseamount_paid}}</th>--}}
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($purchase as $key=>$section)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{$section->user->name}}</td>
                                <td>{{Carbon\Carbon::parse($section->transaction_date)->format('d-M-Y')}}</td>
                                <td style="text-align:right">{{ $section->invoice_total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-receipt" role="tabpanel"
                     aria-labelledby="custom-tabs-one-receipt-tab">
                    <table class="table dataTables table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-1">S.No</th>
                            <th class="col-md-7"> Collect From</th>
                            <th class="col-md-2">Transaction Date</th>
                            <th class="col-md-2">Receipt Amount</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4" style="text-align:right">Total Collection:
                                ৳ {{$total_salesamount_collect}}
                                &nbsp;&nbsp;
                            </th>
                            {{--<th style="text-align:right"> ৳ {{$total_salesamount_collect}}</th>--}}
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($receipt as $key=>$sec)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{$sec->user->name.' , '.$sec->user->profile->mobile}}</td>
                                <td>{{Carbon\Carbon::parse($sec->transaction_date)->format('d-M-Y')}}</td>
                                <td style="text-align:right">{{ $sec->amount}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-payment" role="tabpanel"
                     aria-labelledby="custom-tabs-one-payment-tab">
                    <table class="table dataTables table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-1">S.No</th>
                            <th class="col-md-7"> Paid to</th>
                            <th class="col-md-2">Transaction Date</th>
                            <th class="col-md-2">Paid Amount</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="4" style="text-align:right">Total Payment:
                                ৳ {{$total_purchaseamount_paid}} &nbsp;&nbsp;
                            </th>
                            {{--<th  style="text-align:right"> ৳ {{$total_purchaseamount_paid}}</th>--}}
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($payment as $key=>$section)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{$section->user->name}}</td>
                                <td>{{Carbon\Carbon::parse($section->transaction_date)->format('d-M-Y')}}</td>
                                <td style="text-align:right">{{ $section->amount}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-expense" role="tabpanel"
                     aria-labelledby="custom-tabs-one-expense-tab">
                    <table class="table dataTables table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-1">S.No</th>
                            <th class="col-md-7"> Expense</th>
                            <th class="col-md-2">Date of Expense</th>
                            <th class="col-md-2">Amount</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="3" style="text-align:right">Total Expense:&nbsp;&nbsp;</th>
                            <th style="text-align:right">৳ {{$total_expense}}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($expense as $key=>$section)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $section->expense_type->expense_name }}</td>
                                {{--<td>{{ $section->expense_date }}</td>--}}
                                <td>{{Carbon\Carbon::parse($section->expense_date)->format('d-M-Y')}}</td>
                                <td style="text-align:right">{{ $section->expense_amount }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-salary" role="tabpanel"
                     aria-labelledby="custom-tabs-one-salary-tab">
                    <table class="table dataTables table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="col-md-1">S.No</th>
                            <th class="col-md-7"> Salary Details</th>
                            <th class="col-md-2">Payment Date</th>
                            <th class="col-md-2">Amount</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th colspan="3" style="text-align:right">Total Salary:&nbsp;&nbsp;</th>
                            <th style="text-align:right">৳ {{$total_salary}}</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($salary as $key=>$section)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $section->user->name.' ( '.date('F', mktime(0, 0, 0, $section->salary_month, 10)).' - '. $section->year.' )' }}</td>
                                {{--<td>{{ $section->expense_date }}</td>--}}
                                <td>{{Carbon\Carbon::parse($section->created_at)->format('d-M-Y')}}</td>
                                <td style="text-align:right">{{ $section->paidsalary_amount }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
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
                startDate: moment().subtract('days', 29),
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
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
//                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
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
        $('#reportrange span').html(moment().subtract('days', 29).format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
        $("#StartDate").val(moment().subtract('days', 29).format('YYYY-MM-DD'));
        $("#EndDate").val(moment(endDate).format('YYYY-MM-DD'));

    });

</script>


@endpush
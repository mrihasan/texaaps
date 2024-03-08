@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('manage_ledger_banking','active')
@section('title','Ledger Report')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('ledger_banking')}}" class="nav-link">Ledger Banking</a>
    </li>
@endsection

@push('css')
<style>
    .total{
        font-weight: bolder;
        text-align: right;
    }
</style>

<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">

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
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                       href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                       aria-selected="false">{{ __('all_settings.Search') }}</a>
                </li>
            </ul>
        </div>
        {{--<div class="card-body">--}}
        <div class="tab-content" id="custom-tabs-one-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                 aria-labelledby="custom-tabs-one-home-tab">
                <div class="card-body">
                    <table class="table dataTables table-striped table-bordered table-hover">
                        <thead>
                        <tr style="background-color: #dff0d8">
                            <th>SN</th>
                            <th>{{ __('all_settings.Transaction') }} <br/>Date</th>
                            <th>{{ __('all_settings.Transaction') }} <br/>Code</th>
                            <th>Account <br/>Name</th>
                            <th>Particulars</th>
                            <th>Deposit <br/>Amount</th>
                            <th>Cost <br/>Amount</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="total"></td>
                            <td class="total"></td>
                            <td></td>
                        </tr>
                        </tfoot>
                        @foreach($ledger as $key=>$data)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{Carbon\Carbon::parse($data['transaction_date'][$key])->format('d-M-Y') }}</td>
                                <td>{{$data['transaction_code'][$key]}}</td>
                                <td>{{$data['account_name'][$key]}}</td>
                                <td style="font-size: small">{{$data['reference'][$key]}}</td>
                                <td style="text-align: right">{{($data['transaction_type'][$key]=='Credit'||$data['transaction_type'][$key]=='Received')?$data['transaction_amount'][$key]:''}}</td>
                                <td style="text-align: right">{{($data['transaction_type'][$key]=='Debit'||$data['transaction_type'][$key]=='Withdraw')?$data['transaction_amount'][$key]:''}}</td>
                                {{--                                <td style="text-align: right">{{($data['transaction_type'][$key]=='Investment')?$data['transaction_amount'][$key]:''}}</td>--}}
                                {{--<td>{{$data['transaction_amount'][$key]}}</td>--}}
                                <td style="text-align: right">{{$data['balance'][$key]}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                 aria-labelledby="custom-tabs-one-profile-tab">

                <div class="card-body">
                    {!! Form::open(array('method' => 'get', 'url' => 'ledger_banking_report','class'=>'form-horizontal')) !!}
                    {{ csrf_field() }}
                    <div class="form-group row {{ $errors->has('account') ? ' has-error' : '' }}">
                        <label for="account" class="col-md-4 control-label text-md-right">Select
                            Account:</label>
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
            {{--</div>--}}
        </div>
        <!-- /.card -->
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
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>


<script>
    //    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
<script>
    var startDate1;
    var endDate1;
    $(document).ready(function () {
        $('#reportrange1').daterangepicker(
            {
                startDate1: moment().subtract('days', 29),
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
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
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
        $('#reportrange1 span').html(moment().subtract('days', 29).format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
        $("#StartDate1").val(moment().subtract('days', 29).format('YYYY-MM-DD'));
        $("#EndDate1").val(moment(endDate1).format('YYYY-MM-DD'));

    });

</script>

<script>
    $(document).ready(function () {
        $('.dataTables').DataTable({
            aaSorting: [],
            lengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"] // change per page values here
            ],
            pageLength: 25,
            responsive: true,
            fixedHeader: true,
//            dom: '<"html5buttons"B>lTfgtip',
            'dom': "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: '{{$title_date_range}}'},
//                {extend: 'pdf', title: 'DVL Transaction Data'},
                {
                    extend: 'pdfHtml5',
                    className: 'btn  btn-sm btn-table',
                    titleAttr: 'Export to Pdf',
                    text: '<span class="fa fa-file-pdf-o fa-lg"></span><i class="hidden-xs hidden-sm hidden-md"> Pdf</i>',
                    filename: '{{$title_date_range}}',
                    extension: '.pdf',
                    orientation: 'portrait',
                    title: "{{$title_date_range}}",
                    footer: true,
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)',
                        orthogonal: "Export-pdf"
                    },
                    customize: function (doc) {
                        var rowCount = doc.content[1].table.body.length;
                        for (i = 1; i < rowCount; i++) {

                            /*var val = document.form1.campo.value;
                             if (isNaN(val)){
                             alert(‘Il valore inserito non è numerico’);
                             } else {
                             alert(‘Il valore inserito è numerico’);
                             }*/
                            doc.content[1].table.body[i][0].alignment = 'center';
                            doc.content[1].table.body[i][1].alignment = 'left';
                            doc.content[1].table.body[i][2].alignment = 'left';
                            doc.content[1].table.body[i][3].alignment = 'left';
                            doc.content[1].table.body[i][4].alignment = 'right';
                            doc.content[1].table.body[i][5].alignment = 'right';
                            doc.content[1].table.body[i][6].alignment = 'right';
                        }
                        doc.content[1].table.widths = ['5%', '10%', '15%', '40%', '10%', '10%', '10%'];
//                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.content.splice(0, 1);
                        var now = new Date();
                        var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear() + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
                        var logo = '';
                        {{--var header_title = '{{$title_date_range}}';--}}
                            doc.pageMargins = [10, 50, 10, 40];
                        doc.defaultStyle.fontSize = 7;

                        doc.defaultStyle.alignment = 'left';
                        doc.styles.tableHeader.alignment = 'left';
                        doc.styles.tableHeader.fontSize = 10;
                        doc.styles.tableFooter.fontSize = 10;
                        doc['header'] = (function () {
                            return {
                                columns: [

                                    {
                                        alignment: 'left',
                                        italics: true,
                                        text: '{{$title_date_range}}',
                                        fontSize: 8,
                                        margin: [10, 0]
                                    },
                                    {
                                        //image: logo,
                                        alignment: 'right',
                                        width: 20,
                                        height: 20,
                                        image: 'data:image/png;base64,{{$settings->logo_base64}}'

                                    }
                                    {{--,--}}
                                    {{--{--}}
                                    {{--alignment: 'right',--}}
                                    {{--fontSize: 10,--}}
                                    {{--text: '{{$settings->org_name}}'--}}
                                    {{--}--}}
                                ],
                                margin: 20
                            };
                        });
                        doc['footer'] = (function (page, pages) {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        text: ['Print On: ', {text: jsDate.toString()}]
                                    },
                                    {
                                        alignment: 'centre',
                                        text: '{{$settings->org_name.' : '.$settings->org_slogan}}'
                                    },

                                    {
                                        alignment: 'right',
                                        text: ['Pages ', {text: page.toString()}, ' of ', {text: pages.toString()}]
                                    }
                                ],
                                margin: 20
                            };
                        });
                        var objLayout = {};
                        objLayout['hLineWidth'] = function (i) {
                            return .5;
                        };
                        objLayout['vLineWidth'] = function (i) {
                            return .5;
                        };
                        objLayout['hLineColor'] = function (i) {
                            return '#aaa';
                        };
                        objLayout['vLineColor'] = function (i) {
                            return '#aaa';
                        };
                        objLayout['paddingLeft'] = function (i) {
                            return 4;
                        };
                        objLayout['paddingRight'] = function (i) {
                            return 4;
                        };
                        doc.content[0].layout = objLayout;
                    }
                },

                {
                    extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
//                nb_cols = api.columns().nodes().length;
                nb_cols = 7;
                var j = 5;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return (Number(a) + Number(b)).toFixed(2);
                        }, 0);
                    // Update footer
                    $(api.column(j).footer()).html(pageTotal);
                    j++;
                }
            }

        });
    });

</script>


@endpush
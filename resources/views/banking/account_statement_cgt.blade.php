@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('manage_account_statement','active')
@section('title','Account Statement')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Account Statement</a>
    </li>
@endsection

@push('css')
<style>
    .total{
        font-weight: bolder;
        text-align: right;
    }
</style>
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">

@endpush
@section('maincontent')
    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">{{$header_title}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                       href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                       aria-selected="false">{{ __('all_settings.Search') }}</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <table class="table dataTables table-striped table-bordered table-hover " >
                        <thead>
                        <tr>
                            <th>{{ __('all_settings.Transaction') }} <br/>Date</th>
                            <th>Particulars</th>
                            <th>Ref.No</th>
                            <th>Ref.Date</th>
                            <th>{{ __('all_settings.Transaction') }} <br/>Type</th>
                            <th>{{ __('all_settings.Transaction') }} <br/>Code</th>
                            <th>Credit<br/>Amount(+)</th>
                            <th>Debit<br/>Amount(-)</th>
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
                                <td>{{Carbon\Carbon::parse($data['transaction_date'][$key])->format('d-M-Y, h:ia') }}</td>
                                <td>{{$data['reference'][$key]}}</td>
                                <td>{{$data['ref_no'][$key]}}</td>
                                <td>{{$data['ref_date'][$key]}}</td>
                                <td>{{$data['transaction_type'][$key] }}</td>
                                <td>{{$data['transaction_code'][$key]}}</td>
                                {{--<td style="text-align: right">{{($data['transaction_type'][$key]=='Credited'||$data['transaction_type'][$key]=='Receipt'||$data['transaction_type'][$key]=='Deposit')?$data['transaction_amount'][$key]:''}}</td>--}}
                                {{--<td style="text-align: right">{{($data['transaction_type'][$key]=='Debited'||$data['transaction_type'][$key]=='Payment'||$data['transaction_type'][$key]=='Withdraw')?$data['transaction_amount'][$key]:''}}</td>--}}
                                <td style="text-align: right">{{($data['transaction_type'][$key]=='Credited'||$data['transaction_type'][$key]=='Receipt'||
                                $data['transaction_type'][$key]=='Deposit'||$data['transaction_type'][$key]=='Loan'||$data['transaction_type'][$key]=='Investment')?$data['transaction_amount'][$key]:''}}</td>
                                <td style="text-align: right">{{($data['transaction_type'][$key]=='Debited'||$data['transaction_type'][$key]=='Payment'||
                                $data['transaction_type'][$key]=='Withdraw'||$data['transaction_type'][$key]=='Loan Payment'||$data['transaction_type'][$key]=='Profit Share')?$data['transaction_amount'][$key]:''}}</td>

                                <td style="text-align: right">{{$data['balance'][$key]}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                     aria-labelledby="custom-tabs-one-profile-tab">

                    {!! Form::open(array('method' => 'get', 'url' => 'account_statement','class'=>'form-horizontal')) !!}
                    {!! Form::hidden('start_date', null,['class'=>'StartDate','id'=>'StartDate'] )!!}
                    {!! Form::hidden('end_date', null,['class'=>'EndDate','id'=>'EndDate'] )!!}

                    <div class="form-group row{{ $errors->has('bank_account') ? 'has-error' : '' }}">
                        <label for="roles" class="col-md-4 control-label text-right">Select Account :<span
                                    class="required"> * </span></label>
                        <div class="col-md-6">
                            <select name="bank_account" class="form-control select2" style="width: 100%;" id="bank_account">
                                @foreach($to_accounts as $key=>$to_account)
                                    <option value="{{ $key }}">{{ $to_account}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($errors->has('bank_account'))
                            <em class="invalid-feedback">
                                {{ $errors->first('bank_account') }}
                            </em>
                        @endif
                    </div>

                    <div class="form-group ">
                        <label class="control-label col-md-4 text-right">{{ __('all_settings.Select Date Ranges') }}</label>
                        <div class="col-md-6 input-group " style="display: inline-block">
                            <button type="button" class="btn btn-default " id="reportrange">
                                <i class="far fa-calendar-alt"></i>
                                <span> </span>
                                <i class="fas fa-caret-down"></i>
                            </button>
                            <button id="saveBtn" type="submit"
                                    class="btn btn-info  searchButton float-right">
                                Search
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
@endsection

@push('js')
{{--<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>--}}
{{--<script src="{!! asset('alte305/plugins/daterangepicker/daterangepicker.js')!!}"></script>--}}
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>--}}
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>

<script>
    $(document).ready(function () {
        var table = $('.dataTables').DataTable({
            aaSorting: [],
            lengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"] // change per page values here
            ],
            pageLength: 25,
            responsive: true,
            fixedHeader: true,
            'dom': "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'print',
                    footer: true,
                    page: 'current',
                    messageTop: '{{$header_title}}',
                    messageBottom: '{{'Printed On: '.\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn  btn-sm btn-table',
                    titleAttr: 'Export to Pdf',
                    text: '<span class="fa fa-file-pdf-o fa-lg"></span><i class="hidden-xs hidden-sm hidden-md"> Pdf</i>',
                    filename: '{{$header_title}}',
                    extension: '.pdf',
                    orientation: 'portrait',
                    title: "{{$header_title}}",
                    footer: true,
                    exportOptions: {
                        columns: ':visible:not(.not-export-col)',
                        orthogonal: "Export-pdf"
                    },
                    customize: function (doc) {
                        var rowCount = doc.content[1].table.body.length;
                        for (i = 1; i < rowCount; i++) {
                            doc.content[1].table.body[i][0].alignment = 'center';
                            doc.content[1].table.body[i][1].alignment = 'left';
                            doc.content[1].table.body[i][2].alignment = 'left';
                            doc.content[1].table.body[i][3].alignment = 'right';
                            doc.content[1].table.body[i][4].alignment = 'left';
                            doc.content[1].table.body[i][5].alignment = 'left';
                            doc.content[1].table.body[i][6].alignment = 'center';
                        }
                        doc.content[1].table.widths = ['10%','15%','25%','10%','15%','20%','5%'];
                        doc.content.splice(0, 1);
                        var now = new Date();
                        var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear() + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
                        var logo = '';
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
                                        text: '{{$header_title}}',
                                        fontSize: 10,
                                        margin: [10, 0]
                                    },
                                    {
                                        //image: logo,
                                        alignment: 'center',
                                        width: 20,
                                        height: 20,
                                        image: 'data:image/png;base64,{{$settings->logo_base64}}'

                                    },
                                    {
                                        alignment: 'right',
                                        fontSize: 10,
                                        text: '{{ config('app.name', 'EIS') }}'
                                    }
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
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                var nb_cols = api.columns().nodes().length - 1; // Exclude the last column
                var j = 6;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return (Number(a) + Number(b));
                        }, 0);

                    // Update footer for DataTables view
                    $(api.column(j).footer()).html(pageTotal.toFixed(2));

                    j++;
                }
            }
        });

        // Recalculate footer totals before print
        window.addEventListener('beforeprint', function () {
            updatePrintFooterTotals();
        });

        // Function to update footer totals for print view
        function updatePrintFooterTotals() {
            var api = table.api();
            var nb_cols = api.columns().nodes().length - 1; // Exclude the last column
            var pageTotal = [];
            var j = 6;
            while (j < nb_cols) {
                pageTotal[j] = api
                    .column(j, {page: 'all'})
                    .data()
                    .reduce(function (a, b) {
                        return a + Number(b);
                    }, 0);
                j++;
            }

            // Update footer for print view
            $('.dataTables_scrollFootInner tfoot').children('tr').children('th').each(function (index) {
                if (index >= 6 && index < nb_cols) {
                    $(this).text(pageTotal[index].toFixed(2));
                }
            });
        }
    });

</script>
<script>
    var startDate;
    var endDate;
    $(document).ready(function () {
        $('#reportrange').daterangepicker(
            {
                startDate: moment().subtract('days', 29),
                endDate: moment(),
                minDate: '01/01/2015',
                maxDate: '12/31/2050',
//                dateLimit: {days: 60},
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
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
        $('#reportrange span').html(moment().subtract('days', 29).format('D MMMM YYYY') + ' - ' + moment().format('D MMMM YYYY'));
        $("#StartDate").val(moment().subtract('days', 29).format('YYYY-MM-DD'));
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
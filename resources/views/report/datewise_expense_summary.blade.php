@extends('layouts.al305_main')
@section('report_mo','menu-open')
@section('report_mo','active')
@section('report_expense_date','active')
@section('title','Date Wise Expense Report')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('branch')}}" class="nav-link">Report</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Date Wise Expense Summary Report</a>
    </li>
@endsection

@push('css')
{{--<link rel="stylesheet" href="{{ asset('alte305/plugins/daterangepicker/daterangepicker.css') }}">--}}
{{--<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">--}}
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">

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
                       aria-selected="false">{{ __('all_settings.Manage Search') }}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <table class="table dataTables table-striped table-bordered table-hover">
                        <thead>
                        <tr style="background-color: #dff0d8">
                            <th>S.No</th>
                            <th>Date of Expense</th>
                            <th>Number of Expense</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr style="background-color: #dff0d8">
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($expense as $key=>$section)
                            <tr>
                                <td>{{ $key+1 }}</td>

                                <td>{{ Carbon\Carbon::parse($section->expense_date)->format('d-M-Y') }}</td>
                                <td style="text-align:right">{{$section->total}}</td>
                                <td style="text-align:right">{{ $section->expense_amount }}</td>
                                <td>
                                    <a href="{{ url('datewise_expense_details/'.Carbon\Carbon::parse($section->expense_date)->format('Y-m-d')) }}" class="btn btn-success btn-xs" title="View "><span class="far fa-eye" aria-hidden="true"/></a>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                     aria-labelledby="custom-tabs-one-profile-tab">
                    <div class="row justify-content-center">
                        <div class="card card-info col-md-8">
                            <div class="card-body">

                                {!! Form::open(array('method' => 'get', 'url' => 'datewise_expense_summary','class'=>'form-horizontal')) !!}
                                {{--{!! Form::hidden('start_date', null,['class'=>'StartDate','id'=>'StartDate'] )!!}--}}
                                {{--{!! Form::hidden('end_date', null,['class'=>'EndDate','id'=>'EndDate'] )!!}--}}
                                <input type="hidden" id="StartDate" name="start_date" >
                                <input type="hidden" id="EndDate" name="end_date" >
                                {{--<div class="form-group ">--}}
                                    {{--<label class="control-label col-md-3 text-md-right">{{ __('all_settings.Select Date Ranges') }} :</label>--}}
                                    {{--<div class="col-md-6 input-group " style="display: inline-block">--}}
                                        {{--<button type="button" class="btn btn-default " id="reportrange">--}}
                                            {{--<i class="far fa-calendar-alt"></i>--}}
                                            {{--<span> </span>--}}
                                            {{--<i class="fas fa-caret-down"></i>--}}
                                        {{--</button>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group ">
                                    <label class="control-label col-md-3 text-md-right">{{ __('all_settings.Select Date Ranges') }} :</label>
                                    <div class="col-md-6 input-group " style="display: inline-block">
                                        <button type="button" class="btn btn-default " id="reportrange">
                                            <i class="far fa-calendar-alt"></i> &nbsp;
                                            <span> </span>
                                            <b class="fa fa-angle-down"></b>
                                        </button>
                                    </div>
                                </div>


                                <div class="form-group row {{ $errors->has('approval_type') ? ' has-error' : '' }}">
                                    <label class="col-md-3 control-label text-md-right">Approval Type : <span
                                                class="required"> * </span></label>
                                    <div class=" col-md-6 mt-radio-inline">
                                        <label class="mt-radio">
                                            {{ Form::radio('approval_type', 'Approved',true) }} Approved
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            {{ Form::radio('approval_type', 'Submitted') }} Non Approved
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            {{ Form::radio('approval_type', 'All') }} All
                                            <span></span>
                                        </label>
                                    </div>

                                    @if ($errors->has('approval_type'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('approval_type') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-default">{{ __('all_settings.Back') }}</button>
                                    <button type="submit" id="saveBtn" class="btn btn-info float-right"><i class="fa fa-search" aria-hidden="true"></i> {{ __('all_settings.Search') }}</button>
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
{{--<script src="{!! asset('alte305/plugins/daterangepicker/daterangepicker.js')!!}"></script>--}}
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>--}}
<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
{{--<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>--}}

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
//                {extend: 'pdf', title: 'DVL Expense Report'},
                {
                    extend: 'pdfHtml5',
                    className:'btn  btn-sm btn-table',
                    titleAttr: 'Export to Pdf',
                    text: '<span class="fa fa-file-pdf-o fa-lg"></span><i class="hidden-xs hidden-sm hidden-md"> Pdf</i>',
                    filename: '{{$title_date_range}}',
                    extension: '.pdf',
                    orientation : 'portrait',
                    title: "{{$title_date_range}}",
                    footer:true,
                    exportOptions:{
                        columns:':visible:not(.not-export-col)',
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
                            doc.content[1].table.body[i][2].alignment = 'center';
                            doc.content[1].table.body[i][3].alignment = 'right';
                            doc.content[1].table.body[i][4].alignment = 'center';
                        }
                        doc.content[1].table.widths = [ '10%', '30%','20%', '30%','10%'];
//                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.content.splice(0,1);
                        var now = new Date();
                        var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
                        var logo = '';
                        {{--var header_title = '{{$title_date_range}}';--}}
                            doc.pageMargins = [10,50,10,40];
                        doc.defaultStyle.fontSize = 7;

                        doc.defaultStyle.alignment = 'left';
                        doc.styles.tableHeader.alignment = 'left';
                        doc.styles.tableHeader.fontSize = 8;
                        doc.styles.tableFooter.fontSize = 8;
                        doc['header']=(function() {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        italics: true,
                                        text:  '{{$title_date_range}}',
                                        fontSize: 8,
                                        margin: [10,0]
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
                        doc['footer']=(function(page,pages) {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        text: ['Print On: ', { text: jsDate.toString() }]
                                    },
                                    {
                                        alignment: 'centre',
                                        text: '{{$settings->org_name.' : '.$settings->org_slogan}}'
                                    },

                                    {
                                        alignment: 'right',
                                        text: ['Pages ', { text: page.toString() },	' of ',	{ text: pages.toString() }]
                                    }
                                ],
                                margin: 20
                            };
                        });
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        objLayout['hLineColor'] = function(i) { return '#aaa'; };
                        objLayout['vLineColor'] = function(i) { return '#aaa'; };
                        objLayout['paddingLeft'] = function(i) { return 4; };
                        objLayout['paddingRight'] = function(i) { return 4; };
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
                nb_cols = 4;
                var j = 2;
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
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
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
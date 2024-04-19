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
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/sweetalert/sweetalert2.css') }}">

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
                    <table class="table dataTables table-striped table-bordered table-hover">
                        <thead>
                        <tr style="background-color: #dff0d8">
                            <th>S.No</th>
                            <th>Date of Expense</th>
                            <th> Expense Name</th>
                            <th>Amount</th>
                            <th>Branch</th>
                            <th>Comments</th>
                            <th>Sl No</th>
                            <th>Submitted By</th>
                            <th>Checked By</th>
                            <th>Approved By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr style="background-color: #dff0d8">
                            <th></th>
                            <th></th>
                            <th>Total:</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($expense as $key=>$section)
                            <tr>
                                <td>{{ $key+1 }}</td>

                                <td>{{ Carbon\Carbon::parse($section->expense_date)->format('d-M-Y') }}</td>
                                <td>
                                    {{ $section->expense_type->expense_name }}
                                </td>
                                <td style="text-align:right">{{ $section->expense_amount }}</td>
                                <td>{{ $section->branch->title }}</td>
                                <td>{{ $section->comments }}</td>
                                <td>{{ $section->sl_no }}</td>
                                <td>{{ $section->user->name }}</td>
                                <td>
                                    @if( $section->checked_by == null && Auth::user()->hasRole('Checked'))
                                        <button class="btn btn-warning btn-xs" title="Verify" type="button"
                                                onclick="checkedPost({{$section->id}})">
                                            <i class="fa fa-check-circle"></i>
                                            <span>Check please</span>
                                        </button>
                                        <form method="post" action="{{route('checked_expense', $section->id)}}"
                                              id="check-form{{$section->id}}" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    @elseif( $section->checked_by == null)
                                        <span class="right badge badge-danger">Not Yet Checked</span>
                                    @else
                                        {{$section->checkedBy->name??''}}
                                    @endif
                                </td>

                                <td>
                                    @if($section->checked_by == null)
                                        <span class="right badge badge-danger">Not Yet Verified</span>
                                    @else

                                    @if( $section->approved_by == null && Auth::user()->hasRole('Approval'))
                                        <button class="btn btn-success btn-xs" title="Approve" type="button"
                                                onclick="approvePost({{$section->id}})">
                                            <i class="fa fa-check-circle"></i>
                                            <span>Approve Please</span>
                                        </button>
                                        <form method="post" action="{{route('approve_expense', $section->id)}}"
                                              id="approval-form{{$section->id}}" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    @elseif( $section->approved_by == null)
                                        <span class="right badge badge-danger">Not Yet Approved</span>
                                    @else
                                        {{$section->approvedBy->name}}
                                    @endif
                                    @endif

                                </td>

                                <td>
                                    <a href="{{ url('expense/'.$section->id) }}" class="btn btn-success btn-xs" title="View "><span class="far fa-eye" aria-hidden="true"></span></a>
                                    @can('ExpenseAccess')
                                    <a href="{{ url('expense/' . $section->id . '/edit') }}" class="btn btn-info btn-xs"
                                       title="Edit"><span class="far fa-edit" aria-hidden="true"></span></a>
                                    @endcan
                                    @can('ExpenseDelete')

                                    {!! Form::open([
                                        'method'=>'DELETE',
                                        'url' => ['expense', $section->id],
                                        'style' => 'display:inline'
                                    ]) !!}
                                    {!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-xs',
                                            'title' => 'Delete',
                                            'onclick'=>'return confirm("Confirm delete?")'
                                    ))!!}
                                    {!! Form::close() !!}
                                    @endcan
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

                                {!! Form::open(array('method' => 'get', 'url' => 'date_wise_expense','class'=>'form-horizontal')) !!}
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

                                <div class="form-group row {{ $errors->has('approval_type') ? ' has-error' : '' }}">
                                    <label class="col-md-3 control-label text-md-right">Approval Type : <span
                                                class="required"> * </span></label>
                                    <div class=" col-md-6 mt-radio-inline">
                                        <label class="mt-radio">
                                            {{ Form::radio('approval_type', 'Approved',true) }} Approved
                                            <span></span>
                                        </label>
                                        <label class="mt-radio">
                                            {{ Form::radio('approval_type', 'Submitted') }} Submitted
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
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>--}}
<script type="text/javascript" src="{{ asset('supporting/sweetalert/sweetalert2.min.js') }}"></script>

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
            columnDefs: [
                {targets: [0], className: 'text-center'},
//                {targets: [4], className: 'text-right'},
                {
                    targets: [3],
                    render: $.fn.dataTable.render.number(',', '.', 0, '')
                }
            ],

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {
                    extend: 'excel', title: '{{$header_title}}',
                    messageTop: '{{$header_title}}'
                },
                    {{--{extend: 'pdf', title: 'DVL Transaction Data',--}}
                    {{--messageTop: 'Commission Report of {{entryBy($partner_id).' '. $title_date_range}} ',--}}
                    {{--messageBottom: '{{\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}'--}}
                    {{--},--}}

                {
                    extend: 'pdfHtml5',

                    className: 'btn  btn-sm btn-table',
                    titleAttr: 'Export to Pdf',
                    text: '<span class="fa fa-file-pdf-o fa-lg"></span><i class="hidden-xs hidden-sm hidden-md"> Pdf</i>',
                    filename: '{{$header_title}}',
                    extension: '.pdf',
                    orientation: 'landscape',
//                    orientation: 'portrait',
                    title: "{{$header_title}}",
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
                            doc.content[1].table.body[i][3].alignment = 'right';
                            doc.content[1].table.body[i][4].alignment = 'left';
                            doc.content[1].table.body[i][5].alignment = 'left';
                            doc.content[1].table.body[i][6].alignment = 'left';
                            doc.content[1].table.body[i][7].alignment = 'left';
                            doc.content[1].table.body[i][8].alignment = 'left';
                            doc.content[1].table.body[i][9].alignment = 'left';
//                            doc.content[1].table.body[i][10].alignment = 'right';
//                            doc.content[1].table.body[i][11].alignment = 'center';
//                            doc.content[1].table.body[i][12].alignment = 'center';
                        }
                        doc.content[1].table.widths = ['5%', '10%', '15%', '5%', '25%', '5%', '10%', '10%', '10%', '5%'];
//                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.content.splice(0, 1);
                        var now = new Date();
                        var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear() + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
                        var logo = '';
                        {{--var header_title = '';--}}
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
                                        image: 'data:image/png;base64,logo'

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
                                        text: 'EIS'
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
                    footer: true,
                    messageTop: '{{$header_title}}',
                    messageBottom: '{{'Printed On: '.\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}',
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
                var j = 3;
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
//                    'Last 7 Days': [moment().subtract(6,'days'), moment()],
                    'Last 30 Days': [moment().subtract(29,'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1,'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
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
//                console.log("Callback has been called!");
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

<script type="text/javascript">

    function checkedPost(id) {
//        console.log(id);
        var id = id;
        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        })

        swalWithBootstrapButtons({
            title: 'Are you sure?',
            text: "You want to verify this Request!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Verify it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value
    )
        {
            document.getElementById('check-form' + id).submit();
            event.preventDefault();
        }
    else
        if (
            // Read more about handling dismissals
        result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                'Cancelled',
                'The user remain pending :)',
                'info'
            )
        }
    })
    }
</script>

<script type="text/javascript">

    function approvePost(id) {
        console.log(id);
        var id = id;
        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        })

        swalWithBootstrapButtons({
            title: 'Are you sure?',
            text: "You want to approve this Expense!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value
    )
        {
            document.getElementById('approval-form' + id).submit();
            event.preventDefault();
        }
    else
        if (
            // Read more about handling dismissals
        result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                'Cancelled',
                'The user remain pending :)',
                'info'
            )
        }
    })

    }

</script>

@endpush
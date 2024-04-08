@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee','active')
@section('employee_salary','active')
@section('title','Show Employee Salary')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee')}}" class="nav-link">Employee</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Employee Salary</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/sweetalert/sweetalert2.css') }}">

@endpush
@section('maincontent')

    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">Salary Details</a>
                </li>
            </ul>
        </div>
        <div class="card-body">

            <div class="tab-content">
                <div class="active tab-pane" id="custom-tabs-one-home">
                    <table class="table table-bordered table-striped" id="print_this0">
                        <tbody>
                        <tr>
                            <th>
                                ID
                            </th>
                            <td>
                                {{ $employee_salary->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction Code
                            </th>
                            <td>
                                {{ $employee_salary->transaction_code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Salary For
                            </th>
                            <td>
                                {{ $employee_salary->user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Date
                            </th>
                            <td>
                                {{ Carbon\Carbon::parse($employee_salary->created_at)->format('d-M-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Type
                            </th>
                            <td>
                                {{ $employee_salary->type }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Amount
                            </th>
                            <td>
                                {{ ($employee_salary->type=='Salary Payslip')?number_format($employee_salary->salary_amount,0):number_format($employee_salary->paidsalary_amount,0) }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Branch
                            </th>
                            <td>
                                {{ $employee_salary->branch->title }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Period
                            </th>
                            <td>
                                {{ date('F', mktime(0, 0, 0, $employee_salary->salary_month, 10)).' - '. $employee_salary->year}}
                            </td>
                        </tr>
                        @if($employee_salary->type!='Salary Payslip')
                        <tr>
                            <th>
                                Holiday & Weekend
                            </th>
                            <td>
                                {{ $employee_salary->holiday_weekend }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Leave
                            </th>
                            <td>
                                {{ $employee_salary->leave_day }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Absent
                            </th>
                            <td>
                                {{ $employee_salary->absent_day }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Working Days
                            </th>
                            <td>
                                {{ $employee_salary->working_day }}
                            </td>
                        </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                        class="fa fa-arrow-left"
                        aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
            <a type="button" id="pbutton0" class="btn btn-warning pull-right"><i
                        class="fa fa-print"> Print</i></a>
            @can('ExpenseDelete')
                {!! Form::open([
        'method'=>'DELETE',
        'url' => ['employee_salary', $employee_salary->id],
        'style' => 'display:inline'
    ]) !!}
                {!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs fa-pull-right',
                        'title' => 'Delete',
                        'onclick'=>'return confirm("Confirm delete?")'
                ))!!}
                {!! Form::close() !!}
            @endcan
            @can('ExpenseAccess')
                <a href="{{ url('employee_salary/' . $employee_salary->id . '/edit') }}"
                   class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span
                            class="far fa-edit"
                            aria-hidden="true"></span></a>
            @endcan
        </div>
    </div>
@endsection
@push('js')
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
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
//                { targets: [ 0,1,2,3,4, 5, 6, 7, 8, 9 ], className: 'dt-head text-center'  },
//                { targets: [0,1,2,3,4, 5,6,7 ], className: 'text-center' },
                {targets: [0], className: 'text-center'},
//                {targets: [4], className: 'text-right'},
            ],

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {
                    extend: 'excel', title: '{{ config('app.name', 'EIS') }}',
                    messageTop: ' Product Type   '
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
                    filename: 'Product Type ',
                    extension: '.pdf',
//                    orientation : 'landscape',
                    orientation: 'portrait',
                    title: "Product Type ",
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
//                            doc.content[1].table.body[i][3].alignment = 'left';
//                            doc.content[1].table.body[i][4].alignment = 'right';
//                            doc.content[1].table.body[i][5].alignment = 'right';
//                            doc.content[1].table.body[i][6].alignment = 'right';
//                            doc.content[1].table.body[i][7].alignment = 'left';
//                            doc.content[1].table.body[i][8].alignment = 'left';
                        }
                        doc.content[1].table.widths = ['10%', '50%', '40%'];
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
                                        text: 'Product Type ',
                                        fontSize: 10,
                                        margin: [10, 0]
                                    },
                                    {
                                        //image: logo,
                                        alignment: 'center',
                                        width: 20,
                                        height: 20,
                                        {{--image: 'data:image/png;base64,{{$settings->logo_base64}}'--}}

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
                    messageTop: 'Product Type  ',
                    messageBottom: '{{'Printed On: '.\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]

        });
    });

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
<script src="{!! asset('supporting/printthis.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
    $('#pbutton0').on('click', function () {
        $("#print_this0").printThis({
            debug: false,
            importCSS: true,
            importStyle: true,
            printContainer: true,
//            loadCSS: "../../../public/tf/global/plugins/bootstrap/css/bootstrap.min.css",
            pageTitle: "",
            removeInline: false,
            printDelay: 333,
            header: null,
            footer: null,
            base: false,
//            formValues: true,
            canvas: false,
//            doctypeString: "...",
            removeScripts: false,
            copyTagClasses: false
        });
    });
</script>


@endpush

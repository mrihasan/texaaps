@extends('layouts.al305_main')
@section('report_mo','menu-open')
@section('report_mo','active')
@section('report_expense'.(request()->segment(1)=='datewise_expense_details'?'_date':'_type'),'active')
@section('title','Expense Details Report')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('branch')}}" class="nav-link">Report</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Expense Details</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
@endpush
@section('maincontent')

    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">{{$header_title}}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table dataTables table-striped table-bordered table-hover">
                <thead>
                <tr style="background-color: #dff0d8">
                    <th>S.No</th>
                    <th>Date of Expense</th>
                    <th> Expense Name</th>
                    <th>Amount</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Submitted By</th>
                    <th>Approved By</th>
                    <th>Approved Date</th>
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
                </tr>
                </tfoot>
                <tbody>
                @foreach($expense as $key=>$section)
                    <tr>
                        <td>{{ $key+1 }}</td>

                        <td>{{ Carbon\Carbon::parse($section->expense_date)->format('d-M-Y') }}</td>
                        <td>{{ $section->expense_type->expense_name }}</td>
                        <td style="text-align:right">{{ $section->expense_amount }}</td>
                        <td>{{ $section->comments }}</td>
                        <td>{{ $section->status }}</td>
                        <td>{{ $section->user->name }}</td>
                        <td>
                            {{ ($section->approved_date==null)?'Not Yet Approved':$section->approvedBy->name }}
                        </td>
                        <td>{{ ($section->approved_date==null)?'Not Yet Approved':Carbon\Carbon::parse($section->approved_date)->format('d-M-Y') }}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>--}}

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
                    orientation : 'landscape',
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
//                            doc.content[1].table.body[i][9].alignment = 'left';
//                            doc.content[1].table.body[i][10].alignment = 'right';
//                            doc.content[1].table.body[i][11].alignment = 'center';
//                            doc.content[1].table.body[i][12].alignment = 'center';
                        }
                        doc.content[1].table.widths = ['5%','10%','15%','10%','25%','5%','10%','10%','10%'];
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
                        doc.styles.tableHeader.fontSize = 8;
                        doc.styles.tableFooter.fontSize = 8;
                        doc['header'] = (function () {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        italics: true,
                                        text: '{{$header_title}}',
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
                                        {{--text: '{{ config('app.name', 'EIS') }}'--}}
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

@endpush
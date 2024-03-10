@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee','active')
@section('manage_employee','active')
@section('title','Add Employee')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee')}}" class="nav-link">Employee</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage Employee</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
@endpush

@section('maincontent')
    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">Employee </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('employee/create') }}" class="nav-link">
                        Add Employee
                    </a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <table class="table dataTables table-striped table-bordered table-hover">
                <thead>
                <tr style="background-color: #dff0d8">
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Cell No</th>
                    <th>Designation</th>
                    <th>Religion</th>
                    <th>Branch</th>
                    <th>Salary</th>
                    <th>Bonus %</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                @foreach ($employees as $key => $employee)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{$employee->user->name}}</td>
                        <td>{{ $employee->user->cell_phone}}</td>
                        <td>{{ $employee->designation ?? '' }}</td>
                        <td>{{ $employee->religion ?? '' }}</td>
                        <td>{{ $employee->branch->title ?? '' }}</td>
                        <td>{{ $employee->salary_amount ?? '' }}</td>
                        <td>{{ $employee->bonus_amount ?? '' }}</td>
                        <td>{{($employee->last_working_day) ? 'Left on '. (Carbon\Carbon::parse(date('Y-m-d', strtotime($employee->last_working_day)))->format('d-M-Y')) : 'Active'}}</td>
                        <td>
{{--                            @can('user-show')--}}
                                <a href="{{ route('user.show',$employee->user_id) }}" class="btn btn-success btn-xs"
                                   title="User Profile View"><span class="far fa-user-circle" aria-hidden="true"></span></a>
                            {{--@endcan--}}
                            @can('EmployeeAccess')
                                {{--<a href="{{ url('employee/' . $employee->id . '/edit') }}" class="btn btn-info btn-xs"--}}
                                   {{--title="Edit"><span class="far fa-edit" aria-hidden="true"></span></a>--}}
                                <a href="{{ url('user/' . $employee->user->id . '/edit') }}" class="btn btn-info btn-xs"
                                   title="User Edit"><span class="far fa-edit" aria-hidden="true"></span></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>
            {{--</div>--}}
        </div>
    </div>
@endsection
@push('js')
{{--<script src="{{ asset('AdminLTE-3.0.5/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>--}}

<script>
    $(document).ready(function () {
        $('.dataTables').DataTable({
            aaSorting: [],
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
                            doc.content[1].table.body[i][3].alignment = 'left';
                            doc.content[1].table.body[i][4].alignment = 'left';
                            doc.content[1].table.body[i][5].alignment = 'left';
                            doc.content[1].table.body[i][6].alignment = 'right';
                            doc.content[1].table.body[i][7].alignment = 'right';
                            doc.content[1].table.body[i][8].alignment = 'left';
                            doc.content[1].table.body[i][9].alignment = 'left';
                        }
                        doc.content[1].table.widths = ['5%','20%','10%','10%','10%','15%','10%','10%','5%','5%'];
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
                                        text: 'org_name'
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
            ]
        });
    });
</script>
@endpush
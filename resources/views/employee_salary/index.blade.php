@extends('layouts.al305_main')
@section('employee_mo','menu-open')
@section('employee','active')
@section('employee_salary','active')
@section('title','Manage Employee Salary')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('employee')}}" class="nav-link">Employee</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage Employee Salary</a>
    </li>
@endsection
@push('css')
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
                       aria-selected="true">Employee Salary</a>
                </li>
                <li class="nav-item">
                    {{--<a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"--}}
                    {{--href="{{ url('company/create') }}" role="tab" aria-controls="custom-tabs-one-profile"--}}
                    {{--aria-selected="false">Add Company</a>--}}
                    @can('EmployeeAccess')
                        <a href="{{ url('employee_salary/create') }}" class="nav-link">
                        Pay Salary
                    </a>
                    @endcan

                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <table class="table dataTables table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>User Name</th>
                            <th>Period</th>
                            <th>Type</th>
                            <th>Holiday & Weekend</th>
                            <th>Leave</th>
                            <th>Absent</th>
                            <th>Working Days</th>
                            <th>Salary</th>
                            <th>Paid Amount</th>
                            <th>Date</th>
                            {{--<th>Action</th>--}}
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
                            <td></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            {{--<td></td>--}}
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($employee_salaries as $key=>$employee_salary)
                            <tr>
                                <td>{{ $key+1 }}</td>

                                <td>{{ $employee_salary->user->name }}</td>
                                <td>{{ date('F', mktime(0, 0, 0, $employee_salary->salary_month, 10)).' - '. $employee_salary->year}}</td>
                                <td>{{ $employee_salary->type}}</td>
                                <td>{{ $employee_salary->holiday_weekend }}</td>
                                <td>{{ $employee_salary->leave_day }}</td>
                                <td>{{ $employee_salary->absent_day }}</td>
                                <td>{{ $employee_salary->working_day }}</td>
                                <td style="text-align:right">{{ $employee_salary->salary_amount}}</td>
                                <td style="text-align:right">{{ $employee_salary->paidsalary_amount}}</td>
                                <td>{{ Carbon\Carbon::parse($employee_salary->created_at)->format('d-M-Y') }}</td>
                                {{--<td class="col-md-1">--}}
                                    {{--@can('employee-salary')--}}
                                    {{--<a href="{{ url('employee_salary/' . $employee_salary->id . '/edit') }}" class="btn btn-info btn-xs" title="Edit"><span class="far fa-edit" aria-hidden="true"></span></a>--}}

                                    {{--{!! Form::open([--}}
                                        {{--'method'=>'DELETE',--}}
                                        {{--'url' => ['employee_salary', $employee_salary->id],--}}
                                        {{--'style' => 'display:inline'--}}
                                    {{--]) !!}--}}
                                    {{--{!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" ></span>', array(--}}
                                            {{--'type' => 'submit',--}}
                                            {{--'class' => 'btn btn-danger btn-xs',--}}
                                            {{--'title' => 'Delete',--}}
                                            {{--'onclick'=>'return confirm("Confirm delete?")'--}}
                                    {{--))!!}--}}
                                    {{--{!! Form::close() !!}--}}
                                    {{--@endcan--}}
                                {{--</td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{--</div>--}}
        </div>
    </div>

@endsection
@push('js')
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>

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
                {extend: 'excel', title: ' Employee Salary'},
                    {{--{extend: 'pdf', title: ' {{$title_date_range}}'},--}}
                {
                    extend: 'pdfHtml5',
                    className:'btn  btn-sm btn-table',
                    titleAttr: 'Export to Pdf',
                    text: '<span class="fa fa-file-pdf-o fa-lg"></span><i class="hidden-xs hidden-sm hidden-md"> Pdf</i>',
                    filename: 'Employee Salary',
                    extension: '.pdf',
                    orientation : 'landscape',
                    title: "Employee Salary",
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
                            doc.content[1].table.body[i][0].alignment = 'left';
                            doc.content[1].table.body[i][1].alignment = 'left';
                            doc.content[1].table.body[i][2].alignment = 'left';
                            doc.content[1].table.body[i][3].alignment = 'right';
                            doc.content[1].table.body[i][4].alignment = 'right';
                            doc.content[1].table.body[i][5].alignment = 'right';
                            doc.content[1].table.body[i][6].alignment = 'right';
                            doc.content[1].table.body[i][7].alignment = 'right';
                            doc.content[1].table.body[i][8].alignment = 'right';
                            doc.content[1].table.body[i][9].alignment = 'right';
                            doc.content[1].table.body[i][10].alignment = 'right';
                        }
//                        doc.content[1].table.widths = [ '5%',  '25%', '10%', '10%', '10%', '10%', '10%', '10%', '10%'];
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.content.splice(0,1);
                        var now = new Date();
                        var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear()+' '+now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
                        var logo = '';
                        {{--var header_title = '{{$title_date_range}}';--}}
                            doc.pageMargins = [10,50,10,40];
                        doc.defaultStyle.fontSize = 7;

                        doc.defaultStyle.alignment = 'left';
                        doc.styles.tableHeader.alignment = 'left';
                        doc.styles.tableHeader.fontSize = 10;
                        doc.styles.tableFooter.fontSize = 10;
                        doc['header']=(function() {
                            return {
                                columns: [
                                    {
                                        //image: logo,
                                        alignment: 'center',
                                        width: 20,
                                        height: 20,
                                        image: 'data:image/png;base64,{{$settings->logo_base64}}'

                                    },
                                    {
                                        alignment: 'left',
                                        italics: true,
                                        text:  'Employee Salary',
                                        fontSize: 10,
                                        margin: [10,0]
                                    },
                                    {
                                        alignment: 'right',
                                        fontSize: 10,
                                        text: '{{$settings->org_name}}'
                                    }
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
                nb_cols = 10;
                var j = 8;
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


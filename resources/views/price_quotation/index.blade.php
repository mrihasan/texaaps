@extends('layouts.al305_main')
@section('supply_mo','menu-open')
@section('supply','active')
@section('manage_price_quotation','active')
@section('title',$title_date_range)
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Supply</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage Price Quotation</a>
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
                       aria-selected="true">{{$title_date_range}}</a>
                </li>
                {{--@can('AccountMgtAccess')--}}
                <li class="nav-item">
                    <a href="{{ url('pqCreate') }}" class="nav-link">
                        Add Price Quotation
                    </a>
                </li>
                {{--@endcan--}}

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
                            <th> Quotation Number</th>
                            <th> Date</th>
                            <th> Customer</th>
                            <th>Submitted By</th>
                            <th>Updated By</th>
                            <th> Amount</th>
                            <th>Actions</th>
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
                            <td></td>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($price_quotations as $key=>$data)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $data->ref_no }}</td>
                                <td>{{ Carbon\Carbon::parse($data->pq_date)->format('d-M-y') }}</td>
                                <td>{{ $data->user->name}}</td>

                                <td>{{ $data->entryBy->name}}<br>
                                    <small>{{ Carbon\Carbon::parse($data->created_at)->format('d-m-y, h:iA') }}</small>
                                </td>
                                <td style="color: {{($data->created_at!=$data->updated_at)?'red':''}}"> {{ $data->updatedBy->name}}<br>
                                    <small>{{ Carbon\Carbon::parse($data->created_at)->format('d-m-y, h:iA') }}</small>
                                </td>
                                <td>{{ $data->invoice_total }}</td>
                                <td>
                                    {{--@can('AccountMgtAccess')--}}
                                    <a href="{{ url('price_quotation/'.$data->id) }}" class="btn btn-success btn-xs"
                                       title="View "><span class="far fa-eye" aria-hidden="true"></span></a>
                                    <a href="{{ url('price_quotation/' . $data->id . '/edit') }}"
                                       class="btn btn-info btn-xs" title="Edit"><span class="far fa-edit"
                                                                                      aria-hidden="true"></span></a>

                                    {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['price_quotation', $data->id],
                                    'style' => 'display:inline'
                                    ]) !!}
                                    {!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete',
                                    'onclick'=>'return confirm("Confirm delete?")'
                                    ))!!}
                                    {!! Form::close() !!}
                                    {{--@endcan--}}
                                </td>
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
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{ asset('supporting/vfs_fonts.js')}}"></script>

<script>
    pdfMake.fonts = {
        Roboto: {
            normal: 'Roboto-Regular.ttf',
            bold: 'Roboto-Medium.ttf',
            italics: 'Roboto-Italic.ttf',
            bolditalics: 'Roboto-MediumItalic.ttf'
        },
        nikosh: {
            normal: "NikoshBAN.ttf",
            bold: "NikoshBAN.ttf",
            italics: "NikoshBAN.ttf",
            bolditalics: "NikoshBAN.ttf"
        }
    };

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
                {targets: [6], className: 'text-right'},
                {targets: [7], className: 'text-center'},
                {
                    targets: [6],
                    render: $.fn.dataTable.render.number(',', '.', 0, '')
                }
            ],

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {
                    extend: 'excel', title: '{{ config('app.name', 'EIS') }}',
                    messageTop: '{{$title_date_range}}'
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
                    filename: '{{$title_date_range}}',
                    extension: '.pdf',
//                    orientation : 'landscape',
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
                            doc.content[1].table.body[i][4].alignment = 'left';
                            doc.content[1].table.body[i][5].alignment = 'right';
                            doc.content[1].table.body[i][6].alignment = 'center';
//                            doc.content[1].table.body[i][7].alignment = 'left';
//                            doc.content[1].table.body[i][8].alignment = 'left';
                        }
                        doc.content[1].table.widths = ['10%', '15%', '20%', '15%', '15%', '15%', '10%'];
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
                                        text: '{{$title_date_range}}',
                                        fontSize: 10,
                                        margin: [10, 0]
                                    },
                                    {
                                        //image: logo,
//                                        alignment: 'center',
//                                        width: 20,
//                                        height: 20,
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
                {{--{--}}
                    {{--extend: 'print',--}}
                    {{--footer: true,--}}
                    {{--messageTop: '{{$title_date_range}}',--}}
                    {{--messageBottom: '{{'Printed On: '.\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}',--}}
                    {{--customize: function (win) {--}}
                        {{--$(win.document.body).addClass('white-bg');--}}
                        {{--$(win.document.body).css('font-size', '10px');--}}
                        {{--$(win.document.body).find('table')--}}
                            {{--.addClass('compact')--}}
                            {{--.css('font-size', 'inherit');--}}
                    {{--}--}}
                {{--}--}}
                {
                    extend: 'print',
                    footer: true,
                    messageTop: 'User: {{ auth()->user()->name }}',  // Custom user name at the top of the printed page
                    messageBottom: '{{'Printed On: '.\Carbon\Carbon::now()->format('D, d-M-Y, h:ia')}}',  // Custom date at the bottom
                    customize: function (win) {
                        // Adding a custom class to the body for styling
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');  // General font size for the printed document

                        // Style adjustments for the printed table
                        $(win.document.body).find('table')
                            .addClass('compact')  // Add your custom table class
                            .css({
                                'font-size': 'inherit',  // Inherit font size for table elements
                                'width': '100%',  // Set table width to 100%
                                'border-collapse': 'collapse',  // Ensure table borders are collapsed
                                'border': '1px solid black'  // Add border to table for better readability
                            });

                        // Add custom header content (e.g., logo, title, date)
                        $(win.document.body).prepend(
                            `<div style="text-align: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Your Company Name</h2>
                <p style="margin: 0;">User: {{ auth()->user()->name }}</p>
                <p style="margin: 0;">Date: ${new Date().toLocaleDateString()}</p>
            </div>`
                        );

                        // Add custom footer content (e.g., page number, signature line)
                        $(win.document.body).append(
                            `<div style="text-align: center; margin-top: 20px;">
                <p>Printed by: {{ auth()->user()->name }} | Printed On: ${new Date().toLocaleString()}</p>
                <p>Page <span class="page-num"></span> of <span class="total-pages"></span></p>
            </div>`
                        );

                        // Add page numbers in footer (for example, using JavaScript to detect pages)
                        var totalPages = Math.ceil($(win.document.body).height() / 1000);  // Example logic for pages
                        $(win.document.body).find('.page-num').text(1);
                        $(win.document.body).find('.total-pages').text(totalPages);

                        // Additional style overrides for print (optional)
                        $(win.document.body).find('h2').css('font-size', '16px');  // Custom font size for headers
                        $(win.document.body).find('p').css('font-size', '12px');   // Custom font size for paragraphs
                    }
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
                nb_cols = api.columns().nodes().length -1;
//                nb_cols = 8;
                var j = 6;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return (Number(a) + Number(b)).toFixed(0);
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


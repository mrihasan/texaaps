@extends('layouts.al305_main')
@section('product_mo','menu-open')
@section('product','active')
@section('manage_product','active')
@section('title',$product->title)
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('product')}}" class="nav-link">{{ __('all_settings.Product') }}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Product</a>
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
                       aria-selected="true">Details of {{$product->title}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                       href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                       aria-selected="false">Inventory Details</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" >
                <div class="active tab-pane" id="custom-tabs-one-home">
                    <table class="table table-bordered table-striped" id="print_this0">
                        <tbody>
                        <tr>
                            <th>
                                ID
                            </th>
                            <td>
                                {{ $product->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Title
                            </th>
                            <td>
                                {{ $product->title }}
                            </td>
                        </tr>
                        <tr>
                            <th>Type/Category</th>
                            <td>{{ $product->product_type->title }}</td>
                        </tr>
                        {{--<tr>--}}
                            {{--<th>Company</th>--}}
                            {{--<td>{{ $product->company_name->title }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th>Brand</th>--}}
                            {{--<td>{{ $product->brand->title }}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <th>Unit</th>
                            <td>{{ $product->unit->title }}</td>
                        </tr>
                        {{--<tr>--}}
                            {{--<th>Unit Buy Price</th>--}}
                            {{--<td>{{ $product->unitbuy_price }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th>Unit Sell Price</th>--}}
                            {{--<td>{{ $product->unitsell_price }}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <th>Low Stock Alert</th>
                            <td>{{ $product->low_stock }}</td>
                        </tr>
                        <tr>
                            <th>
                                Status
                            </th>
                            <td>
                                {{ $product->status }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total Purchase
                            </th>
                            <td>
                                {{ static_product_stock($product->id)['purchase'] }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Total Sales
                            </th>
                            <td>
                                {{ static_product_stock($product->id)['sales'] }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Current Stock
                            </th>
                            <td>
                                {{ static_product_stock($product->id)['stock'] }}
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="custom-tabs-one-profile">
                    <table class="table dataTables table-striped table-bordered table-hover tab_4_table" >
                        <thead>
                        <tr>
                            <th>{{ __('all_settings.Transaction') }}<br/> Code</th>
                            <th>{{ __('all_settings.Transaction') }}<br/> Date</th>
                            <th>{{ __('all_settings.Transaction') }}<br/> Type</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Unit</th>
                            <th>qty</th>
                            <th>MRP<br/> Unit</th>
                            {{--<th>Discount<br/>Total</th>--}}
                            <th>Line<br/> Total</th>
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
                            <td style="text-align: right"></td>
                            <td style="text-align: right"></td>
                            <td style="text-align: right"></td>
                            <td style="text-align: right"></td>
                        </tr>
                        </tfoot>
                        @foreach($product->inventory_details as $stu)
                            <tr>
                                <td>
                                    <a href="{{ url('invoice/' . $stu->invoice->id ) }}" class="btn btn-success btn-xs"
                                       title="Show"><span class="far fa-eye" aria-hidden="true"></span></a> {{$stu->invoice->sl_no}}
                                </td>
                                <td>{{Carbon\Carbon::parse($stu->transaction_date)->format('d-M-Y')}}</td>
                                <td>{{$stu->transaction_type}}</td>
                                <td>{{$stu->product->title??''}}</td>
                                <td>{{$stu->brand->title??''}}</td>
                                <td>{{$stu->model??''}}</td>
                                <td style="text-align: right">{{$stu->unit_name??''}}</td>
                                <td style="text-align: right">{{$stu->qty??''}}</td>
                                <td style="text-align: right">{{($stu->transaction_type=='Sales')?$stu->usell_price:$stu->ubuy_price}}</td>
                                <td style="text-align: right">{{$stu->line_total??''}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                            class="fa fa-arrow-left"
                            aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                <a type="button" id="pbutton0" class="btn btn-warning pull-right"><i
                            class="fa fa-print"> Print</i></a>
                @can('ProductMgtDelete')
                {!! Form::open([
        'method'=>'DELETE',
        'url' => ['product', $product->id],
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
                @can('ProductMgtAccess')
                <a href="{{ url('product/' . $product->id . '/edit') }}"
                   class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span
                            class="far fa-edit"
                            aria-hidden="true"></span></a>
                    @endcan

            </div>
        </div>
    </div>
@endsection
@push('js')
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{!! asset('supporting/printthis.js')!!}" type="text/javascript"></script>
<script type="text/javascript">
    //    console.log(id);
    $('#pbutton0').on('click', function () {
//    $('.printt').on('click', function(){
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
                    messageTop: '{{$product->title}} '
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
                    filename: '{{$product->title}}',
                    extension: '.pdf',
                    orientation : 'landscape',
//                    orientation: 'portrait',
                    title: "{{$product->title}}",
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
                            doc.content[1].table.body[i][8].alignment = 'right';
                            doc.content[1].table.body[i][9].alignment = 'right';
                        }
                        doc.content[1].table.widths = ['10%', '10%', '7%','18%', '10%', '15%','5%', '5%', '10%', '10%'];
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
                                        text: '{{$product->title}}',
                                        fontSize: 10,
                                        margin: [10, 0]
                                    },
//                                    {
                                        //image: logo,
//                                        alignment: 'center',
//                                        width: 20,
//                                        height: 20,
                                        {{--image: 'data:image/png;base64,{{$settings->logo_base64}}'--}}
//                                    },

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
                    messageTop: '{{$product->title}}',
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
                nb_cols = api.columns().nodes().length ;
//                nb_cols = 8;
                var j = 7;
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

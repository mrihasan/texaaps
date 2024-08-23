@extends('layouts.al305_main')
@section('paymentmgt_mo','menu-open')
@section('paymentmgt','active')
@section($transaction_type,'active')
@section('title','Show Account Ledger')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Account Ledger</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/sweetalert/sweetalert2.css') }}">
<style>
    @media print {
        .pagebreak {
            page-break-before: always;
        }

        body {
            background: none;
            -ms-zoom: 1.665;
        }

        div.portrait, div.landscape {
            margin: 0;
            padding: 0;
            border: none;
            background: none;
        }

        div.landscape {
            transform: rotate(270deg) translate(-276mm, 0);
            transform-origin: 0 0;
        }

    }

    table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid gray;
        padding: 4px;
    }

    table.center {
        margin-left: auto;
        margin-right: auto;
    }

    @media print {
        body {
            margin: 0;
            padding: 0;
            size: A4 portrait;
        }

        /* Adjust margins to fit content within A4 size */
        @page {
            margin: 20mm; /* Adjust as needed */
        }
    }
</style>

@endpush
@section('maincontent')

    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">Ledger Details</a>
                </li>
            </ul>
        </div>
        <div class="card-body">

            <div class="tab-content">
                <div class="active tab-pane" id="custom-tabs-one-home">
                    <table class="table table-striped" id="print_this0" width="90%" style="border: none">
                        <tbody>
                        <tr style="border: none">
                            <td style="border: none" colspan="3"><img
                                        src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}"
                                        width="25%"
                                        class="" style="border: none"></td>
                        </tr>
                        <tr style="border: none">
                            <td style="border: none" colspan="3">
                                <h3 style="text-align: center">
                                @if($ledger->transaction_type_id==4)
                                    Payment Voucher
                                    @elseif($ledger->transaction_type_id==3)
                                    Receipt Voucher
                                    @else
                                @endif
                                </h3>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                ID
                            </th>
                            <td colspan="2">
                                {{ $ledger->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction Sl No
                            </th>
                            <td colspan="2">
                                {{ $ledger->sl_no??'' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Tracking ID
                            </th>
                            <td  colspan="2">
                                {{ $ledger->transaction_code }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction Date
                            </th>
                            <td colspan="2">
                                {{ Carbon\Carbon::parse($ledger->transaction_date)->format('d-M-Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction Type
                            </th>
                            <td colspan="2">
                                {{ $ledger->transaction_type->title}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction Amount
                            </th>
                            <td colspan="2">
                                {{ number_format($ledger->amount,0) }}
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Linked Bill
                            </th>
                            <td colspan="2">
                                @if($ledger->invoice_id)
                                    {{ $ledger->invoice->sl_no ?? 'null'}}
                                    <a href="{{ url('invoice/' . $ledger->invoice_id ) }}" class="btn btn-outline-info btn-xs"
                                       title="Show Invoice"><span class="far fa-eye" aria-hidden="true"></span></a>
                                @else N/A
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th>

                                @if($ledger->transaction_type_id==4)
                                    Supplier
                                @elseif($ledger->transaction_type_id==3)
                                    Customer
                                @else
                                @endif

                            </th>
                            <td colspan="2">
                                @if($ledger->user->profile->company_name_id!=null)
                                    <strong>{{$ledger->user->profile->company_name->title}}</strong>
                                    <br/>
                                    {{$ledger->user->profile->company_name->contact_no ?? ''}}<br/>
                                    {{$ledger->user->profile->company_name->address ?? ''}}<br/>
                                    {{$ledger->user->profile->company_name->address2 ?? ''}}<br/>
                                @else
                                    <strong>{{$ledger->user->name}}</strong>
                                    <br/>
                                    {{$ledger->user->profile->mobile}}
                                    <br/>{{$ledger->user->profile->address}}
                                    <br/>{{$ledger->user->profile->address2}}<br/>
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th>
                                Branch
                            </th>
                            <td colspan="2">
                                {{ $ledger->branch->title }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Account
                            </th>
                            <td colspan="2">
                                {{ $bank_ledger->bank_account->account_name}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Transaction Method
                            </th>
                            <td colspan="2">
                                {{ $bank_ledger->transaction_method->title}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Ref No
                            </th>
                            <td colspan="2">
                                {{ $bank_ledger->ref_no}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Ref Date
                            </th>
                            <td colspan="2">
                                {{ Carbon\Carbon::parse($bank_ledger->ref_date)->format('d-M-Y')}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Particulars
                            </th>
                            <td colspan="2">
                                {{ $ledger->comments}}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Approval Status
                            </th>
                            <td colspan="2">
                                {{ $ledger->approve_status }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Created By
                            </th>
                            <td colspan="2">
                                {{ entryBy($ledger->entry_by) }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Created at
                            </th>
                            <td colspan="2">
                                {{ Carbon\Carbon::parse($ledger->created_at)->format('d-M-Y H:i:s') }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Checked By
                            </th>
                            <td colspan="2">
                                {{ entryBy($ledger->checked_by) }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Checked at
                            </th>
                            <td colspan="2">
                                {{($ledger->checked_by)? Carbon\Carbon::parse($ledger->checked_date)->format('d-M-Y H:i:s'):'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Approved By
                            </th>
                            <td colspan="2">
                                {{ entryBy($ledger->approved_by) }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Approved at
                            </th>
                            <td colspan="2">
                                {{($ledger->approved_by)? Carbon\Carbon::parse($ledger->approved_date)->format('d-M-Y H:i:s'):'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Updated By
                            </th>
                            <td colspan="2">
                                {{ entryBy($ledger->updated_by) }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Updated at
                            </th>
                            <td colspan="2">
                                {{($ledger->updated_by)? Carbon\Carbon::parse($ledger->updated_at)->format('d-M-Y H:i:s'):'N/A' }}
                            </td>
                        </tr>
                        <tr style="border: none">
                            <td style="text-align:left; border: none" width="35%" >
                                Prepared by<br/><br/>
                                @if($ledger->entryby->employee && ($ledger->entryby->imageprofile->sign!='default_sign'||$ledger->entryby->imageprofile->sign!=null))
                                    <img src="{!! asset( 'storage/sign/'. $ledger->entryby->imageprofile->sign. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @else
                                    <img src="{!! asset( 'storage/sign/blank_sign.png'. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @endif

                                <address>
                                    ______________________<br/>
                                    {{$ledger->entryby->name}}<br/>
                                    {{($ledger->entryby->employee)?$ledger->entryby->employee->designation:'N/A'}}
                                    <br/>
                                    {{setting_info()['org_name']}}
                                </address>
                            </td>

                            <td style="text-align:center; border: none" width="30%">
                                Checked by<br/><br/>
                                @if( $ledger->checked_by!=null && $ledger->checkedBy->employee && ($ledger->checkedBy->imageprofile->sign!='default_sign'||$ledger->checkedBy->imageprofile->sign!=null))
                                    <img src="{!! asset( 'storage/sign/'. $ledger->checkedBy->employee->user->imageprofile->sign. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @else
                                    <img src="{!! asset( 'storage/sign/blank_sign.png'. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @endif
                                <address>
                                    ______________________<br/>
                                    {{($ledger->checked_by)?$ledger->checkedBy->name:'Not Yet Checked'}}
                                    <br/>
                                    {{($ledger->checked_by && $ledger->checkedBy->employee)?$ledger->checkedBy->employee->designation:'N/A'}}
                                    <br/>
                                    {{($ledger->checked_by)?setting_info()['org_name']:''}}
                                </address>
                            </td>
                            <td style="text-align:right; border: none" width="35%">
                                Approved By<br/><br/>
                                @if($ledger->approved_by!=null && $ledger->approvedBy->employee && ($ledger->approvedBy->imageprofile->sign!='default_sign'||$ledger->approvedBy->imageprofile->sign!=null))
                                    <img src="{!! asset( 'storage/sign/'. $ledger->approvedBy->imageprofile->sign. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @else
                                    <img src="{!! asset( 'storage/sign/blank_sign.png'. '?'. 'time='. time()) !!}"
                                         class="img-fluid" alt="Sign Image">
                                @endif

                                <address>
                                    ______________________<br/>
                                    {{($ledger->approved_by)?$ledger->approvedBy->name:'Not Yet Approved'}}
                                    <br/>
                                    {{($ledger->approved_by && $ledger->approvedBy->employee)?$ledger->approvedBy->employee->designation:'N/A'}}
                                    <br/>
                                    {{($ledger->approved_by)?setting_info()['org_name']:''}}
                                </address>
                            </td>

                        </tr>

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

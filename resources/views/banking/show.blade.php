@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('manage_account','active')
@section('title','Manage Account')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Account</a>
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
                       aria-selected="true">Account Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                       href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                       aria-selected="false">Statement</a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <div class="active tab-pane" id="custom-tabs-one-home">
                    <table class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <th>
                                ID
                            </th>
                            <td>
                                {{ $bank_account->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Account Name
                            </th>
                            <td>
                                {{ $bank_account->account_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>Bank Name</th>
                            <td>{{ $bank_account->bank_name }}</td>
                        </tr>
                        <tr>
                            <th>Account Number</th>
                            <td>{{ $bank_account->account_no}}</td>
                        </tr>
                        <tr>
                            <th>Account Type</th>
                            <td>{{ $bank_account->account_type}}</td>
                        </tr>
                        <tr>
                            <th>Details</th>
                            <td>{{ $bank_account->details}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="tab-pane" id="custom-tabs-one-profile">
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
            </div>
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                            class="fa fa-arrow-left"
                            aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                {{--@can('AccountMgtDelete')--}}
                {{--{!! Form::open([--}}
        {{--'method'=>'DELETE',--}}
        {{--'url' => ['bank_account', $bank_account->id],--}}
        {{--'style' => 'display:inline'--}}
    {{--]) !!}--}}
                {{--{!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(--}}
                        {{--'type' => 'submit',--}}
                        {{--'class' => 'btn btn-danger btn-xs fa-pull-right',--}}
                        {{--'title' => 'Delete',--}}
                        {{--'onclick'=>'return confirm("Confirm delete?")'--}}
                {{--))!!}--}}
                {{--{!! Form::close() !!}--}}
                {{--@endcan--}}
                @can('AccountMgtAccess')
                <a href="{{ url('bank_account/' . $bank_account->id . '/edit') }}"
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
//                    orientation : 'landscape',
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
                            doc.content[1].table.body[i][6].alignment = 'center';
                        }
                        doc.content[1].table.widths = ['10%','15%','25%','10%','15%','20%','5%'];
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
                nb_cols = api.columns().nodes().length -1;
//                nb_cols = 8;
                var j = 6;
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

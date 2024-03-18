@extends('layouts.al305_main')
@section('supply_mo','menu-open')
@section('supply','active')
@section('manage_sales','active')
@section('title','Manage Sales')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('salesTransaction')}}" class="nav-link">{{ __('all_settings.Sales') }}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{ __('all_settings.Manage Sales') }}</a>
    </li>
@endsection
@push('css')
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

        .noprint_123 {
            display: none;
        }

        .pad_footer {
            display: block; /* Show header and footer when printing */
            position: fixed;
            width: 100%;
            background-color: #f0f0f0; /* Change background color as needed */
            text-align: left;
            padding: 0;
        }

        .pad_footer {
            bottom: 0;
        }

        .pad_content {
            margin-top: 120px; /* Adjust according to your header and footer heights */
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
</style>

@endpush
@section('maincontent')
    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">Bill </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                       href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                       aria-selected="false">Challan</a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <div class="portlet-body form portrait" id="print_this0">
                        {{--<div class="pad_header">--}}
                        {{--<img src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}" alt="Header Image" style="max-width: 100%; max-height: 100%;">--}}
                        {{--</div>--}}
                        <div class="content">
                            <table class="center" width="90%" style="border: none">

                                <tbody>
                                <tr style="border: none">
                                    <td style="border: none"><img
                                                src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}"
                                                class="img-fluid" style="border: none"></td>
                                </tr>
                                <tr style="border: none">
                                    <td style="border: none" class="company-name">
                                        <strong>{{'Reference: '.$invoice->reference}}</strong></td>
                                    <td style="border: none; text-align: right" class="company-name">
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <h4 style="text-align: center;margin-top: 0px;margin-bottom: 0px; text-decoration: underline"
                                            class="company-name">
                                            <strong>INVOICE/BILL</strong></h4>
                                    </td>
                                    <td style="border: none; text-align: right" class="company-name">
                                        <strong>{{' Date: '.Carbon\Carbon::parse($invoice->transaction_date)->format('d-M-Y').','}}</strong><br/>
                                        <strong>
                                            {{'Bill No: '.$invoice->sl_no}}
                                            <br/>
                                            <small>{{'Tracking No: '.$invoice->transaction_code}}</small>
                                            <br/>
                                            {{' By: '.($invoice->entryBy->name)}}
                                            <br/>
                                        </strong>

                                    </td>
                                </tr>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">

                                        <h6 style="text-align: left;margin-top: 0px;margin-bottom: 0px;"
                                            class="company-name">
                                            <strong>To</strong></h6>
                                        @if($related_customer!=null)
                                            <strong>{{$related_customer->name}}</strong><br/>
                                            {{$related_customer->mobile}}<br/>
                                            {{$related_customer->address}}<br/>
                                        @elseif($invoice->user->profile->company_name_id!=null)
                                            <strong>{{$invoice->user->profile->company_name->title}}</strong>
                                            <br/>
                                            {{$invoice->user->profile->company_name->contact_no ?? ''}}<br/>
                                            {{$invoice->user->profile->company_name->contact_no2 ?? ''}}<br/>
                                            {{$invoice->user->profile->company_name->address ?? ''}}<br/>
                                            {{$invoice->user->profile->company_name->address2 ?? ''}}<br/>
                                        @else
                                            <strong>{{$invoice->user->name}}</strong>
                                            <br/>
                                            {{$invoice->user->profile->mobile}}
                                            <br/>{{$invoice->user->profile->address}}
                                            <br/>{{$invoice->user->profile->address2}}<br/>
                                        @endif
                                    </td>

                                    <td style="text-align:center;border: none " width="30%">

                                    </td>
                                    <td style="text-align:center; border: none" width="35%">
                                        <div class="company-address"
                                             style="text-align: right; color: blue; font-size:12px">
                                        </div>
                                    </td>

                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">

                                    </td>

                                    <td style="text-align:center; border: none" width="30%">
                                    </td>
                                    <td style="text-align:right; border: none" width="35%">
                                    </td>

                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" width="95%" colspan="3">
                                        <p style="text-align:justify; white-space: pre-wrap">{{ $invoice->notes }}</p>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                            <table class="table-hover center" width="90%" style="table-layout: fixed; ">
                                <thead>
                                <tr>
                                    <th style="text-align:center" width="5%">Sl</th>
                                    <th style="text-align:center" width="50%">Details</th>
                                    <th style="text-align:center" width="10%">Qty</th>
                                    <th style="text-align:center" width="10%">Unit</th>
                                    <th style="text-align:center" width="10%">MRP Price</th>
                                    {{--<th style="text-align:center" width="4%">Disc.<br/>(%)</th>--}}
                                    {{--<th style="text-align:center" width="7%">Disc.<br/>Amount</th>--}}
                                    {{--<th style="text-align:center" width="5%">Disc.<br/>MRP</th>--}}
                                    <th style="text-align:center" width="15%">MRP Total</th>
                                    {{--<th style="text-align:center" width="6%">Disc.<br/>Total</th>--}}
                                    {{--<th style="text-align:center" width="7%">Total</th>--}}
                                </tr>
                                </thead>
                                <tbody style="border: none">
                                @foreach($transactionDetails as $key=>$details)
                                    <tr style="border-bottom-style: hidden">
                                        <td>{{ $key+1 }}</td>

                                        <td>
                                            {{ $details->product_title }}
                                        </td>
                                        <td style="text-align:center">{{ $details->qty}} </td>
                                        <td style="text-align:center">{{ $details->unit_name}}</td>
                                        <td style="text-align:right">{{ $details->usell_price}} </td>
                                        {{--<td style="text-align:right">{{ $details->discountPercentage}} </td>--}}
                                        {{--<td style="text-align:right">{{ $details->discountUnit}} </td>--}}
                                        {{--<td style="text-align:right">{{ $details->discountedMrp}} </td>--}}
                                        <td style="text-align:right">{{ $details->line_total}} </td>
                                        {{--<td style="text-align:right">{{ $details->discountTotal}} </td>--}}
                                        {{--<td style="text-align:right">{{ $details->discountedTotalMrp}} </td>--}}
                                    </tr>
                                @endforeach
                                <tr></tr>
                                <tr>
                                    <td colspan="3" style="text-align:center"></td>
                                    <td colspan="2" style="text-align:right">Sub Total :</td>
                                    <td style="text-align:right">{{$invoice->product_total}}</td>

                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:center"><strong>Ledger Balance</strong></td>
                                    {{--<td colspan="1" style="text-align:center" ></td>--}}
                                    {{--<td colspan="1" style="text-align:center"></td>--}}
                                    {{--<td colspan="1" style="text-align:center">All</td>--}}
                                    <td colspan="2" style="text-align:right">(+)Vat/Tax :</td>
                                    <td style="text-align:right">{{$invoice->vat}}</td>

                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right">
                                        <strong>{{'From '.\Carbon\Carbon::parse($mindate_ledger)->format('d-M-Y').' to '.\Carbon\Carbon::parse($before1day_invoice)->format('d-M-Y')}} </strong>
                                    </td>
                                    <td colspan="1" style="text-align:right">{{$ledger['balance_before1day']}}</td>
                                    <td colspan="2" style="text-align:right">(-)Discount :</td>
                                    <td style="text-align:right">{{$invoice->discount}}</td>

                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right">Today
                                        <strong>( {{\Carbon\Carbon::parse($invoice->transaction_date)->format('d-M-Y')}}
                                            )</strong></td>
                                    <td colspan="1" style="text-align:right">{{$ledger['balance_today']}}</td>
                                    <td colspan="2" style="text-align:right">Net Amount:</td>
                                    <td style="text-align:right">{{$invoice->total_amount}}</td>

                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right">
                                        <strong> {{'From '.\Carbon\Carbon::parse($mindate_ledger)->format('d-M-Y').' to '.\Carbon\Carbon::parse($invoice->transaction_date)->format('d-M-Y')}} </strong>
                                    </td>
                                    <td colspan="1"
                                        style="text-align:right">{{$ledger['balance_before1day']+$ledger['balance_today']}}</td>
                                    <td colspan="2" style="text-align:right">Less Amount:</td>
                                    <td style="text-align:right">{{$invoice->less_amount}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:left"><strong>Last Payment Info : </strong>
                                        {{($ledger['lastPayment'])?('Date: '. \Carbon\Carbon::parse($ledger['lastPayment']->transaction_date)->format('d-M-Y')
                                .', Payment Method : '. $ledger['lastPayment']->transaction_method->title.', Amount ৳ : '. $ledger['lastPayment']->amount):'No transaction'}}
                                    </td>
                                    <td colspan="2" style="text-align:right">Invoice Total :</td>
                                    <td style="text-align:right">{{round($invoice->invoice_total)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align:left"><strong>In Word (Invoice Total): </strong>
                                        {{numberToWord($invoice->invoice_total).' Taka Only'}}
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <table class="center" width="90%" style="table-layout: fixed; border: none">
                                <tr style="border: none">
                                    {{--<td style="border: none"></td>--}}
                                    <td style="border: none" colspan="5">
                                        <br/>
                                        Thank you.
                                        <br/>
                                        <br/>

                                        Best regards,
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        Mob: +880176-5450149.<br/>
                                        E-mail: noman@texaaps.com
                                    </td>
                                    {{--<td colspan="4" style="border: none"></td>--}}
                                </tr>
                                <tr style="border: none">
                                    <td colspan="6" style="border: none">
                                        <div class="pad_footer">
                                            <p>
                                                <img src="{!! asset( 'storage/images/pad_bottom.jpg'. '?'. 'time='. time()) !!}"
                                                     class="img-fluid" style="border: none" width="75%"></p>
                                            <div class="row">
                                                <p class="col-md-6">
                                                    <small>Software By : www.eidyict.com 01716-383038</small>
                                                </p>
                                                <p class="col-md-6" style="text-align: right">
                                                    <small>Print
                                                        Time:{{\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}</small>
                                                </p>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"
                                    aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                        <a type="button" id="pbutton0" class="btn btn-warning pull-right"><i
                                    class="fa fa-print"> Print</i></a>
                        @can('SupplyDelete')
                            {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['invoice', $invoice->id],
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
                        @can('SupplyAccess')
                            <a href="{{ url('invoice/' . $invoice->id . '/edit') }}"
                               class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span
                                        class="far fa-edit"
                                        aria-hidden="true"></span></a>
                        @endcan
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                     aria-labelledby="custom-tabs-one-profile-tab">
                    <div class="portlet-body form portrait" id="print_this1">
                        <div class="content">
                            <table class="center" width="90%" style="border: none">

                                <tbody>
                                <tr style="border: none">
                                    <td style="border: none"><img
                                                src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}"
                                                class="img-fluid" style="border: none"></td>
                                </tr>
                                <tr style="border: none">
                                    <td style="border: none" class="company-name">
                                        <strong>{{'Reference: '.$invoice->reference}}</strong></td>
                                    <td style="border: none; text-align: right" class="company-name">
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <h4 style="text-align: center;margin-top: 0px;margin-bottom: 0px; text-decoration: underline"
                                            class="company-name">
                                            <strong>Challan</strong></h4>
                                    </td>
                                    <td style="border: none; text-align: right" class="company-name">
                                        <strong>{{' Date: '.Carbon\Carbon::parse($invoice->transaction_date)->format('d-M-Y').','}}</strong><br/>
                                        <strong>
                                            {{'Bill No: '.$invoice->transaction_code}}
                                            <br/>
                                            {{' By: '.($invoice->entryBy->name)}}
                                            <br/>
                                        </strong>

                                    </td>
                                </tr>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">

                                        <h6 style="text-align: left;margin-top: 0px;margin-bottom: 0px;"
                                            class="company-name">
                                            <strong>To</strong></h6>
                                        @if($related_customer!=null)
                                            <strong>{{$related_customer->name}}</strong><br/>
                                            {{$related_customer->mobile}}<br/>
                                            {{$related_customer->address}}<br/>
                                        @elseif($invoice->user->profile->company_name_id!=null)
                                            <strong>{{$invoice->user->profile->company_name->title}}</strong>
                                            <br/>
                                            {{$invoice->user->profile->company_name->contact_no ?? ''}}<br/>
                                            {{$invoice->user->profile->company_name->contact_no2 ?? ''}}<br/>
                                            {{$invoice->user->profile->company_name->address ?? ''}}<br/>
                                            {{$invoice->user->profile->company_name->address2 ?? ''}}<br/>
                                        @else
                                            <strong>{{$invoice->user->name}}</strong>
                                            <br/>
                                            {{$invoice->user->profile->mobile}}
                                            <br/>{{$invoice->user->profile->address}}
                                            <br/>{{$invoice->user->profile->address2}}<br/>
                                        @endif
                                    </td>

                                    <td style="text-align:center;border: none " width="30%">

                                    </td>
                                    <td style="text-align:center; border: none" width="35%">
                                        <div class="company-address"
                                             style="text-align: right; color: blue; font-size:12px">
                                        </div>
                                    </td>

                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">

                                    </td>

                                    <td style="text-align:center; border: none" width="30%">
                                    </td>
                                    <td style="text-align:right; border: none" width="35%">
                                    </td>

                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" width="95%" colspan="3">
                                        <p style="text-align:justify; white-space: pre-wrap">{{ $invoice->notes }}</p>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                            <table class="table-hover center" width="90%" style="table-layout: fixed; ">
                                <thead>
                                <tr>
                                    <th style="text-align:center" width="5%">Sl</th>
                                    <th style="text-align:center" width="50%">Details</th>
                                    <th style="text-align:center" width="10%">Qty</th>
                                    <th style="text-align:center" width="10%">Unit</th>
                                </tr>
                                </thead>
                                <tbody style="border: none">
                                @foreach($transactionDetails as $key=>$details)
                                    <tr style="border-bottom-style: hidden">
                                        <td>{{ $key+1 }}</td>

                                        <td>
                                            {{ $details->product_title }}
                                        </td>
                                        <td style="text-align:center">{{ $details->qty}} </td>
                                        <td style="text-align:center">{{ $details->unit_name}}</td>
                                    </tr>
                                    <tr></tr>
                                @endforeach

                                </tbody>
                            </table>
                            <table class="center" width="90%" style="table-layout: fixed; border: none">
                                <tr style="border: none">
                                    {{--<td style="border: none"></td>--}}
                                    <td style="border: none" colspan="5">
                                        <br/>
                                        Thank you.
                                        <br/>
                                        <br/>

                                        Best regards,
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        Mob: +880176-5450149.<br/>
                                        E-mail: noman@texaaps.com
                                    </td>
                                    {{--<td colspan="4" style="border: none"></td>--}}
                                </tr>
                                <tr style="border: none">
                                    <td colspan="6" style="border: none">
                                        <div class="pad_footer">
                                            <p>
                                                <img src="{!! asset( 'storage/images/pad_bottom.jpg'. '?'. 'time='. time()) !!}"
                                                     class="img-fluid" style="border: none" width="75%"></p>
                                            <div class="row">
                                                <p class="col-md-6">
                                                    <small>Software By : www.eidyict.com 01716-383038</small>
                                                </p>
                                                <p class="col-md-6" style="text-align: right">
                                                    <small>Print
                                                        Time:{{\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}</small>
                                                </p>
                                            </div>

                                        </div>

                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"
                                    aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                        <a type="button" id="pbutton1" class="btn btn-warning pull-right"><i
                                    class="fa fa-print"> Print</i></a>
                    </div>
                </div>

            </div>

        </div>


    </div>
@endsection

@push('js')
<script src="{!! asset('supporting/printthis.js')!!}" type="text/javascript"></script>
<script type="text/javascript">
    //    console.log(id);
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

    $('#pbutton1').on('click', function () {
        $("#print_this1").printThis({
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


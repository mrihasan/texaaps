@extends('layouts.al305_main')
@section('supply_mo','menu-open')
@section('supply','active')
@section('manage_purchase','active')
@section('title','Manage Purchase')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('purchaseTransaction')}}" class="nav-link">{{ __('all_settings.Purchase') }}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{ __('all_settings.Manage Purchase') }}</a>
    </li>
@endsection
@push('css')
<style>
    @media print {
        /*div{*/
        /*page-break-inside: avoid;*/
        /*}*/
        .pagebreak {
            page-break-before: always;
        }

        /* page-break-after works, as well */
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

        .table {
            font-size: 8px;
        }

        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th,
        .table > thead > tr > td {
            padding: 0px;
        !important;
            /*text-align: center;*/
            /*line-height: 1.42857;*/
            /*line-height: 1.42857;*/
            /*border-top: 1px solid #e7ecf1;*/
        }

        .table > thead > tr > th {
            padding: 0px;
        !important;
            text-align: center;
            /*line-height: 1.42857;*/
            /*line-height: 1.42857;*/
            /*border-top: 1px solid #e7ecf1;*/
        }

    }

    .company-name {
        font-size: 14px;
    }

    .company-address {
        font-size: 10px;
    }

    .customer-name-address {
        font-size: 10px;
    }

    td, th {
        font-size: 10px !important;
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
                       aria-selected="true">{{ __('all_settings.Manage Purchase') }} </a>
                </li>
                <li>
                    <a type="button" id="pbutton0" class="btn btn-success pull-right"><i
                                class="fa fa-print"> Print</i></a>

                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <div class="form-body" id="print_this0">
                        <div class="invoice-content-2 bordered">
                            <table class="center" width="60%" style="border: none">

                                <tbody>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">

                                        <h6 style="text-align: left;margin-top: 0px;margin-bottom: 0px;" class="company-name">
                                            <strong>Bill From</strong></h6>
                                        Attention : {{$invoice->user->name}}<br/>
                                        {{$invoice->user->profile->address}}<br/>
                                        {{$invoice->user->profile->cell_phone.', '.$invoice->user->profile->contact_no1.', '.$invoice->user->profile->contact_no2}}
                                    </td>

                                    <td style="text-align:center;border: none " width="30%">
                                        <h4 style="text-align: center;margin-top: 0px;margin-bottom: 0px; text-decoration: underline" class="company-name">
                                            <strong>Purchase BILL</strong></h4>
                                        <br/>

                                    </td>
                                    <td style="text-align:center; border: none" width="35%">
                                        <div class="company-address"
                                             style="text-align: right; color: blue; font-size:12px">
                                            {{'Sl No: '.$invoice->sl_no}}<br/>
                                            <small>{{'Tracking ID: '.$invoice->transaction_code}}</small>
                                            <br/>
                                            {{' Date: '.Carbon\Carbon::parse($invoice->transaction_date)->format('d-M-Y').','}}<br/>
                                            {{' By: '.($invoice->entryBy->name)}}
                                            <br/>
                                            {{'Reference: '.$invoice->reference}}
                                        </div>

                                    </td>
                                </tr>
                                <tr><td style="text-align:left; border: none " colspan="3">{{$invoice->notes}}</td></tr>
                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">

                                    </td>

                                    <td style="text-align:center; border: none" width="30%">
                                    </td>
                                    <td style="text-align:right; border: none" width="35%">
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <table class="table-hover center" width="60%" style="table-layout: fixed; ">
                            <thead>
                            <tr>
                                <th style="text-align:center" width="5%">Sl</th>
                                <th style="text-align:center" width="50%">Details</th>
                                <th style="text-align:center" width="10%">Qty</th>
                                <th style="text-align:center" width="10%">Unit</th>
                                <th style="text-align:center" width="10%">Unit Price</th>
                                <th style="text-align:center" width="15%">Total</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactionDetails as $key=>$details)
                                <tr>
                                    <td>{{ $key+1 }}</td>

                                    <td>
                                        {{--{{ $details->product_title.' : '.$details->product_type_title }}--}}
                                        <strong>Product : </strong>{{ $details->product_title }}<br/>
                                        <strong>Brand : </strong>{{ $details->brand_title }}<br/>
                                        <strong>Model : </strong>{{ $details->model }}<br/>

                                    </td>
                                    <td style="text-align:right">{{ $details->qty}} </td>
                                    <td style="text-align:right">{{ $details->unit_name}} </td>
                                    <td style="text-align:right">{{ $details->ubuy_price}} </td>
                                    <td style="text-align:right">{{ $details->line_total}} </td>
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
                                <td colspan="2" style="text-align:right">(+)Vat/Tax :</td>
                                <td style="text-align:right">{{$invoice->vat}}</td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align:right"><strong>{{'Previous : From '.\Carbon\Carbon::parse($mindate_ledger)->format('d-M-Y').' to '.\Carbon\Carbon::parse($before1day_invoice)->format('d-M-Y')}} </strong></td>
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
                                    @if($ledger['lastPayment']!=null)
                                        {{'Date & Time: '. $ledger['lastPayment']->transaction_date
                                    .', Payment Method : '. $ledger['lastPayment']->transaction_method->title.', Amount ৳ : '. $ledger['lastPayment']->amount}}

                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td colspan="2" style="text-align:right">Total Amount:</td>
                                <td style="text-align:right">{{$invoice->invoice_total}}</td>
                            </tr>
                            <tr>
                                <td colspan="10" style="text-align:left"><strong>In Word: </strong>
                                    {{numberToWord($invoice->invoice_total).' Taka Only'}}
                                </td>
                            </tr>
                            {{--<tr>--}}
                            {{--<td colspan="4" style="text-align:right" >Due Amount: </td><td style="text-align:right">{{$invoice->total_amount - $total_paid}}</td>--}}
                            {{--</tr>--}}
                            <tr >
                                <td colspan="5" style="height: 40px; border: none">Receiver Signature</td>
                                <td colspan="5" style="border: none">Authorised Signature</td>
                            </tr>
                            <tr style="border: none">
                                <td colspan="10" style="text-align:left; border: none">
                                </td>
                            </tr>


                            </tbody>
                        </table>
                        <table class="table-hover center" width="60%" style="table-layout: fixed; border: none">
                            <tr style="border: none">
                                <td colspan="5" style="text-align:left; border:none">
                                    <small>Software By : www.eidyict.com 01716-383038</small>
                                </td>
                                <td colspan="5" style="text-align:right; border: none">
                                    <small>Print Time:{{\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                        class="fa fa-arrow-left"
                        aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
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
@endsection

@push('js')
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

@endpush


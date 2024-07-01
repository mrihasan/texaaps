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

    }

    .company-name {
        font-size: 14px;
    }

    .company-address {
        font-size: 10px;
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
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-transaction-tab" data-toggle="pill"
                       href="#custom-tabs-one-transaction" role="tab" aria-controls="custom-tabs-one-transaction"
                       aria-selected="false">Transaction History</a>
                </li>


            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <div class="form-body" id="print_this0">
                        <div class="invoice-content-2 bordered">
                            <table class="center" width="95%" style="border: none">

                                <tbody>
                                <tr style="border: none">
                                    <td style="border: none"><img
                                                src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}"
                                                class="img-fluid" style="border: none"></td>
                                </tr>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">

                                        <h6 style="text-align: left;margin-top: 0px;margin-bottom: 0px;" class="company-name">
                                            <strong>Bill From</strong></h6>
                                        Attention : {{$invoice->user->name}}<br/>
                                        {{$invoice->user->profile->address}}<br/>
                                        {{$invoice->user->profile->cell_phone.', '.$invoice->user->profile->contact_no1.', '.$invoice->user->profile->contact_no2}}
                                    </td>

                                    <td style="text-align:center;border: none " width="30%">
                                        <h3 style="text-align: center;margin-top: 0px;margin-bottom: 0px; text-decoration: underline" class="company-name">
                                            <strong>Purchase BILL</strong></h3>
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
                        <table class="table-hover center" width="95%" style="table-layout: fixed; ">
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
                                    <td style="text-align:right">{{ number_format($details->ubuy_price,0)}} </td>
                                    <td style="text-align:right">{{ number_format($details->line_total,0)}} </td>
                                </tr>
                            @endforeach
                            <tr></tr>
                            <tr>
                                <td colspan="3" style="text-align:center"></td>
                                <td colspan="2" style="text-align:right">Sub Total :</td>
                                <td style="text-align:right">{{number_format($invoice->product_total,0)}}</td>

                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:center"><strong>Ledger Balance</strong></td>
                                <td colspan="2" style="text-align:right">(+)Vat/Tax :</td>
                                <td style="text-align:right">{{$invoice->vat}}</td>
                            </tr>

                            <tr>
                                <td colspan="2" style="text-align:right"><strong>{{'Previous : From '.\Carbon\Carbon::parse($mindate_ledger)->format('d-M-Y').' to '.\Carbon\Carbon::parse($before1day_invoice)->format('d-M-Y')}} </strong></td>
                                <td colspan="1" style="text-align:right">{{number_format($ledger['balance_before1day'],0)}}</td>
                                <td colspan="2" style="text-align:right">(-)Discount :</td>
                                <td style="text-align:right">{{$invoice->discount}}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:right">Today
                                    <strong>( {{\Carbon\Carbon::parse($invoice->transaction_date)->format('d-M-Y')}}
                                        )</strong></td>
                                <td colspan="1" style="text-align:right">{{number_format($ledger['balance_today'],0)}}</td>
                                <td colspan="2" style="text-align:right">Net Amount:</td>
                                <td style="text-align:right">{{number_format($invoice->total_amount,0)}}</td>

                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:right">
                                    <strong> {{'From '.\Carbon\Carbon::parse($mindate_ledger)->format('d-M-Y').' to '.\Carbon\Carbon::parse($invoice->transaction_date)->format('d-M-Y')}} </strong>
                                </td>
                                <td colspan="1"
                                    style="text-align:right">{{number_format($ledger['balance_before1day']+$ledger['balance_today'],0)}}</td>
                                <td colspan="2" style="text-align:right">Less Amount:</td>
                                <td style="text-align:right">{{$invoice->less_amount}}</td>
                            </tr>




                            <tr>
                                <td colspan="3" style="text-align:left"><strong>Last Payment Info : </strong>
                                    @if($ledger['lastPayment']!=null)
                                        {{'Date & Time: '. $ledger['lastPayment']->transaction_date
                                    .', Payment Method : '. $ledger['lastPayment']->transaction_method->title.', Amount ৳ : '. number_format($ledger['lastPayment']->amount,0)}}

                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td colspan="2" style="text-align:right">Total Amount:</td>
                                <td style="text-align:right">{{number_format($invoice->invoice_total,0)}}</td>
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
                        <table class="table-hover center" width="95%" style="table-layout: fixed; border: none">
                            <!-- <tr style="border: none">
                                <td colspan="5" style="text-align:left; border:none">
                                    <small>Software By : www.eidyict.com 01716-383038</small>
                                </td>
                                <td colspan="5" style="text-align:right; border: none">
                                    <small>Print Time:{{\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}</small>
                                </td>
                            </tr> -->
                            <tr style="border: none">
                                    <td colspan="6" style="border: none">
                                        <div class="pad_footer">
                                            <div class="row">
                                                <p>
                                                <img src="{!! asset( 'storage/images/pad_bottom.jpg'. '?'. 'time='. time()) !!}"
                                                      style="border: none" width="75%"></p>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">

                                                    <small>Software By : www.eidyict.com 01716-383038</small>

                                                </div>
                                                <div class="col-md-6" style="text-align: right">
                                                    <small style="padding-right: 50px">Print
                                                        Time:{{\Carbon\Carbon::now()->format(' D, d-M-Y, h:ia')}}</small>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                        </table>
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
                <div class="tab-pane fade" id="custom-tabs-one-transaction" role="tabpanel"
                     aria-labelledby="custom-tabs-one-transaction-tab">
                    <div class="portlet-body form portrait" id="print_this2">
                        <div class="content">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th> Date</th>
                                    <th> {{ __('all_settings.Transaction') }} No</th>
                                    <th>User Info</th>
                                    <th> Transaction Type</th>
                                    <th> Transaction Method</th>
                                    <th> amount</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($related_payment as $data)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($data->transaction_date)->format('d-M-Y') }}</td>
                                        <td>{{ $data->transaction_code }}</td>

                                        <td>
                                            <a href="{{ route('user.show',$data->user->id) }}" class="btn btn-success btn-xs"
                                               title="User Profile View"><span class="far fa-user-circle"
                                                                               aria-hidden="true"></span></a>
                                            {{$data->user->name??''}}</td>
                                        <td>{{ $data->transaction_type->title }}</td>
                                        <td>{{ $data->transaction_method->title}}</td>
                                        <td>{{ $data->amount }}</td>
                                        <td>{{ $data->comments }}</td>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"
                                    aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                        <a type="button" id="pbutton2" class="btn btn-warning pull-right"><i
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
    $('#pbutton2').on('click', function () {
        $("#print_this2").printThis({
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


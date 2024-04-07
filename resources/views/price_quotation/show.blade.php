@extends('layouts.al305_main')
@section('supply_mo','menu-open')
@section('supply','active')
@section('manage_price_quotation','active')
@section('title','Price Quotation')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Supply</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">View Price Quotation</a>
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
                       aria-selected="true">Price Quotation </a>
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
                                    <td style="border: none" class="company-name" width="35%">
                                        <strong>{{'Sl No: '.$price_quotation->ref_no}}</strong><br>

                                        <small>{{'Tracking No: '.$price_quotation->tracking_code}}</small>
                                    </td>
                                    <td style="border: none; text-align: right" class="company-name" width="35%">
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <h4 style="text-align: center;margin-top: 0px;margin-bottom: 0px; text-decoration: underline"
                                            class="company-name">
                                            <strong>Price Quotation</strong></h4>
                                    </td>
                                    <td style="border: none; text-align: right" class="company-name" width="35%">
                                        <strong>{{' Date: '.Carbon\Carbon::parse($price_quotation->transaction_date)->format('d-M-Y').','}}</strong><br/>
                                        <strong>
                                            {{'Reference: '.$price_quotation->reference}}
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
                                        @elseif($price_quotation->user->profile->company_name_id!=null)
                                            <strong>{{$price_quotation->user->profile->company_name->title}}</strong>
                                            <br/>
                                            {{$price_quotation->user->profile->company_name->contact_no ?? ''}}<br/>
{{--                                            {{$price_quotation->user->profile->company_name->contact_no2 ?? ''}}<br/>--}}
                                            {{$price_quotation->user->profile->company_name->address ?? ''}}<br/>
                                            {{$price_quotation->user->profile->company_name->address2 ?? ''}}<br/>
                                        @else
                                            <strong>{{$price_quotation->user->name}}</strong>
                                            <br/>
                                            {{$price_quotation->user->profile->mobile}}
                                            <br/>{{$price_quotation->user->profile->address}}
                                            <br/>{{$price_quotation->user->profile->address2}}<br/>
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
                                    <td style="text-align:left; border: none"  colspan="3">
                                        Dear Sir,<br/>
                                        As per your requirements, We are delighted to provide the Price Quotation for the following items for your kind consideration.<br/>
                                        {{$price_quotation->additional_notes}}
                                    </td>

                                    {{--<td style="text-align:center; border: none" width="30%">--}}
                                    {{--</td>--}}
                                    {{--<td style="text-align:right; border: none" width="35%">--}}
                                    {{--</td>--}}

                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" width="95%" colspan="3">
                                        <p style="text-align:justify; white-space: pre-wrap">{{ $price_quotation->notes }}</p>
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
                                    <th style="text-align:center" width="10%">Unit Price (BDT)</th>
                                    <th style="text-align:center" width="15%">Total Price (BDT)</th>
                                </tr>
                                </thead>
                                <tbody style="border: none">
                                @foreach($transactionDetails as $key=>$details)
                                    <tr style="border-bottom-style: hidden">
                                        <td>{{ $key+1 }}</td>

                                        <td>
                                            <strong>Product : </strong>{{ $details->product_title }}<br/>
                                            <strong>Brand : </strong>{{ $details->brand_title }}<br/>
                                            <strong>Model : </strong>{{ $details->model }}<br/>
                                            <strong>Details : </strong>{{ $details->product_details}}<br/>
                                        </td>
                                        <td style="text-align:center">{{ $details->qty}} </td>
                                        <td style="text-align:center">{{ $details->unit_name}}</td>
                                        <td style="text-align:right">{{ $details->unit_price}} </td>
                                        <td style="text-align:right">{{ $details->line_total}} </td>
                                    </tr>
                                @endforeach
                                <tr></tr>
                                <tr>
                                    {{--<td colspan="3" style="text-align:center"></td>--}}
                                    <td colspan="5" style="text-align:right">Total (BDT) :</td>
                                    <td style="text-align:right">{{$price_quotation->invoice_total}}</td>

                                </tr>
                                <tr>
                                    <td colspan="6" style="text-align:left"><strong>In Word (Total): </strong>
                                        {{numberToWord($price_quotation->invoice_total).' Taka Only'}}
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <table class="center" width="90%" style="table-layout: fixed; border: none">
                                <tr style="border: none">
                                    {{--<td style="border: none"></td>--}}
                                    <td style="border: none" colspan="5">
                                        <br/>
                                        <h4>Terms & Conditions:</h4>
                                        <p style="white-space: pre-wrap">{{$price_quotation->terms}}</p><br/>
                                        Thank you.
                                        <br/>
                                        Best regards,
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        {{ entryByInfo($price_quotation->entry_by)['name'] }}<br/>
                                        {{ entryByInfo($price_quotation->entry_by)['cell_phone'] }}<br/>
                                        {{ entryByInfo($price_quotation->entry_by)['email'] }}<br/>
                                        <br/>
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
                    'url' => ['invoice', $price_quotation->id],
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
                            <a href="{{ url('invoice/' . $price_quotation->id . '/edit') }}"
                               class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span
                                        class="far fa-edit"
                                        aria-hidden="true"></span></a>
                        @endcan
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


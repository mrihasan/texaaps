@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('manage_payment_request','active')
@section('title','Show Payment Request')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Show Payment Request</a>
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
                       aria-selected="true">Payment Request </a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <div class="portlet-body form portrait" id="print_this0">
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
                                        <strong>{{'Reff: '.$payment_request->req_no}}</strong></td>
                                    <td style="border: none; text-align: right" class="company-name">
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <br/>
                                        <h4 style="text-align: center;margin-top: 0px;margin-bottom: 0px; text-decoration: underline"
                                            class="company-name">
                                            <strong>PAYMENT REQUEST FORM</strong></h4>
                                    </td>
                                    <td style="border: none; text-align: right" class="company-name">
                                        <strong>{{' Date: '.Carbon\Carbon::parse($payment_request->req_date)->format('d-M-Y')}}</strong><br/>

                                    </td>
                                </tr>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none"  colspan="3">

                                        <h6 style="text-align: left;margin-top: 0px;margin-bottom: 0px;"
                                            class="company-name">
                                            <strong>To</strong></h6>
                                        <p>Managing Director </p>

                                        <address>Texaaps<br/>Rangs Nahraz, H-14(5th Floor )<br/>Shahjalal Avenue, Sector-4,<br/>Uttara, Dhaka-1230</address>
                                        <p>Dear Sir,</p>
                                        <p>Kindly arrange for the Payment for the bellow particulars-</p>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:left; border: none" colspan="3">
                                        <p><strong>Customer Details :</strong></p>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Customer Name :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{companyBy($payment_request->customer_id)}}</strong>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Product Name :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{$payment_request->product->title}}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Product Brand :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{$payment_request->product->brand->title}}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Product Model :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{$payment_request->model}}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Work order Ref No :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{$payment_request->workorder_refno}}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Work order Date :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{ Carbon\Carbon::parse($payment_request->workorder_date)->format('d-M-Y') }}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Work order Amount :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{ $payment_request->workorder_amount }}
                                    </td>
                                </tr>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none" colspan="3">
                                        <p><strong>Purchase / Supplier Details :</strong></p>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Supplier Name :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{companyBy($payment_request->supplier_id)}}</strong>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Supplier Address :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{companyAddress($payment_request->supplier_id)}}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Contact Person :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{$payment_request->contact_person }}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Contact No :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{$payment_request->contact_no}}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Amount :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{$payment_request->amount}}
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>In Word :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        {{numberToWord($payment_request->amount).' Taka Only'}}
                                    </td>
                                </tr>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none" colspan="3">
                                        <p><strong>Bank Details :</strong></p>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Account Name :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{$payment_request->bank_account->account_name}}</strong>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Account No :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{$payment_request->bank_account->account_no}}</strong>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Bank Name :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{$payment_request->bank_account->bank_name}}</strong>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Payment Mode :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{$payment_request->transaction_method->title}}</strong>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Expected Bill :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{$payment_request->expected_bill}}</strong>
                                    </td>
                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:right; border: none" >
                                        <strong>Expected Day :</strong>
                                    </td>
                                    <td style="text-align:left; border: none" >
                                        <strong>{{$payment_request->expected_day}}</strong>
                                    </td>
                                </tr>

                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">
                                        <br/>
                                        <br/>
                                        <br/>
                                    </td>

                                    <td style="text-align:center; border: none" width="30%">
                                    </td>
                                    <td style="text-align:right; border: none" width="35%">
                                    </td>

                                </tr>
                                <tr style="border: none">
                                    <td style="text-align:left; border: none" width="35% ">
                                        Prepared by<br/><br/><br/><br/>
                                        <address>
                                        ______________________<br/>
                                        {{$payment_request->user->name}}<br/>
                                        {{($payment_request->user->employee)?$payment_request->user->employee->designation:''}}<br/>
                                        {{setting_info()['org_name']}}
                                        </address>
                                    </td>

                                    <td style="text-align:center; border: none" width="30%">
                                        Checked by<br/><br/><br/><br/>
                                        <address>
                                            ______________________<br/>
                                            {{($payment_request->checked_by)?$payment_request->checkedBy->name:'Not Yet Checked'}}<br/>
                                            {{($payment_request->checked_by && $payment_request->user->employee)?$payment_request->user->employee->designation:''}}<br/>
                                            {{($payment_request->checked_by)?setting_info()['org_name']:''}}
                                        </address>
                                    </td>
                                    <td style="text-align:right; border: none" width="35%">
                                        Approved By<br/><br/><br/><br/>
                                        <address>
                                            ______________________<br/>
                                            {{($payment_request->approved_by)?$payment_request->approvedBy->name:'Not Yet Approved'}}<br/>
                                            {{($payment_request->approved_by && $payment_request->user->employee)?$payment_request->user->employee->designation:''}}<br/>
                                            {{($payment_request->approved_by)?setting_info()['org_name']:''}}
                                        </address>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"
                                    aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                        <a type="button" id="pbutton0" class="btn btn-warning pull-right"><i
                                    class="fa fa-print"> Print</i></a>
                        {{--@can('SupplyDelete')--}}
                            {{--{!! Form::open([--}}
                    {{--'method'=>'DELETE',--}}
                    {{--'url' => ['invoice', $payment_request->id],--}}
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
                        {{--@can('SupplyAccess')--}}
                            {{--<a href="{{ url('invoice/' . $payment_request->id . '/edit') }}"--}}
                               {{--class="btn btn-info btn-xs fa-pull-right" title="Edit" style="margin-right: 10px"><span--}}
                                        {{--class="far fa-edit"--}}
                                        {{--aria-hidden="true"></span></a>--}}
                        {{--@endcan--}}
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


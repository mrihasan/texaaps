@extends('layouts.al305_main')
@section('report_mo','menu-open')
@section('report','active')
@section('sbalance_sheet','active')
@section('title',$header_title)
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Balance Sheet</a>
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
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

{{--<link href="{{ asset('custom/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />--}}
<!-- Tempusdominus Bbootstrap 4 -->
{{--<link rel="stylesheet"--}}
{{--href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">--}}
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}">


@endpush
@section('maincontent')
    <div class="row justify-content-center">
        <div class="card card-info col-md-12">
            <div class="card-header">
                <h3 class="card-title">{{$header_title}}</h3>
            </div>

            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                         aria-labelledby="custom-tabs-one-home-tab">

                        {!! Form::open(array('method' => 'get', 'url' => 'sbalance_sheet','class'=>'form-horizontal','id'=>'saveForm')) !!}
                        {{ csrf_field() }}

                        <div class="form-group row {{ $errors->has('fiscal_year') ? ' has-error' : '' }}">
                            <label class="control-label col-md-4 text-right">Fiscal Year :<span
                                        class="required"> * </span></label>
                            <div class="col-md-6">
                                <select name="fiscal_year" class="form-control select2" id="fiscal_year">
                                    {{--<option value="">Select fiscal year</option>--}}
                                    @foreach($fiscalYears as $fy)
                                        <option value="{{$fy}}" <?=$fiscalYear == $fy ? ' selected="selected"' : '';?> >{{$fy}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('fiscal_year'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('fiscal_year') }}</strong>
                                </span>
                                @endif
                            </div>
                            {{--<button type="submit" class="btn btn-info float-right">{{ __('all_settings.Search') }}</button>--}}
                        </div>
                        {!! Form::close() !!}
                        <div id="loading" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; text-align: center;">
                            <div style="width: 50px; height: 50px; border: 6px solid #fff; border-top: 6px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px;"></div>
                            <div style="font-size: 18px; font-weight: bold;">Processing your request, please wait...</div>
                        </div>

                        <div class="portlet-body form portrait" id="print_this0">
                            <div class="content">
                                <table class="center" width="60%" style="border: none">

                                    <tbody>
                                    <tr class="print-only" style="border: none" >
                                        <td style="border: none;" colspan="2" ><img
                                                    src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}"
                                                    class="img-fluid" style="border: none" height="auto" width="30%"></td>
                                    </tr>
                                    <tr style="border: none">
                                        <td style="border: none; text-align: center" colspan="2">
                                            <br/>
                                            <h4 style="text-align: center; margin-top: 0px;margin-bottom: 0px; text-decoration: underline;">
                                                <strong>{{$header_title}}</strong></h4>
                                            <h6 style="text-align: center; margin-top: 0px;margin-bottom: 0px; text-decoration: none;">
                                                <small>{{$header_subtitle}}</small></h6>
                                        </td>
                                    </tr>

                                    <tr style="border: none">
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr >
                                        <td style="text-align:left; border: none">
                                        </td>
                                        <td style="text-align:center; text-decoration: underline">Amount In Taka</td>
                                    </tr>
                                    <tr >
                                        <td style="text-align:left; border: none">
                                        </td>
                                        <td style="text-align:center; text-decoration: underline">At <br/>{{$end_date}}</td>
                                    </tr>

                                    <tr >
                                        <td style="text-align:left; text-decoration: underline"  colspan="2">
                                            <strong>ASSETS</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            NON-CURRENT ASSETS
                                        </td>
                                        <td style="text-align:right;" >
                                            {{--{{ number_format($total['fixedAssets'],0)}}--}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Fixed Assets- At Cost Less Depreciation
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['fixedAssets'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
{{--Current Assets Start--}}
                                    <tr >
                                        <td style="text-align:left; text-decoration: underline"  colspan="2">
                                            <strong>Current Assets</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Total Stock (Goods & Raw Materials)
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['total_stock'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Customer Receivable
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['customer_receivable'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    {{--Current Assets End--}}
{{--Other Current Assets Start--}}
                                    <tr >
                                        <td style="text-align:left; text-decoration: underline"  colspan="2">
                                            <strong>Other Current Assets</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Cash Balance
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['cash_balance'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Bank Balance
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['bank_balance'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; " >
                                            <strong>Total Assets </strong>
                                        </td>
                                        <td style="text-align:right; border: solid; " >
                                            <strong>{{number_format($total['all_assets'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>

                                    {{--Current Assets End--}}
{{--Equity & Liability Start--}}
                                    <tr >
                                        <td style="text-align:left; text-decoration: underline"  colspan="2">
                                            <strong>Equity & Liability</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Total Investments
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['investment'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Accumulated Loss/ Profit (Previous year + Current year)
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['accumulatedProfit'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Drawing by Directors
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['drawingbyDirectors'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; ">
                                            <strong>Total equity </strong>
                                        </td>
                                        <td style="text-align:right; border: solid; " >
                                            <strong>{{number_format($total['total_equity'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    {{--Equity & Liability End--}}
{{--Non Current Liability Start--}}
                                    <tr >
                                        <td style="text-align:left; text-decoration: underline"  colspan="2">
                                            <strong>Non Current Liability</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Bank Loan
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['bank_loan']['loan'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Loan From Director's
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['director_loan'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; " >
                                            <strong>Total Non Current Liability </strong>
                                        </td>
                                        <td style="text-align:right; border: solid; " >
                                            <strong>{{number_format($total['non_current_liability'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>

                                    {{--Non Current Liability End--}}
{{--Current Liability Start--}}
                                    <tr >
                                        <td style="text-align:left; text-decoration: underline" colspan="2" >
                                            <strong>Current Liability</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Liabilities for Goods Suppliers (Suppliers Payables)
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['supplier_payable'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;" >
                                            Liabilities for Expenses
                                        </td>
                                        <td style="text-align:right;" >
                                            {{ number_format($total['liabilitiesForExpenses'],0)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; " >
                                            <strong>Total Current Liability </strong>
                                        </td>
                                        <td style="text-align:right; border: solid; " >
                                            <strong>{{number_format($total['current_liability'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; " >
                                            <strong>Total Liability </strong>
                                        </td>
                                        <td style="text-align:right; border: solid; " >
                                            <strong>{{number_format($total['total_liability'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; " >
                                            <strong>Total Liability & Equity </strong>
                                        </td>
                                        <td style="text-align:right; border: solid; " >
                                            <strong>{{number_format($total['total_equity_liability'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; border: none" width="70%" >
                                        </td>
                                        <td style="text-align:right; border: none" width="30%">
                                        </td>
                                    </tr>

                                    {{--Current Liability End--}}

                                    </tbody>
                                </table>
                            </div>
                        </div>
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



            <!-- /.card-body -->
        </div>

    </div>

@endsection
@push('js')
<!-- InputMask for Date picker-->
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
{{--<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>--}}
<!-- Tempusdominus Bootstrap 4 -->
{{--<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>--}}
<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

{{--<script type="text/javascript">--}}
    {{--// Trigger form submission on dropdown change--}}
    {{--$('#fiscal_year').change(function() {--}}
        {{--$('#saveForm').submit(); // Submit the form--}}
    {{--});--}}
{{--</script>--}}
<script type="text/javascript">
    $(document).ready(function () {
        // Trigger spinner during form submission
        $('#fiscal_year').change(function () {
            $('#loading').fadeIn(); // Show the spinner
            $('#saveForm').submit(); // Submit the form
        });

        // Trigger spinner during page refresh or navigation
        $(window).on('beforeunload', function () {
            $('#loading').fadeIn(); // Show the spinner
        });

        // Hide the spinner after the page has fully loaded
        $(window).on('load', function () {
            $('#loading').fadeOut(); // Hide the spinner
        });
    });
</script>

<script>
    //    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>
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
</script>


@endpush
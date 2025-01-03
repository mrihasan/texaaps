@extends('layouts.al305_main')
@section('report_mo','menu-open')
@section('report','active')
@section('income_statement','active')
@section('title','Income Statement')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Income Statement</a>
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
                <h3 class="card-title">{{'Income Statement '.$header_title}}</h3>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                         aria-labelledby="custom-tabs-one-home-tab">
                        {!! Form::open(array('method' => 'get', 'url' => 'income_statement','class'=>'form-horizontal','id'=>'saveForm')) !!}
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
                                    <tr class="print-only" style="border: none">
                                        <td style="border: none;" colspan="3"><img
                                                    src="{!! asset( 'storage/images/pad_top.png'. '?'. 'time='. time()) !!}"
                                                    class="img-fluid" style="border: none" height="auto" width="30%">
                                        </td>
                                    </tr>
                                    <tr style="border: none">
                                        <td style="border: none; text-align: center" colspan="3">
                                            <br/>
                                            <h4 style="text-align: center; margin-top: 0px;margin-bottom: 0px; text-decoration: underline;">
                                                <strong>Profit and Loss Statement</strong></h4>
                                        </td>
                                    </tr>
                                    <tr style="border: none">
                                        <td style="border: none; text-align: center" colspan="3">
                                            <strong>
                                                {{'For the Year : '.$header_title}}
                                            </strong>
                                        </td>
                                    </tr>

                                    <tr style="border: none">
                                        <td style="text-align:left; border: none" width="20%">
                                        </td>
                                        <td style="text-align:left;border: none " width="60%">
                                        </td>
                                        <td style="text-align:right; border: none" width="20%">
                                        </td>
                                    </tr>
                                    {{--Sales Start--}}
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; background-color: #d3d3d3"
                                            colspan="2">
                                            <strong>Sales</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; background-color: #d3d3d3">
                                            <strong>Amount</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid;" rowspan="2">

                                        </td>
                                        <td style="text-align:left; border: solid;">
                                            Total Sales
                                        </td>
                                        <td style="text-align:right; border: solid;">
                                            {{ number_format($total['salesamount'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid;">
                                            Sales Return
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            {{ number_format($total['returnamount'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong>Net Sale</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['netSales'],0)}}</strong>
                                        </td>
                                    </tr>
                                    {{--Sales End--}}
                                    {{--Purchase Start--}}
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; background-color: #d3d3d3"
                                            colspan="2">
                                            <strong>Cost of Sale</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; background-color: #d3d3d3">

                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid;" rowspan="2">

                                        </td>
                                        <td style="text-align:left; border: solid;">
                                            Opening Stock
                                        </td>
                                        <td style="text-align:right; border: solid;">
                                            {{number_format($total['openingStock'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid;">
                                            Total Purchase
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            {{number_format($total['purchaseamount'],0)}}

                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong>Total Stock</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['totalStock'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong> Closing Stock</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['closingStock'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong> Balance Stock</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['balanceStock'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong> Gross Profit</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['grossProfit'],0)}}</strong>
                                        </td>
                                    </tr>
                                    {{--Purchase End--}}
                                    {{--Expense Start--}}
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid; background-color: #d3d3d3"
                                            colspan="2">
                                            <strong>Expense</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; background-color: #d3d3d3">

                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid;" rowspan="2">

                                        </td>
                                        <td style="text-align:left; border: solid;">
                                            All Expnenses
                                        </td>
                                        <td style="text-align:right; border: solid;">
                                            {{number_format($total['expense'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:left; border: solid;">
                                            Salary & Wages
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            {{number_format($total['salary'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong>Total Expense</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['totalExpense'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong>Operating Profit</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['operatingProfit'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong>Bank Charge</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['bankCharge'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid;background-color: #d3d3d3">
                                        <td style="text-align:left; border: solid; " colspan="2">
                                            <strong>Net Profit Before Tax</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            <strong>{{number_format($total['netProfitBeforeTax'],0)}}</strong>
                                        </td>
                                    </tr>
                                    <tr style="border: solid">
                                        <td style="text-align:right; border: solid; " colspan="2">
                                            <strong>Income Tax Paid</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            {{number_format($total['incomeTaxPaid'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid;background-color: #d3d3d3">
                                        <td style="text-align:Left; border: solid; " colspan="2">
                                            <strong>Net Profit For the Year</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            {{number_format($total['netProfitForTheYear'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid;background-color: #d3d3d3">
                                        <td style="text-align:Left; border: solid; " colspan="2">
                                            <strong>Previous Net Income BF</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            {{number_format($total['pre_netProfitForTheYear'],0)}}
                                        </td>
                                    </tr>
                                    <tr style="border: solid;background-color: #d3d3d3">
                                        <td style="text-align:Left; border: solid; " colspan="2">
                                            <strong>Balance transferred to Statement of Financial Position</strong>
                                        </td>
                                        <td style="text-align:right; border: solid; ">
                                            {{number_format($total['balanceTransferred'],0)}}
                                        </td>
                                    </tr>
                                    {{--Expense End--}}
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
    {{--$('#fiscal_year').change(function () {--}}
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
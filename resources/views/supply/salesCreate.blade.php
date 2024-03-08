@extends('layouts.al305_main')
@section('supply_mo','menu-open')
@section('supply','active')
@section('add_sales','active')
@section('title','Sales Create')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('salesTransaction')}}" class="nav-link">{{ __('all_settings.Sales') }}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{ __('all_settings.Add Sales') }}</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('alte305/plugins/jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

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

    td, th {
        font-size: 10px !important;
    }

    table {
        border-collapse: collapse;
    }

    table, tbody, tr, th, td {
        /*border: 1px solid red;*/
        padding: 1px !important;
        font-size: 10px !important;
    }

    table.center {
        margin-left: auto;
        margin-right: auto;
    }

    .form-control {
        padding: 0 !important;
    }

    /* Add this CSS to hide the unitBuyPrice field initially */
    .unitBuyPrice {
        display: none;
    }

    /* Add this CSS to show the unitBuyPrice field when the parent row is hovered */
    tr:hover .unitBuyPrice {
        display: block;
    }

    .autocomplete_list {
        max-height: 50px; /* Adjust the maximum height as needed */
        overflow-y: auto; /* Enable vertical scrolling when the list exceeds the maximum height */
    }

</style>
@endpush
@section('maincontent')
    <meta id="token" name="token" content="{{ csrf_token() }}">
    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">{{ __('all_settings.Add Sales') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('salesTransaction') }}" class="nav-link">
                        {{ __('all_settings.Manage Sales') }}
                    </a>
                </li>

            </ul>
        </div>
        {!! Form::open(['url' => 'invoice', 'files'=> true,'class'=>'form-horizontal','id'=>'saveForm']) !!}

        <div class="form-body">
            {{ csrf_field() }}
            {!! Form::hidden('transaction_type', 'Sales' )!!}

            <div class="card-body">
                @include ('errors.list')
                @include('partials.flash_message')
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group row {{ $errors->has('branch') ? ' has-error' : '' }}">
                            <label class="control-label col-md-12 text-left">Select Branch :<span
                                        class="required"> * </span></label>
                            <div class="col-md-12">
                                {{ Form::select('branch', $branch,null, ['class'=>'form-control select2bs4 ', 'required'] ) }}
                                @if ($errors->has('branch'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('branch') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row {{ $errors->has('customer_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-12 text-left">Select Customer :<span
                                        class="required"> * </span></label>
                            <div class="col-md-12">
                                {{ Form::select('customer_id', $customers,null, ['class'=>'form-control select2bs4 ', 'required'] ) }}
                                @if ($errors->has('customer_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('customer_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group row {{ $errors->has('transaction_date') ? ' has-error' : '' }}">
                            <label class="col-md-12 control-label text-md-left">Transaction Date : <span
                                        class="required"> * </span></label>
                            <div class="col-md-12 input-group date" id="transaction_date" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" name="transaction_date"
                                       value="{{ old('transaction_date') }}" data-target="#transaction_date"/>
                                <div class="input-group-append" data-target="#transaction_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            @if ($errors->has('transaction_date'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('transaction_date') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group row {{ $errors->has('reference') ? ' has-error' : '' }}">
                            <label class="col-md-12 control-label text-md-left">Reference :</label>
                            <div class="col-md-12">
                                {!! Form::text('reference', null,['class'=>'form-control ', 'placeholder'=>'Enter Reference']) !!}
                                @if ($errors->has('reference'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('reference') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row suggestion">
                    <div class="col-md-4">
                        {!! Form::text('name', null,['class'=>'form-control ', 'placeholder'=>"Please Enter Customer Name",'style'=>"background-color: #FEA7A7"]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::text('mobile', null,['class'=>'form-control ', 'placeholder'=>"Please Enter Customer Mobile No",'style'=>"background-color: #FEA7A7"]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::text('address', null,['class'=>'form-control ', 'placeholder'=>"Please Enter Customer Address",'style'=>"background-color: #FEA7A7"]) !!}
                    </div>
                </div>

                <div class='row'>
                    {{--<div class='col-xs-10 col-sm-10 col-md-10 col-lg-10'>--}}
                    <table class="table table-bordered table-hover ">
                        <thead>
                        <tr>
                            <th style="text-align:center;background-color: #7adeee"><input id="check_all"
                                                                                           class="formcontrol"
                                                                                           type="checkbox"/></th>
                            <th style="text-align:center; width: 30%;background-color: #7adeee">Title</th>
                            <th class="d-none">id</th>
                            <th style="text-align:center; width:10%;background-color: #7adeee">Qty</th>
                            <th style="text-align:center; width:10%;background-color: #7adeee">Unit</th>
                            <th style="text-align:center; width:10%;background-color: #7adeee">In Stock</th>
                            <th style="text-align:center; width:10%;background-color: #7adeee">UBP Alt+U</th>
                            <th style="text-align:center;background-color: #7adeee">Unit Sell Price</th>
                            {{--<th style="text-align:center">Discount</br>(%)</th>--}}
                            {{--<th style="text-align:center">Discount</br>Amount</th>--}}
                            {{--<th style="text-align:center">Discounted</br> MRP</th>--}}
                            <th style="text-align:center;background-color: #7adeee">Line Total</th>
                            {{--<th style="text-align:center">Discount</br>Total</th>--}}
                            {{--<th style="text-align:center">Discounted</br> Total MRP</th>--}}
                            {{--<th style="text-align:center">TBP</th>--}}

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="text-align:center"><input class="case" type="checkbox"/></td>
                            <td class="col-md-2"><input custom="doup" type="text" data-type="title"
                                                        name="itemName[]"
                                                        id="itemName_1"
                                                        class="form-control autocomplete_txt autocomplete_list"
                                                        autocomplete="off" required></td>
                            <td class="d-none"><input type="text" data-type="productId" name="productId[]"
                                                      id="productId_1"
                                                      class="form-control productID autocomplete_txt"
                                                      autocomplete="off"></td>

                            <td><input type="number" name="quantity[]" id="quantity_1"
                                       class="form-control changesNo "
                                       autocomplete="off" step="any"
                                       onkeypress="return IsNumeric(event);"
                                       style="text-align:right"
                                       ondrop="return false;" onpaste="return false;"></td>
                            <td><input type="text" name="unit_name[]" readonly
                                       id="unit_name_1" style="text-align:center"
                                       class="form-control autocomplete_txt "
                                       autocomplete="off"></td>
                            <td><input type="text" step="any" name="stock[]" id="stock_1" readonly
                                       class="form-control in_stock">
                            </td>

                            <td><input type="text" data-type="unitBuyPrice" name="unitBuyPrice[]"
                                       readonly
                                       id="unitBuyPrice_1" style="text-align:right"
                                       class="form-control autocomplete_txt unitBuyPrice"
                                       autocomplete="off"></td>
                            <td><input type="number" data-type="unitSellPrice" name="unitSellPrice[]"
                                       id="unitSellPrice_1" style="text-align:right"
                                       class="form-control autocomplete_txt changesNo"
                                       autocomplete="off"></td>
                            <td><input readonly type="number" step="any" name="mrpTotal[]"
                                       id="mrpTotal_1" class="form-control changesNo mrpTotal"
                                       autocomplete="off" style="text-align:right"
                                       onkeypress="return IsNumeric(event);"
                                       ondrop="return false;" onpaste="return false;"></td>
                            <td class="d-none"><input readonly type="text" step="any" name="totalBuyPrice[]"
                                                      id="totalBuyPrice_1" class="form-control totalBuyPrice"
                                                      autocomplete="off" style="text-align:right"
                                                      onkeypress="return IsNumeric(event);"
                                                      ondrop="return false;" onpaste="return false;"></td>
                        </tr>
                        </tbody>
                    </table>
                    {{--</div>--}}
                    <div class='col-md-6 '>
                        <button class="btn btn-danger delete" type="button">- Delete</button>
                        <button class="btn btn-success addmore" type="button" id="add-more-button">+ Add More
                            <small style="color: cyan"> (Alt+A)</small>
                        </button>
                        <div>&nbsp;</div>
                        <div>
                            {!! Form::label('notes', 'Notes') !!}
                            {!! Form::textarea('notes', null,['class'=>'form-control','placeholder'=>'Invoice Comments']) !!}
                        </div>
                    </div>
                    <div class='col-md-2 '></div>
                    <div class='col-md-4'>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background-color: #7adeee">Sub Total ৳</span>
                            </div>
                            <input readonly type="number" step="any" class="form-control" id="subTotal"
                                   placeholder="Subtotal" style="text-align:right" name="product_total"
                                   onkeypress="return IsNumeric(event);" ondrop="return false;"
                                   onpaste="return false;">
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: #7adeee">Vat/Tax (%)</span>
                                </div>
                                <input type="number" step="any" class="form-control" id="tax"
                                       placeholder="VAT/Tax %"
                                       name="vat_per" style="text-align:right"
                                       onkeypress="return IsNumeric(event);"
                                       ondrop="return false;" onpaste="return false;">
                                <input readonly type="number" step="any" name="tax_amount"
                                       class="form-control"
                                       id="taxAmount" placeholder="0" style="text-align:right"
                                       onkeypress="return IsNumeric(event);" ondrop="return false;"
                                       onpaste="return false;">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <span class="input-group-text"
                                      style="background-color: #7adeee">Invoice Discount (%)</span>
                                </div>

                                <input type="number" step="any" class="form-control" id="discount"
                                       placeholder="Discount %"
                                       name="disc_per" style="text-align:right"
                                       onkeypress="return IsNumeric(event);"
                                       ondrop="return false;" onpaste="return false;">
                                <input readonly type="number" step="any" name="discount_amount"
                                       class="form-control"
                                       id="discountAmount" placeholder="0" style="text-align:right"
                                       onkeypress="return IsNumeric(event);" ondrop="return false;"
                                       onpaste="return false;">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="background-color: #7adeee">Total ৳</span>
                                </div>
                                <input readonly type="number" step="any" name="total_amount" class="form-control"
                                       id="totalAftertax" placeholder="Total" style="text-align:right"
                                       onkeypress="return IsNumeric(event);" ondrop="return false;"
                                       onpaste="return false;">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"
                                          style="background-color: #7adeee">Less Amount ৳</span>
                                </div>
                                <input type="number" step="any" name="less_amount" class="form-control"
                                       value="0" id="lessAmount" placeholder="Less Amount"
                                       style="text-align:right"
                                       onkeypress="return IsNumeric(event);" ondrop="return false;"
                                       onpaste="return false;">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"
                                          style="background-color: #7adeee">Invoice Total ৳</span>
                                </div>
                                <input readonly type="number" class="form-control invoiceTotal"
                                       id="invoiceTotal" name="invoice_total"
                                       placeholder="Invoice Total" style="text-align:right" value="0"
                                       onkeypress="return IsNumeric(event);" ondrop="return false;"
                                       onpaste="return false;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row d-none">
                    <div class='col-md-12'>
                        <span id="balanceAmount" style="color: #9f1447"></span>
                    </div>
                    <div class='col-md-6'>
                        <div>
                            {!! Form::textarea('comments', null,['class'=>'form-control','placeholder'=>'Received Comments','rows'=>'4']) !!}
                        </div>

                    </div>
                    <div class='col-md-2'></div>
                    <div class='col-md-4'>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"
                                          style="background-color: #7adeee">Received Amount ৳</span>
                                </div>
                                <input type="number" class="form-control " name="received_amount"
                                       id="paidAmount"
                                       placeholder="Received Amount" style="text-align:right" value="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"
                                          style="background-color: #7adeee">Transaction Method</span>
                                </div>
                                {{ Form::select('transaction_method', $transaction_methods, null,['class'=>'form-control select2bs4'] ) }}
                            </div>
                            @if ($errors->has('transaction_method'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('transaction_method') }}</strong>
                                    </span>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-outline-dark"><i
                            class="fa fa-arrow-left"
                            aria-hidden="true"></i> {{ __('all_settings.Back') }}</a>

                <button type="submit" class="btn btn-info float-right" id="saveButton"><i
                            class="fa fa-save"
                            aria-hidden="true"></i> Save
                </button>
            </div>
            <!-- /.card-footer -->

            {!! Form::close() !!}

        </div>
    </div>

@endsection

@push('js')
{{--<script src="{!! asset('atf/global/plugins/jquery-ui/jquery-ui.min.js')!!}" type="text/javascript"></script>--}}
<script src="{!! asset('alte305/plugins/jquery-ui/jquery-ui.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>

<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>
<script src="{!! asset('supporting/invoice/auto_sales_sp.js')!!}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })
</script>
<script>
    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#transaction_date').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y'
        });
    })

</script>
<script>
    $(document).ready(function () {
        $(".suggestion").hide()
        $('[name=customer_id]').on('change', function () {
            $('.suggestion').toggle(this.value === '6');
        })
    });
</script>

<script>
    $(document).on('change keyup blur', '.changesNo', function () {
        var $this = $(this);
        id_arr = $(this).attr('id');
        id = id_arr.split("_");
        product_id = $('#productId_' + id[1]).val();
        quantity = $('#quantity_' + id[1]).val();
        var url = siteURL + '/in_stock_qty';
        var sendData = {
            product_id: product_id,
            qty: quantity,
            transaction_type: 'Sales',
            _token: $("input[name='_token']").val()
        };
        $.get(url, sendData, function (data) {
//            console.log(data);
            $this.closest('tr').find('.in_stock').val(data.in_stock);
        }, 'json')
    });
</script>
<script>
    $("select[name='customer_id']").change(function () {
        var user_id = $(this).val();
//        console.log(user_id);
        var token = $("input[name='_token']").val();
        $.ajax({
            url: "user_balance",
            method: 'POST',
            data: {user_id: user_id, _token: token},
            success: function (data) {
                console.log(data);
                jQuery('#balanceAmount').text('Name: ' + data.user_info.name +' , Cell Phone: ' + data.user_info.cell_phone + ' , Last Transaction Amount: ' + data.last_transaction_amount +' & type was: ' + data.last_transaction_type + ' Dated:' + data.last_transaction_date + ' , Current Balance is: ' + data.balance);
            },
            error: function () {
                jQuery('#balanceAmount').text('Sorry, an error occurred.');
            }

        });
    });
</script>

{{--prevent multiple form submits (Jquery needed)--}}
<script>
    $('#saveForm').submit(function () {
        $("#saveButton", this)
            .html("Please Wait...")
            .attr('disabled', 'disabled');
        return true;
    });
</script>
@endpush
@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('add_payment_request','active')
@section('title','Add Payment Request')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Payment Request</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">

@endpush
@section('maincontent')

    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">Add Payment Request</h3>
            </div>

            {!! Form::open(['url' => 'payment_request', 'class'=>'form-horizontal','id'=>'saveForm']) !!}

            {{ csrf_field() }}

            <div class="card-body">
                <div class="form-group row{{ $errors->has('branch') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Branch :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        <select name="branch" class="form-control select2" style="width: 100%;" id="branch">
                            {{--<option>Select Branch</option>--}}
                            @foreach($branches as $key=>$branch)
                                <option value="{{ $key }}" {{ (session()->get('brand')) == $key ? 'selected' : '' }}>{{ $branch}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($errors->has('branch'))
                        <em class="invalid-feedback">
                            {{ $errors->first('branch') }}
                        </em>
                    @endif
                </div>

                <div class="form-group row {{ $errors->has('request_date') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Request Date : <span
                                class="required"> * </span></label>
                    <div class="col-md-6 input-group date" id="request_date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="request_date"
                               value="{{ old('request_date') }}" data-target="#request_date"/>
                        <div class="input-group-append" data-target="#request_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    @if ($errors->has('request_date'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('request_date') }}</strong>
                                    </span>
                    @endif
                </div>
                <div class="form-group row{{ $errors->has('bank_account') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Select Account :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        <select name="bank_account" class="form-control select2" style="width: 100%;" id="bank_account">
                            @foreach($to_accounts as $key=>$to_account)
                                <option value="{{ $key }}">{{ $to_account}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($errors->has('bank_account'))
                        <em class="invalid-feedback">
                            {{ $errors->first('bank_account') }}
                        </em>
                    @endif
                </div>
                <div class="form-group row {{ $errors->has('transaction_method') ? ' has-error' : '' }}">
                    <label class="col-sm-4 control-label text-md-right">Payment Mode : <span
                                class="required"> * </span></label>
                    <div class="col-sm-6">
                        {{ Form::select('transaction_method', $transaction_methods, null,['class'=>'form-control select2'] ) }}
                        @if ($errors->has('transaction_method'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('transaction_method') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('expected_bill') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Expected Bill :</label>
                    <div class="col-md-6">
                        {!! Form::text('expected_bill', null,['class'=>'form-control ', 'placeholder'=>'Enter Expected Bill ']) !!}
                        @if ($errors->has('expected_bill'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('expected_bill') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('expected_day') ? ' has-error' : '' }}">
                    <label class="col-sm-4 control-label text-md-right">Expected Day : </label>
                    <div class="col-sm-6">
                        {{ Form::select('expected_day', $expected_days, null,['class'=>'form-control select2'] ) }}
                        @if ($errors->has('expected_day'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('expected_day') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>

                <hr/>
                <div class="form-group row {{ $errors->has('customer') ? ' has-error' : '' }}">
                    <label for="customer" class="col-md-4 control-label text-md-right">Select
                        Customer :<span class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('customer', $customer,null, ['class'=>'form-control select2bs4','autofocus'=>'autofocus' ] ) }}
                        @if ($errors->has('customer'))
                            <span class="help-block"><strong>{{ $errors->first('customer') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('product') ? ' has-error' : '' }}">
                    <label for="product" class="col-md-4 control-label text-md-right">Select
                        Product :<span class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('product', $product,null, ['class'=>'form-control select2bs4' ] ) }}
                        @if ($errors->has('product'))
                            <span class="help-block"><strong>{{ $errors->first('product') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('model') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Model :</label>
                    <div class="col-md-6">
                        {!! Form::text('model', null,['class'=>'form-control ', 'placeholder'=>'Enter Model']) !!}
                        @if ($errors->has('model'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('model') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('workorder_refno') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Work order Ref No :</label>
                    <div class="col-md-6">
                        {!! Form::text('workorder_refno', null,['class'=>'form-control ', 'placeholder'=>'Enter work order ref no']) !!}
                        @if ($errors->has('workorder_refno'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('workorder_refno') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('workorder_date') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Work Order Date : </label>
                    <div class="col-md-6 input-group date" id="workorder_date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="workorder_date"
                               value="{{ old('workorder_date') }}" data-target="#workorder_date"/>
                        <div class="input-group-append" data-target="#workorder_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    @if ($errors->has('workorder_date'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('workorder_date') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group row {{ $errors->has('workorder_amount') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Work Order Amount :</label>
                    <div class="col-md-6">
                        {!! Form::number('workorder_amount', null,['class'=>'form-control ','step'=>'any', 'min'=>'0.0', 'placeholder'=>'Work Order Amount ']) !!}
                        @if ($errors->has('workorder_amount'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('workorder_amount') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <hr/>
                <div class="form-group row {{ $errors->has('supplier') ? ' has-error' : '' }}">
                    <label for="supplier" class="col-md-4 control-label text-md-right">Select
                        Supplier :<span class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('supplier', $supplier,null, ['class'=>'form-control select2bs4','autofocus'=>'autofocus' ] ) }}
                        @if ($errors->has('supplier'))
                            <span class="help-block"><strong>{{ $errors->first('supplier') }}</strong></span>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('contact_person') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Contact Person :</label>
                    <div class="col-md-6">
                        {!! Form::text('contact_person', null,['class'=>'form-control ', 'placeholder'=>'Enter contact person']) !!}
                        @if ($errors->has('contact_person'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('contact_person') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('contact_no') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Contact No :</label>
                    <div class="col-md-6">
                        {!! Form::text('contact_no', null,['class'=>'form-control ', 'placeholder'=>'Enter contact no']) !!}
                        @if ($errors->has('contact_no'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('contact_no') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('amount') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Amount :</label>
                    <div class="col-md-6">
                        {!! Form::number('amount', null,['class'=>'form-control ','step'=>'any', 'min'=>'0.0', 'placeholder'=>'Amount ']) !!}
                        @if ($errors->has('amount'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>

            </div>

            <!-- /.card-body -->
            <div class="card-footer">
                <a href="{{ url()->previous() }}" class="btn btn-outline-dark"><i
                            class="fa fa-arrow-left"
                            aria-hidden="true"></i> Back</a>

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
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

{{--prevent multiple form submits (Jquery needed)--}}
<script>
    $('#saveForm').submit(function () {
        $("#saveButton", this)
            .html("Please Wait...")
            .attr('disabled', 'disabled');
        return true;
    });
</script>
<script>

    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#request_date').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y',
            // minDate: '03/06/2019',
        });
        $('#workorder_date').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y',
            // minDate: '03/06/2019',
        });

        //Initialize Select2 Elements
        $('.select2').select2()
    })
</script>


@endpush
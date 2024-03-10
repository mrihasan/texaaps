@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('deposit','active')
@section('title','Deposit Update')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Deposit</a>
    </li>
@endsection
@push('css')
{{--<link href="{{ asset('custom/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />--}}
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}">


@endpush
@section('maincontent')
    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">Bank Deposit Update</h3>
            </div>
            {!! Form::model($bank_ledger,['method'=>'PATCH', 'route'=>['bank_ledger.update',$bank_ledger->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
            {{ csrf_field() }}
            {!! Form::hidden('transaction_type_id', 8 )!!} {{--Deposite--}}

            <div class="card-body">
                <div class="form-group row{{ $errors->has('branch') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Branch :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('branch', $branches, $bank_ledger->branch_id, ['class'=>'form-control select2bs4 ', 'required'] ) }}
                    </div>
                    @if($errors->has('branch'))
                        <em class="invalid-feedback">
                            {{ $errors->first('branch') }}
                        </em>
                    @endif
                </div>
                <div class="form-group row{{ $errors->has('bank_account') ? 'has-error' : '' }}">
                    <label for="roles" class="col-md-4 control-label text-right">Select Account :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {{ Form::select('bank_account', $to_accounts, $bank_ledger->bank_account_id, ['class'=>'form-control select2bs4 ', 'required'] ) }}
                    </div>
                    @if($errors->has('bank_account'))
                        <em class="invalid-feedback">
                            {{ $errors->first('bank_account') }}
                        </em>
                    @endif
                </div>
                <div class="form-group row {{ $errors->has('transaction_date') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Transaction Date : <span
                                class="required"> * </span></label>
                    <div class="col-md-6 input-group date" id="transaction_date"
                         data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                               name="transaction_date"
                               value="{{Carbon\Carbon::parse(date('Y-m-d ', strtotime($bank_ledger->transaction_date)))->format('dd-mm-YYYY')}}" data-target="#transaction_date"/>
                        {{--                                  {!! Form::input('text', 'transaction_date', \Carbon\Carbon::now()->format('d-M-Y'),['class'=>'form-control']) !!}--}}
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

                <div class="form-group row {{ $errors->has('amount') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Amount :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::number('amount', $bank_ledger->amount,['class'=>'form-control ', 'placeholder'=>'Enter Amount']) !!}
                        @if ($errors->has('amount'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>

                <div class="form-group row {{ $errors->has('transaction_method') ? ' has-error' : '' }}">
                    <label class="col-sm-4 control-label text-md-right">Transaction Method : <span
                                class="required"> * </span></label>
                    <div class="col-sm-6">
                        {{ Form::select('transaction_method', $transaction_methods, $bank_ledger->transaction_method_id,['class'=>'form-control select2'] ) }}
                        @if ($errors->has('transaction_method'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('transaction_method') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('ref_no') ? ' has-error' : '' }}">
                    <label for="ref_no" class="col-md-4 control-label text-md-right">Ref No :</label>
                    <div class="col-md-6">
                        {!! Form::text('ref_no', $bank_ledger->ref_no,['class'=>'form-control ', 'placeholder'=>'Enter Ref No']) !!}
                        @if ($errors->has('ref_no'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('ref_no') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('ref_date') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Ref Date : </label>
                    <div class="col-md-6 input-group date" id="ref_date"
                         data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input"
                               name="ref_date"
                               value="{{($bank_ledger->ref_date!=null)?Carbon\Carbon::parse(date('Y-m-d ', strtotime($bank_ledger->ref_date)))->format('dd-mm-YYYY'):''}}" data-target="#ref_date"/>
                        <div class="input-group-append" data-target="#ref_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    @if ($errors->has('ref_date'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('ref_date') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group row">
                    <label class="col-md-4 control-label text-md-right">Particulars :</label>
                    <div class="col-md-6">
                        {!! Form::textarea('particulars', null,['class'=>'form-control ', 'placeholder'=>'i.e: Check No : CA123456']) !!}
                    </div>
                </div>
            </div>

            <!-- /.card-body -->
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
<!-- InputMask for Date picker-->
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
    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });
    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#transaction_date').datetimepicker({
//            date: moment(),
            format: 'DD-MM-YYYY'
        });
        $('#ref_date').datetimepicker({
//            date: moment(),
            format: 'DD-MM-YYYY'
        });

    })
</script>


@endpush
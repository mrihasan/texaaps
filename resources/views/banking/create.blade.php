@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('manage_bank_account','active')
@section('title','Add Account')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Account</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')

    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">Add Account</h3>
            </div>

            {!! Form::open(['url' => 'bank_account', 'class'=>'form-horizontal','id'=>'saveForm']) !!}

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

                <div class="form-group row {{ $errors->has('account_name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Account Name :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::text('account_name', null,['class'=>'form-control ', 'placeholder'=>'Enter Account Name']) !!}
                        @if ($errors->has('account_name'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('account_name') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>
                <div class="form-group row {{ $errors->has('account_no') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Account Number :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::text('account_no', null,['class'=>'form-control ', 'placeholder'=>'Enter Account Number']) !!}
                        @if ($errors->has('account_no'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('account_no') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 control-label text-md-right">Account Type :<span
                                class="required"> * </span></label>
                    <div class="col-sm-6">
                        <select name="account_type" class="form-control" id="account_type" required>
                            <option value="" >Select Account type</option>
                            <option value="Bank Account" {{ (isset($banking) && $banking->account_type == 'Bank Account') ? ' selected' : '' }}>Bank Account</option>
                            <option value="Mobile Banking" {{ (isset($banking) && $banking->account_type == 'Mobile Banking') ? ' selected' : '' }}>Mobile Banking</option>
                            <option value="Petty Cash" {{ (isset($banking) && $banking->account_type == 'Petty Cash') ? ' selected' : '' }}>Petty Cash</option>
{{--                            <option value="Others" {{ (isset($banking) && $banking->account_type == 'Others') ? ' selected' : '' }}>Others</option>--}}
                        </select>
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('opening_balance') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Opening Balance :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::number('opening_balance', null,['class'=>'form-control ', 'placeholder'=>'Enter Amount', 'step'=>'any', 'min'=>'0.0']) !!}
                        @if ($errors->has('opening_balance'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('opening_balance') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>



                <div class="form-group row">
                    <label class="col-md-4 control-label text-md-right"> Details :</label>
                    <div class="col-md-6">
                        {!! Form::textarea('details', null,['class'=>'form-control ', 'placeholder'=>'Details']) !!}
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
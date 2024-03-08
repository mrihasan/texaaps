@extends('layouts.al305_main')
@section('expense_mo','menu-open')
@section('expense','active')
@section('manage_expense_type','active')
@section('title','Add Expense Type')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('expense_type') }}" class="nav-link">Expense Type</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Expense Type</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')

    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">Add Expense Type</h3>
            </div>
            {!! Form::open(array('route' => 'expense_type.store','method'=>'POST','class'=>'form-horizontal','id'=>'saveForm')) !!}


            {{ csrf_field() }}

            <div class="card-body">

                <div class="form-group row {{ $errors->has('expense_name') ? ' has-error' : '' }}">
                    <label for="expense_name" class="col-md-4 control-label text-md-right">Expense Type Name :
                        <span class="required"> * </span></label>
                    <div class="col-md-6">
                        <input id="expense_name" type="text" class="form-control input-circle" name="expense_name"
                               value="{{ old('expense_name') }}" placeholder="Enter Expense Type Name">
                        @if ($errors->has('expense_name'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('expense_name') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- /.card-body -->
            <div class="card-footer">
                {{--<button type="submit" class="btn btn-outline-dark">{{ __('all_settings.Back') }}</button>--}}
                <a href="{{ url()->previous() }}" class="btn btn-outline-dark"><i
                            class="fa fa-arrow-left"
                            aria-hidden="true"></i> {{ __('all_settings.Back') }}</a>

                <button type="submit" class="btn btn-info float-right" id="saveButton"><i
                            class="fa fa-save"
                            aria-hidden="true"></i> Save</button>
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
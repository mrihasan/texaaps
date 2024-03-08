@extends('layouts.al305_main')
@section('expense_mo','menu-open')
@section('expense','active')
@section('manage_expense_type','active')
@section('title','Update Expense Type')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('expense_type') }}" class="nav-link">Expense Type</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Update Expense Type</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')

    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">Update Expense Type</h3>
            </div>
            {!! Form::model($expense_type,['method'=>'PATCH', 'route'=>['expense_type.update',$expense_type->id],'class'=>'form-horizontal']) !!}


            {{ csrf_field() }}

            <div class="card-body">

                <div class="form-group row {{ $errors->has('expense_name') ? ' has-error' : '' }}">
                    <label for="expense_name" class="col-md-4 control-label text-md-right">Expense Type Name :
                        <span class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::text('expense_name', null,['class'=>'form-control ', 'placeholder'=>'Enter Expense Type Name ']) !!}
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

                <button type="submit" class="btn btn-info float-right"><i
                            class="fa fa-save"
                            aria-hidden="true"></i> Save</button>
            </div>
            <!-- /.card-footer -->

            {!! Form::close() !!}
        </div>
    </div>
@endsection
@push('js')


@endpush

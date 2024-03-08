@extends('layouts.al305_main')
@section('expense_mo','menu-open')
@section('expense','active')
@section('add_expense','active')
@section('title','Add Expense')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('expense') }}" class="nav-link">Expense</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Expense</a>
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
                <h3 class="card-title">Add Expense</h3>
            </div>

            {!! Form::open(['url' => 'expense', 'files'=> true,'class'=>'form-horizontal','id'=>'saveForm']) !!}

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
                <div class="form-group row {{ $errors->has('expense_type') ? ' has-error' : '' }}">
                    <label class="col-sm-4 control-label text-md-right">Select Expense type : <span
                                class="required"> * </span></label>
                    <div class="col-sm-6">
                        {{ Form::select('expense_type', $expense_type, null,['class'=>'form-control select2', 'id'=>'expense_type', 'autofocus'=>'autofocus' ] ) }}
                        @if ($errors->has('expense_type'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('expense_type') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>

                <div class="form-group row {{ $errors->has('expense_date') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Expense Date : <span
                                class="required"> * </span></label>
                    <div class="col-md-6 input-group date" id="expense_date" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" name="expense_date"
                               value="{{ old('expense_date') }}" data-target="#expense_date"/>
                        {{--                                  {!! Form::input('text', 'expense_date', \Carbon\Carbon::now()->format('d-M-Y'),['class'=>'form-control']) !!}--}}
                        <div class="input-group-append" data-target="#expense_date"
                             data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    @if ($errors->has('expense_date'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('expense_date') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group row {{ $errors->has('expense_amount') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Amount :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::number('expense_amount', null,['class'=>'form-control ', 'placeholder'=>'Enter Amount', 'step'=>'any']) !!}
                        @if ($errors->has('expense_amount'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('expense_amount') }}</strong>
                                    </span>
                        @endif

                    </div>
                </div>


                <div class="form-group row {{ $errors->has('transaction_method') ? ' has-error' : '' }}">
                    <label class="col-sm-4 control-label text-md-right">Transaction Method : <span
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
                <div class="form-group row">
                    <label class="col-md-4 control-label text-md-right"> Comments:</label>
                    <div class="col-md-6">
                        {!! Form::textarea('expense_comments', null,['class'=>'form-control ', 'placeholder'=>'i.e: Conveyance of Mr.x from Motijil to Badda']) !!}
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
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

<script>

    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#expense_date').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y',
            // minDate: '03/06/2019',
        });

        //Initialize Select2 Elements
        $('.select2').select2()
    })
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

<script>
    $(function () {
        $('#expense_type').on('change', function () {
            if (this.value == 1) {
                $("#select_rent_month").show();
            }
            else {
                $("#select_rent_month").hide();
            }
        });
    });
</script>


@endpush
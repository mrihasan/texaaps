@extends('layouts.al305_main')
@section('superadmin_mo','menu-open')
@section('superadmin','active')
@section('manage_branch','active')
@section('title','Manage Branch')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('branch')}}" class="nav-link">Branch</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Update Branch</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">


@endpush
@section('maincontent')

    <div class="row justify-content-center">
        <div class="card card-info col-md-8">
            <div class="card-header">
                <h3 class="card-title">Update Branch</h3>
            </div>

            {!! Form::model($branch,['method'=>'PATCH', 'route'=>['branch.update',$branch->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}

            {{ csrf_field() }}

            <div class="card-body">

                <div class="form-group row {{ $errors->has('title') ? ' has-error' : '' }}">
                    <label for="title" class="col-md-4 control-label text-md-right">Title :
                        <span class="required"> * </span></label>
                    <div class="col-md-6">
                        {!! Form::text('title', null,['class'=>'form-control ', 'placeholder'=>'Enter Title']) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row{{ $errors->has('code_no') ? ' has-error' : '' }}">
                    <label for="code_no" class="col-md-4 control-label text-md-right">Code Number (Unique):
                    </label>
                    <div class="col-md-6">
                        {!! Form::text('code_no', null,['class'=>'form-control ', 'placeholder'=>'Enter code no']) !!}
                        @if ($errors->has('code_no'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('code_no') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('contact_no1') ? ' has-error' : '' }}">
                    <label for="contact_no1" class="col-md-4 control-label text-right">Mobile Number :</label>
                    <div class="col-md-6">
                        {!! Form::text('contact_no1',null, array('placeholder' => 'cell phone number','class' => 'form-control',
                        'pattern'=>'[0]{1}[1]{1}[0-9]{9}','maxlength'=>'11')) !!}
                        <span class="help-block">11 Digit and Start with 01
                                    </span>
                        @if ($errors->has('contact_no1'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('contact_no1') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('contact_no12') ? ' has-error' : '' }}">
                    <label for="contact_no12" class="col-md-4 control-label text-right">Phone Number :</label>
                    <div class="col-md-6">
                        {!! Form::text('contact_no2',null, array('placeholder' => 'phone number','class' => 'form-control')) !!}
                        @if ($errors->has('contact_no12'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('contact_no12') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row {{ $errors->has('address') ? ' has-error' : '' }}">
                    <label for="address" class="col-md-4 control-label text-md-right">Address : </label>
                    <div class="col-md-6">
                        {!! Form::textarea('address',null, array('placeholder' => 'address','class' => 'form-control')) !!}
                        @if ($errors->has('address'))
                            <span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
                        @endif
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('status') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label text-md-right">Status : <span
                                class="required"> * </span></label>
                    <div class=" col-md-6 mt-radio-inline">
                        <label class="mt-radio">
                            {{ Form::radio('status', 'Active') }} Active
                            <span></span>
                        </label>
                        <label class="mt-radio">
                            {{ Form::radio('status', 'Inactive') }} Inactive
                            <span></span>
                        </label>
                    </div>

                    @if ($errors->has('status'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                    @endif
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
<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#joining_date').datetimepicker({
//            date: moment(),
            format: 'YYYY-mm-DD'
        });
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


@endpush
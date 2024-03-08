@extends('layouts.al305_main')
@section('product_mo','menu-open')
@section('product','active')
@section('add_Supplier','active')
@section('title','Add Supplier')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('user')}}" class="nav-link">User</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Supplier</a>
    </li>
@endsection
@push('css')

<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">

@endpush
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Supplier</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {!! Form::open(['url' => 'user', 'class' => 'form-horizontal','id'=>'saveForm']) !!}
                {{ csrf_field() }}
                {!! Form::hidden('user_type', 4 )!!}
                {!! Form::hidden('password', $default_password )!!}
                {!! Form::hidden('password_confirmation', $default_password )!!}

                <div class="card-body">

                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                        <label class="control-label col-md-4 text-right">Title:
                            <span class="required"> * </span>
                        </label>
                        <div class="col-md-6">
                            <input type="text" id="title" name="title" class="form-control" autofocus
                                   placeholder="Title" required value="{{ old('title') }}" required>
                        </div>
                        @if($errors->has('title'))
                            <em class="invalid-feedback">
                                {{ $errors->first('title') }}
                            </em>
                        @endif
                    </div>
                    <div class="form-group row {{ $errors->has('code_name') ? 'has-error' : '' }}">
                        <label class="control-label col-md-4 text-right">Code :</label>
                        <div class="col-md-6">
                            <input type="text" id="code_name" name="code_name" class="form-control"
                                   placeholder="Code" value="{{ old('code_name') }}" >
                        </div>
                        @if($errors->has('code_name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('code_name') }}
                            </em>
                        @endif
                    </div>


                    <div class="form-group row {{ $errors->has('cell_phone') ? ' has-error' : '' }}">
                        <label for="cell_phone" class="col-md-4 control-label text-right">Mobile Number :</label>
                        <div class="col-md-6">
                            {!! Form::text('cell_phone',null, array('placeholder' => 'cell phone number','class' => 'form-control',
                            'pattern'=>'[0]{1}[1]{1}[0-9]{9}','maxlength'=>'11')) !!}
                            <span class="help-block">11 Digit and Start with 01
                                    </span>
                            @if ($errors->has('cell_phone'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('cell_phone') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('contact_no2') ? ' has-error' : '' }}">
                        <label for="contact_no2" class="col-md-4 control-label text-right">Phone Number :</label>
                        <div class="col-md-6">
                            {!! Form::text('contact_no2',null, array('placeholder' => 'phone number','class' => 'form-control')) !!}
                            @if ($errors->has('contact_no2'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('contact_no2') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email"
                               class="col-md-4 control-label text-right">Email : </label>
                        <div class="col-md-6">
                            <input id="email" readonly type="email"  class="form-control" name="email" value="{{ old('email') }}"
                                   onfocus="if (this.hasAttribute('readonly')) { this.removeAttribute('readonly');
                    // fix for mobile safari to show virtual keyboard https://jsfiddle.net/danielsuess/n0scguv6/
                                    this.blur();    this.focus();  }"/>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('address') ? 'has-error' : '' }}">
                        <label class="control-label col-md-4 text-right">Address Line1:</label>
                        <div class="col-md-6">
                            <input type="text" id="address" name="address" class="form-control"
                                   value="{{ old('address', isset($company_name) ? $company_name->address : '') }}" >
                        </div>
                        @if($errors->has('address'))
                            <em class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </em>
                        @endif
                    </div>
                    <div class="form-group row {{ $errors->has('address2') ? 'has-error' : '' }}">
                        <label class="control-label col-md-4 text-right">Address Line2:</label>
                        <div class="col-md-6">
                            <input type="text" id="address2" name="address2" class="form-control"
                                   value="{{ old('address2', isset($company_name) ? $company_name->address2 : '') }}" >
                        </div>
                        @if($errors->has('address2'))
                            <em class="invalid-feedback">
                                {{ $errors->first('address2') }}
                            </em>
                        @endif
                    </div>

                    <div class="form-group row {{ $errors->has('web') ? 'has-error' : '' }}">
                        <label class="control-label col-md-4 text-right">Web Address :</label>
                        <div class="col-md-6">
                            <input type="text" id="web" name="web" class="form-control"
                                   value="{{ old('web', isset($company_name) ? $company_name->web : '') }}" >
                        </div>
                        @if($errors->has('web'))
                            <em class="invalid-feedback">
                                {{ $errors->first('web') }}
                            </em>
                        @endif
                    </div>


                </div>

                <div class="card-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                class="fa fa-arrow-left"
                                aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                    <button type="submit" class="btn btn-success float-right" id="saveButton"><i
                                class="fa fa-save"
                                aria-hidden="true"></i> Save
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
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

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
</script>


<script>
    $(function () {
        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd-mm-yyyy', {'placeholder': 'dd-mm-yyyy'})
        //Date range picker
        $('#joining_date').datetimepicker({
            date: moment(),
            format: 'DD-MM-Y'
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

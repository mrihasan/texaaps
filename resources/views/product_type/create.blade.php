@extends('layouts.al305_main')
@section('product_mo','menu-open')
@section('product_type','active')
@section('manage_product_type','active')
@section('title','Product Type ')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('product_type')}}" class="nav-link">Product Type</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Product Type</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add Product Type</h3>
                </div>
                <form action="{{ route("product_type.store") }}" method="POST" enctype="multipart/form-data" id="saveForm">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row{{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="col-md-4 control-label text-md-right" for="title">Product Type Title</label>
                            <input type="text" id="title" name="title" class="form-control col-md-6" autofocus
                                   value="{{ old('title', isset($product_type) ? $product_type->title : '') }}" required>
                            @if($errors->has('title'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </em>
                            @endif
                        </div>
                        <div class="form-group row {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label text-md-right">Status : <span
                                        class="required"> * </span></label>
                            <div class=" col-md-6 mt-radio-inline">
                                <label class="mt-radio">
                                    {{ Form::radio('status', 'Active', true) }} Active
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

                    <div class="card-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                    class="fa fa-arrow-left"
                                    aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                        <button type="submit" class="btn btn-success float-right" id="saveButton"><i
                                    class="fa fa-save"
                                    aria-hidden="true"></i> Save
                        </button>
                    </div>
                </form>
            </div>
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
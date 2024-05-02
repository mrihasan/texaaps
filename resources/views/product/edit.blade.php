@extends('layouts.al305_main')
@section('product_mo','menu-open')
@section('product','active')
@section('manage_product','active')
@section('title','Product ')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('product')}}" class="nav-link">{{ __('all_settings.Product') }}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Update Product</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">

<style>
</style>
@endpush
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Update Product</h3>
                </div>
                {!! Form::model($product,['method'=>'PATCH', 'route'=>['product.update',$product->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
                    @csrf
                    <div class="card-body">
                        <div class="form-group row{{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="col-md-4 control-label text-md-right" for="title">Product Title :<span class="required"> * </span></label>
                            <input type="text" id="title" name="title" class="form-control col-md-6"
                                   value="{{ old('title', isset($product) ? $product->title : '') }}" required>
                            @if($errors->has('title'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </em>
                            @endif
                        </div>
                        <div class="form-group row {{ $errors->has('product_type_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-4 text-right">Product Type/Category :<span class="required"> * </span></label>
                            <div class="col-md-6">
                                {{ Form::select('product_type_id', $product_types,null, ['class'=>'form-control select2', 'required', 'data-live-search'=>'true'] ) }}

                                @if ($errors->has('product_type_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('product_type_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        {{--<div class="form-group row {{ $errors->has('company_name_id') ? ' has-error' : '' }}">--}}
                            {{--<label class="control-label col-md-4 text-right">Company :--}}
                                {{--<span class="required"> * </span>--}}
                            {{--</label>--}}
                            {{--<div class="col-md-6">--}}
                                {{--{{ Form::select('company_name_id', $company_names,null, ['class'=>'form-control select2', 'required', 'data-live-search'=>'true'] ) }}--}}
                                {{--@if ($errors->has('company_name_id'))--}}
                                    {{--<span class="help-block">--}}
                                        {{--<strong>{{ $errors->first('company_name_id') }}</strong>--}}
                                    {{--</span>--}}
                                {{--@endif--}}

                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="form-group row {{ $errors->has('brand_id') ? ' has-error' : '' }}">--}}
                            {{--<label class="control-label col-md-4 text-right">Brand :</label>--}}
                            {{--<div class="col-md-6">--}}
                                {{--{{ Form::select('brand_id', $brands,null, ['class'=>'form-control select2', 'required', 'data-live-search'=>'true'] ) }}--}}
                                {{--@if ($errors->has('brand_id'))--}}
                                    {{--<span class="help-block">--}}
                                    {{--<strong>{{ $errors->first('brand_id') }}</strong>--}}
                                {{--</span>--}}
                                {{--@endif--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group row {{ $errors->has('unit_id') ? ' has-error' : '' }}">
                            <label class="control-label col-md-4 text-right">Unit :<span class="required"> * </span></label>
                            <div class="col-md-6">
                                {{ Form::select('unit_id', $units,null, ['class'=>'form-control select2', 'required', 'data-live-search'=>'true'] ) }}
                                @if ($errors->has('unit_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('unit_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row{{ $errors->has('unitbuy_price') ? 'has-error' : '' }} d-none">
                            <label class="col-md-4 control-label text-md-right" for="unitbuy_price">Unit Buy Price :<span class="required"> * </span></label>
                            <input type="number" id="unitbuy_price" name="unitbuy_price" class="form-control col-md-6" step="any" min="0.0"
                                   value="{{ old('unitbuy_price', isset($product) ? $product->unitbuy_price : '') }}" required>
                            @if($errors->has('unitbuy_price'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('unitbuy_price') }}
                                </em>
                            @endif
                        </div>
                        <div class="form-group row{{ $errors->has('unitsell_price') ? 'has-error' : '' }} d-none">
                            <label class="col-md-4 control-label text-md-right" for="unitsell_price">Unit Sell Price :<span class="required"> * </span></label>
                            <input type="number" id="unitsell_price" name="unitsell_price" class="form-control col-md-6" step="any" min="0.0"
                                   value="{{ old('unitsell_price', isset($product) ? $product->unitsell_price : '') }}" required>
                            @if($errors->has('unitsell_price'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('unitsell_price') }}
                                </em>
                            @endif
                        </div>
                        <div class="form-group row{{ $errors->has('low_stock') ? 'has-error' : '' }}">
                            <label class="col-md-4 control-label text-md-right" for="low_stock">Low stock Alert :<span class="required"> * </span></label>
                            <input type="number" id="low_stock" name="low_stock" class="form-control col-md-6" min="1"
                                   value="{{ old('low_stock', isset($product) ? $product->low_stock : 10) }}" required>
                            @if($errors->has('low_stock'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('low_stock') }}
                                </em>
                            @endif
                        </div>


                        <div class="form-group row {{ $errors->has('status') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label text-md-right">Status : <span class="required"> * </span></label>
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
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
@push('js')
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

<script>
    $(function () {
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
@endpush
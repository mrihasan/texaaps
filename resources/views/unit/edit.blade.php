@extends('layouts.al305_main')
@section('product_mo','menu-open')
@section('unit','active')
@section('manage_unit','active')
@section('title','Unit ')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('unit')}}" class="nav-link">Unit</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Add Unit</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Update Unit</h3>
                </div>
                {!! Form::model($unit,['method'=>'PATCH', 'route'=>['unit.update',$unit->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
                    @csrf
                    <div class="card-body">
                        <div class="form-group row{{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="col-md-4 control-label text-md-right" for="title">Unit Title</label>
                            <input type="text" id="title" name="title" class="form-control col-md-6"
                                   value="{{ old('title', isset($unit) ? $unit->title : '') }}" required>
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
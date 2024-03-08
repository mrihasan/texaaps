@extends('layouts.al305_main')
@section('superadmin_mo','menu-open')
@section('superadmin','active')
@section('manage_setting','active')
@section('title','Settings')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Settings</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">General Settings</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')
    <div class="row justify-content-center ">
        <div class="col-md-8">

            <div class="card card-success card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                               href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                               aria-selected="true"> Settings View</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                               href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                               aria-selected="false">Settings Update</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                             aria-labelledby="custom-tabs-one-home-tab">
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Org Name : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->org_name}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Org Slogan
                                        : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->org_slogan}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Address Line-1
                                        : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->address_line1}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Address Line-2
                                        : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->address_line2}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Contact No-1
                                        : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->contact_no1}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Contact No-2
                                        : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->contact_no2}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Email : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->email}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>Web : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->web}}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-6 control-label text-md-right"><strong>VAT NO : </strong></label>
                                <div class="col-md-6">
                                    <div class="form-control-static"> {{$settings->vat_reg_no}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                             aria-labelledby="custom-tabs-one-profile-tab">
                            {!! Form::model($settings, ['route' => ['setting.update',$settings->id],'method' => 'PATCH', 'class' => 'form-horizontal'] ) !!}

                            <div class="form-group row {{ $errors->has('org_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Org Name: <span
                                            class="required"> * </span></label>
                                <div class=" col-md-7">
                                    {!! Form::text('org_name', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter Org Name']) !!}
                                    @if ($errors->has('org_name'))
                                        <span class="help-block"><strong>{{ $errors->first('org_name') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('org_slogan') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Org Slogan: </label>
                                <div class=" col-md-7">
                                    {!! Form::text('org_slogan', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter Org Slogan']) !!}
                                    @if ($errors->has('org_slogan'))
                                        <span class="help-block"><strong>{{ $errors->first('org_slogan') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('address_line1') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Address Line1: <span
                                            class="required"> * </span></label>
                                <div class=" col-md-7">
                                    {!! Form::text('address_line1', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter Address']) !!}
                                    @if ($errors->has('address_line1'))
                                        <span class="help-block"><strong>{{ $errors->first('address_line1') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('address_line2') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Address Line2: </label>
                                <div class=" col-md-7">
                                    {!! Form::text('address_line2', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter Address']) !!}
                                    @if ($errors->has('address_line2'))
                                        <span class="help-block"><strong>{{ $errors->first('address_line2') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('contact_no1') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Contact No-1: <span
                                            class="required"> * </span></label>
                                <div class=" col-md-7">
                                    {!! Form::text('contact_no1', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter Contact']) !!}
                                    @if ($errors->has('contact_no1'))
                                        <span class="help-block"><strong>{{ $errors->first('contact_no1') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('contact_no2') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Contact No-2: </label>
                                <div class=" col-md-7">
                                    {!! Form::text('contact_no2', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter Contact']) !!}
                                    @if ($errors->has('contact_no2'))
                                        <span class="help-block"><strong>{{ $errors->first('contact_no2') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Email: </label>
                                <div class=" col-md-7">
                                    {!! Form::text('email', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter Email']) !!}
                                    @if ($errors->has('email'))
                                        <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('web') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">Web: </label>
                                <div class=" col-md-7">
                                    {!! Form::text('web', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter web']) !!}
                                    @if ($errors->has('web'))
                                        <span class="help-block"><strong>{{ $errors->first('web') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('vat_reg_no') ? ' has-error' : '' }}">
                                <label class="control-label col-md-5 text-md-right">VAT reg. no: </label>
                                <div class=" col-md-7">
                                    {!! Form::text('vat_reg_no', null,['class'=>'form-control input-circle', 'placeholder'=>'Please Enter vat_reg_no']) !!}
                                    @if ($errors->has('vat_reg_no'))
                                        <span class="help-block"><strong>{{ $errors->first('vat_reg_no') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                {{--<button type="submit" class="btn btn-outline-dark">{{ __('all_settings.Back') }}</button>--}}
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
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.al305_main')
@if($user_type=='Employee')
    @section('employee_mo','menu-open')
@section('employee','active')
@section('manage_employee','active')
@section('title','ManageEmployee')

@elseif($user_type=='Admin')

    @section('user_mo','menu-open')
@section('user','active')
@else
    @section('product_mo','menu-open')
@section('product','active')

@endif
@section('manage_'.$user_type,'active')
@section('title','Manage '.$user_type)
@push('css')
<link rel="stylesheet"
      href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">
{{--<link rel="stylesheet" href="{{ asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">--}}
<link href="{!! asset('supporting/bootstrap-fileinput/bootstrap-fileinput.css')!!}" rel="stylesheet"
      type="text/css"/>

@endpush
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('user')}}" class="nav-link">User</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Update User</a>
    </li>
@endsection

@section('maincontent')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                {{--<img class="profile-user-img img-fluid img-circle"--}}
                                {{--src="{!! asset('alte305/dist/img/user4-128x128.jpg')!!}"--}}
                                {{--alt="User profile picture">--}}

                                @if($user->imageprofile->image=='default_image.png' && $user->profile->gender=='Male')
                                    <img src="{!! asset( 'storage/images/avatar_male'.'.jpg'. '?'. 'time='. time()) !!}"
                                         class="profile-user-img img-fluid img-circle">
                                @elseif($user->imageprofile->image=='default_image.png' && $user->profile->gender=='Female')
                                    <img
                                            src="{!! asset( 'storage/images/avatar_female'.'.jpg'. '?'. 'time='. time()) !!}"
                                            class="profile-user-img img-fluid img-circle">
                                @elseif($user->imageprofile->image=='default_image.png' && Auth::user()->profile->gender==null)
                                    <img src="{!! asset('storage/images/eisLogoTFoutline.png')!!}"
                                         class="profile-user-img img-fluid img-circle" alt="User Image">
                                @else
                                    <img
                                            src="{!! asset( 'storage/image_profile/'. $user->imageprofile->image. '?'. 'time='. time()) !!}"
                                            class="profile-user-img img-fluid img-circle" alt="User Image">
                                @endif

                            </div>

                            <h3 class="profile-username text-center">{{$user->name}}</h3>
                            <h6 class="text-center">{{$user->user_type->title}}</h6>
                            <p class="text-muted text-center">{{$user->email}}</p>
                            {{--                            <p class="text-muted text-center">{{$user->user_type}}</p>--}}
                            <address>
                                <strong>Cell No: </strong>{{$user->cell_phone}}<br/>
                            </address>
                            <hr/>
                        </div>
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Info</strong>
                            <address>
                                <strong>NID:</strong> {{$user->profile->nid}}<br/>
                                <strong>Joining:</strong> {{Carbon\Carbon::parse(date('Y-m-d', strtotime($user->profile->joining_date)))->format('d-M-Y')}}
                                <br/>
                                <strong>DOB:</strong> {{($user->profile->date_of_birth==null)?'':Carbon\Carbon::parse(date('Y-m-d', strtotime($user->profile->date_of_birth)))->format('d-M-Y')}}
                                <br/>
                                <strong>Gender:</strong> {{$user->profile->gender}}<br/>
                            </address>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                            <p class="text-muted">{{$user->profile->address}}</p>
                            <address>
                                Contact-1 : {{ $user->profile->contact_no1}}<br/>
                                Contact-2 : {{ $user->profile->contact_no2}}
                            </address>

                            <hr>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#avatar" data-toggle="tab">Avatar</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#sign" data-toggle="tab">Sign</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">System
                                        Settings</a>
                                </li>
                                @if($user->user_type_id == 2)
                                    <li class="nav-item"><a class="nav-link" href="#employee_settings"
                                                            data-toggle="tab">Employee Settings</a>
                                    </li>
                                @endif
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="profile">
                                    {!! Form::model($user->profile,['method'=>'PATCH', 'route'=>['profile.update',$user->profile->id],'class'=>'form-horizontal saveForm']) !!}
                                    @csrf

                                    <div class="card-body">


                                        <div
                                                class="form-group row {{ $errors->has('joining_date') ? ' has-error' : '' }}">
                                            <label class="col-md-4 control-label text-md-right">Joining Date : <span
                                                        class="required"> * </span></label>
                                            <div class="col-md-6 input-group date" id="joining_date"
                                                 data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                       name="joining_date"
                                                       value="{{ Carbon\Carbon::parse($user->profile->joining_date)->format('d-m-Y') }}"
                                                       data-target="#joining_date"/>
                                                <div class="input-group-append" data-target="#joining_date"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            @if ($errors->has('joining_date'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('joining_date') }}</strong>
                                                </span>
                                            @endif

                                        </div>
                                        <div
                                                class="form-group row {{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                                            <label class="col-md-4 control-label text-md-right">Date of Birth : </label>
                                            <div class="col-md-6 input-group date" id="date_of_birth"
                                                 data-target-input="nearest">
                                                <input type="text" class="form-control datetimepicker-input"
                                                       name="date_of_birth"
                                                       value="{{ ($user->profile->date_of_birth!=null)? Carbon\Carbon::parse($user->profile->date_of_birth)->format('d-m-Y'):'' }}"
                                                       data-target="#date_of_birth"/>
                                                <div class="input-group-append" data-target="#date_of_birth"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            @if ($errors->has('date_of_birth'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('date_of_birth') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                        <div class="form-group row {{ $errors->has('gender') ? ' has-error' : '' }}">
                                            <label class="col-md-4 control-label text-md-right">Gender:<span
                                                        class="required"> * </span></label>
                                            <div class=" col-md-6 mt-radio-inline">
                                                <label class="mt-radio">
                                                    <input type="radio" name="gender"
                                                           value="Male" {{ ($user->profile->gender=="Male")? "checked" : "" }} >Male
                                                    <span> </span>
                                                </label>
                                                <label class="mt-radio">
                                                    <input type="radio" name="gender"
                                                           value="Female" {{ ($user->profile->gender=="Female")? "checked" : "" }} >Female
                                                    <span> </span>
                                                </label>
                                                <label class="mt-radio">
                                                    <input type="radio" name="gender"
                                                           value="Others" {{ ($user->profile->gender=="Others")? "checked" : "" }} >Others
                                                    <span> </span>
                                                </label>
                                            </div>
                                            @if ($errors->has('gender'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                            @endif
                                        </div>

                                        <div class="form-group row{{ $errors->has('nid') ? ' has-error' : '' }}">
                                            <label for="nid" class="col-md-4 control-label text-md-right">NID Number
                                                (Unique) : </label>
                                            <div class="col-md-6">
                                                <input id="nid" type="text" class="form-control input-circle" name="nid"
                                                       value="{{ $user->profile->nid }}" placeholder="Enter unique NID">
                                                @if ($errors->has('nid'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('nid') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div
                                                class="form-group row {{ $errors->has('contact_no1') ? ' has-error' : '' }}">
                                            <label for="contact_no1" class="col-md-4 control-label text-right">Contact
                                                No1: </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="contact_no1"
                                                       value="{{ $user->profile->contact_no1 }}" autocomplete="false"
                                                       placeholder="Contact No" onfocus="true">
                                                @if ($errors->has('contact_no1'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_no1') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div
                                                class="form-group row {{ $errors->has('contact_no2') ? ' has-error' : '' }}">
                                            <label for="contact_no2" class="col-md-4 control-label text-right">Contact
                                                No2: </label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="contact_no2"
                                                       value="{{ $user->profile->contact_no2 }}" autocomplete="false"
                                                       placeholder="Contact No" onfocus="true">
                                                @if ($errors->has('contact_no2'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_no2') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('address') ? ' has-error' : '' }}">
                                            <label for="address" class="col-md-4 control-label text-right">Address:
                                                <span
                                                        class="required"> * </span></label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="address"
                                                       value="{{ $user->profile->address }}" autocomplete="false"
                                                       placeholder="Address" required onfocus="true">
                                                @if ($errors->has('address'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('company_name_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-4 text-right">Company :</label>
                                            <div class="col-md-6">
                                                <select name="company_name_id" class="form-control select2"
                                                        id="company_name_id">
                                                    <option value="">Select Company</option>
                                                    @foreach($company_names as $company_name)

                                                        <option
                                                                value="{{$company_name->id}}" {{($user->profile->company_name_id==$company_name->id)?'selected':''}}>
                                                            {{--value="{{$company_name->id}}">--}}
                                                            {{$company_name->title}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('company_name_id'))
                                                    <span class="help-block">
                                    <strong>{{ $errors->first('company_name_id') }}</strong>
                                </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    {{--<div class="card-footer">--}}
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                                class="fa fa-arrow-left"
                                                aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                                    <button type="submit" class="btn btn-success float-right" id="saveButton"><i
                                                class="fa fa-save"
                                                aria-hidden="true"></i> Save
                                    </button>
                                    {{--</div>--}}
                                    {!! Form::close() !!}

                                </div>
                                <div class="tab-pane" id="avatar">
                                    {!! Form::model($user->imageprofile, ['route' => ['imageprofile.update',$user->imageprofile->id],'method' => 'PATCH', 'class' => 'saveFrom', 'files' => true] ) !!}
                                    {!! Form::hidden('user_id', $user->id )!!}
                                    {!! Form::hidden('image_type', 'avatar' )!!}
                                    @csrf

                                    <div class="card-body">
                                        <div class="form-group">
                                            <h3>Profile Picture</h3>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 150px;">
                                                    @if($user->imageprofile->image=='default_image.png' && $user->profile->gender=='Male')
                                                        <img
                                                                src="{!! asset( 'storage/images/avatar_male'.'.jpg'. '?'. 'time='. time()) !!}"
                                                                class="profile-user-img img-fluid img-circle">
                                                    @elseif($user->imageprofile->image=='default_image.png' && $user->profile->gender=='Female')
                                                        <img
                                                                src="{!! asset( 'storage/images/avatar_female'.'.jpg'. '?'. 'time='. time()) !!}"
                                                                class="profile-user-img img-fluid img-circle">
                                                    @elseif($user->imageprofile->image=='default_image.png' && Auth::user()->profile->gender==null)
                                                        <img src="{!! asset('storage/images/eisLogoTFoutline.png')!!}"
                                                             class="profile-user-img img-fluid img-circle"
                                                             alt="User Image">
                                                    @else
                                                        <img
                                                                src="{!! asset( 'storage/image_profile/'. $user->imageprofile->image. '?'. 'time='. time()) !!}"
                                                                class="profile-user-img img-fluid img-circle"
                                                                alt="User Image">
                                                    @endif

                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 200px;"></div>
                                                <div>
                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new"> Select Avatar </span>
                                    <span class="fileinput-exists"> Change </span>
                                    {!! Form::file('image', null, array('required', 'class'=>'form-control')) !!}
                                </span>
                                                    <a href="javascript:;" class="btn btn-default fileinput-exists"
                                                       data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="callout callout-warning">
                                                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>
                                                <p>
                                                    <span> Prefered image size for Avatar is 300X300 & not more then 1MB. Supported image type should be jpeg, jpj, png and bmp. Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                </p>
                                            </div>
                                        </div>

                                    </div>

                                    {{--<div class="card-footer">--}}
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                                class="fa fa-arrow-left"
                                                aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                                    <button type="submit" class="btn btn-success float-right" id="saveButton"><i
                                                class="fa fa-save"
                                                aria-hidden="true"></i> Save
                                    </button>
                                    {{--</div>--}}
                                    {!! Form::close() !!}

                                </div>
                                <div class="tab-pane" id="sign">
                                    {!! Form::model($user->imageprofile, ['route' => ['imageprofile.update',$user->imageprofile->id],'method' => 'PATCH', 'class' => 'saveFrom', 'files' => true] ) !!}
                                    {!! Form::hidden('user_id', $user->id )!!}
                                    {!! Form::hidden('image_type', 'sign' )!!}
                                    @csrf

                                    <div class="card-body">
                                        <div class="form-group">
                                            <h3>Sign</h3>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 150px;">
                                                    @if($user->imageprofile->sign=='default_sign')
                                                        <img
                                                                src="{!! asset( 'storage/images/dummy-sign'.'.png'. '?'. 'time='. time()) !!}"
                                                                class="img-fluid" alt="Sign Image">
                                                    @else
                                                        <img
                                                                src="{!! asset( 'storage/sign/'. $user->imageprofile->sign. '?'. 'time='. time()) !!}"
                                                                class="img-fluid" alt="Sign Image">
                                                    @endif
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 210px; max-height: 70px;"></div>
                                                <div>
                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new"> Select Sign </span>
                                    <span class="fileinput-exists"> Change </span>
                                    {!! Form::file('sign', null, array('required', 'class'=>'form-control')) !!}
                                </span>
                                                    <a href="javascript:;" class="btn btn-default fileinput-exists"
                                                       data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="row">
                                            <div class="callout callout-warning">
                                                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>
                                                <p>
                                                    <span> Prefered image size for Avatar is 210X70 & not more then 200KB. Supported image type should be jpeg, jpj, png and bmp. Attached image thumbnail is supported in Latest Firefox, Chrome, Opera, Safari and Internet Explorer 10 only </span>
                                                </p>
                                            </div>
                                        </div>

                                    </div>

                                    {{--<div class="card-footer">--}}
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                                class="fa fa-arrow-left"
                                                aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                                    <button type="submit" class="btn btn-success float-right" id="saveButton"><i
                                                class="fa fa-save"
                                                aria-hidden="true"></i> Save
                                    </button>
                                    {{--</div>--}}
                                    {!! Form::close() !!}

                                </div>
                                <div class="tab-pane" id="settings">

                                    {!! Form::model($user,['method'=>'PATCH', 'route'=>['user.update',$user->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
                                    @csrf

                                    <div class="card-body">

                                        <div class="form-group row {{ $errors->has('name') ? ' has-error' : '' }}">
                                            <label for="name" class="col-md-4 control-label text-md-right">Full Name :
                                                <span class="required"> * </span></label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" name="name"
                                                       value="{{ old('name', isset($user) ? $user->name : '') }}"
                                                       autocomplete="false"
                                                       placeholder="User Name" required onfocus="true">
                                                @if ($errors->has('name'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div
                                                class="form-group row {{ $errors->has('cell_phone') ? ' has-error' : '' }}">
                                            <label for="cell_phone" class="col-md-4 control-label text-right">Mobile
                                                Number :
                                                <span class="required"> ** </span></label>
                                            <div class="col-md-6">
                                                {!! Form::text('cell_phone',null, array('placeholder' => 'cell phone number','class' => 'form-control', 'pattern'=>'[0]{1}[1]{1}[0-9]{9}','maxlength'=>'11')) !!}
                                                <span class="help-block">11 Digit and Start with 01
                                    </span>
                                                @if ($errors->has('cell_phone'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('cell_phone') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row {{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email"
                                                   class="col-md-4 control-label text-right">Email : <span
                                                        class="required"> ** </span></label>
                                            <div class="col-md-6">
                                                <input id="email" readonly type="email" class="form-control"
                                                       name="email" value="{{ $user->email }}"
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
                                        <div class="form-group row">
                                            <label class="col-md-4 control-label text-right"></label>
                                            <div class="col-md-6">
                                                <span class="help-block"><strong>** Mobile Number or Email any one is required</strong></span>
                                            </div>
                                        </div>


                                        <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label for="password" class="col-md-4 control-label text-right">New Password
                                                :
                                                <span class="required"> * </span></label>
                                            <div class="col-md-6">
                                                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        {{--</div>--}}
                                        <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                            <label for="password_confirmation"
                                                   class="col-md-4 control-label text-right">Confirm Password:
                                                <span class="required"> * </span></label>
                                            <div class="col-md-6">
                                                {!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
                                                @if ($errors->has('password_confirmation'))
                                                    <span
                                                            class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row {{ $errors->has('user_type_id') ? ' has-error' : '' }}">
                                            <label class="control-label col-md-4 text-right">User type:
                                                <span class="required"> * </span>
                                            </label>
                                            <div class="col-md-6">
                                                <select name="user_type_id" class="form-control select2"
                                                        style="width: 100%">
                                                    @foreach($user_types as $user_type)
                                                        <option
                                                                value="{{$user_type->id}}" {{($user->user_type->id==$user_type->id)?'selected':''}}>
                                                            {{ $user_type->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('user_type_id'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('user_type_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row{{ $errors->has('roles') ? 'has-error' : '' }}">
                                            <label for="roles" class="col-sm-4 control-label text-right">Roles :<span
                                                        class="required"> * </span></label>
                                            <div class="col-sm-6">
                                                <select name="roles[]" id="roles" class="form-control select2"
                                                        multiple="multiple"
                                                        required style="width: 100%">
                                                    @foreach($roles as $id => $role)
                                                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('roles'))
                                                    <em class="invalid-feedback">
                                                        {{ $errors->first('roles') }}
                                                    </em>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-md-4 control-label text-right">Web Access:<span
                                                        class="required"> * </span></label>
                                            <div class="mt-radio-inline col-md-6">
                                                <label class="mt-radio">
                                                    <input type="radio" name="web_access"
                                                           value="1" {{ ($user->web_access=="1")? "checked" : "" }} >Yes
                                                    <span></span>
                                                </label>
                                                <label class="mt-radio">
                                                    <input type="radio" name="web_access"
                                                           value="0" {{ ($user->web_access=="0")? "checked" : "" }} >No
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="card-footer">--}}
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary"><i
                                                class="fa fa-arrow-left"
                                                aria-hidden="true"></i>{{ __('all_settings.Back') }}</a>
                                    <button type="submit" class="btn btn-success float-right" id="saveButton"><i
                                                class="fa fa-save"
                                                aria-hidden="true"></i> Save
                                    </button>
                                    {{--</div>--}}
                                    {!! Form::close() !!}

                                </div>
                                @if($user->user_type_id == 2)
                                    @include('user.edit_employee_settings')
                                @endif
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('js')
<!-- InputMask for Date picker-->
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script
        src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>

<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>
<script src={!! asset('supporting/bootstrap-fileinput/bootstrap-fileinput.js')!!} type="text/javascript"></script>

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
//            date: moment(),
            format: 'DD-MM-Y'
        });
        $('#date_of_birth').datetimepicker({
//            date: moment(),
            format: 'DD-MM-Y'
        });

    });

</script>

<script>
    $('.saveForm').submit(function () {
        $("#saveButton", this)
            .html("Please Wait...")
            .attr('disabled', 'disabled');
        return true;
    });
</script>

@endpush

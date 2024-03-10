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
            <p class="text-muted text-center">
                @foreach($user->roles as $role)
                    <span class="badge badge-info">{{ $role->title }}</span>
                @endforeach
            </p>
            <hr/>
            <address>
                <strong>Cell No: </strong>{{$user->cell_phone}}<br/>
                <strong>E-mail: </strong>{{$user->email}}<br/>
            </address>
            <a href="{{ url('user/' . $user->id . '/edit') }}" class="btn btn-info btn-xs"
               title="Edit"><span class="far fa-edit" aria-hidden="true"></span></a>

        </div>
    </div>
    <!-- /.card -->
    @if(Auth::user()->id==$user->id)
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Update Password</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('password_update', $user->id) }}" class="form-horizontal"
                      method="post">
                    @csrf
                    @method('PATCH')
                    <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                        <label for="name" class=" control-label">Current Password :
                            <span class="required"> * </span></label>
                        <div>
                            <input type="password" class="form-control" name="current_password"
                                   placeholder="Current Password" required>
                            @if ($errors->has('current_password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                        <label for="name" class="control-label">New Password :
                            <span class="required"> * </span></label>
                        <div>
                            <input type="password" class="form-control" name="new_password"
                                   placeholder="New Password" required>
                            @if ($errors->has('new_password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <p id="password-strength-text"></p>
                    </div>
                    <div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                        <label for="name" class="control-label">Confirm Password :
                            <span class="required"> * </span></label>
                        <div>
                            <input type="password" class="form-control" name="confirm_password"
                                   placeholder="Confirm Password" required>
                            @if ($errors->has('confirm_password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('confirm_password') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>
                    <div class="clearfix margin-top-10">
                        <span class="label label-danger">NOTE ! </span>
                        <span class="help-block"><small> The password must be 6 characters long and different from current password.
                                {{--at least meet 4 of the following 5 rules <br/>--}}
                                {{--English uppercase characters (A–Z), English lowercase characters (a–z), Base 10 digits (0–9),--}}
                                {{--Non-alphanumeric ( !,@,#,$,%,^,&,*,or_ ), Unicode characters--}}
                                                    </small></span>
                    </div>
                    <button class="btn btn-primary pull-right">Save</button>
                    <div class="clearfix"></div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
    @endif
</div>

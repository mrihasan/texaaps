<div class="tab-pane" id="employee_settings">

    {!! Form::model($user->employee,['method'=>'PATCH', 'route'=>['employee.update',$user->employee->id],'class'=>'form-horizontal','id'=>'saveForm']) !!}
    @csrf
    {!! Form::hidden('user_id', $user->id )!!}
    <div class="card-body">
        <div class="form-group row{{ $errors->has('branch_id') ? 'has-error' : '' }}">
            <label for="roles" class="col-md-4 control-label text-right ">Administrative Branch :<span
                        class="required"> * </span></label>
            <div class="col-md-6">
                {{ Form::select('branch_id', $branches, $user->employee->branch_id,['class'=>'form-control select2','style'=>'width: 100%;'] ) }}
                @if($errors->has('branch_id'))
                    <span class="help-block"><strong>
                                {{ $errors->first('branch_id') }}
                                </strong></span>
                @endif

            </div>
        </div>
        <div class="form-group row{{ $errors->has('access_branch') ? 'has-error' : '' }}">
            <label for="roles" class="col-md-4 control-label text-right">Can Access Branches :</label>
            <div class="col-md-6">
                <select name="access_branch[]" class="form-control select2" style="width: 100%;" required
                        multiple="multiple">
                    @foreach($branches as $id => $branch)
                        <option value="{{ $id }}" {{ (isset($user->employee) && $user->branches->contains($id)) ? 'selected' : '' }}>{{ $branch }}</option>
                    @endforeach

                </select>
                @if($errors->has('access_branch'))
                    <span class="help-block"><strong>
                                {{ $errors->first('access_branch') }}
                                </strong></span>
                @endif

            </div>
        </div>


        <div class="form-group row {{ $errors->has('salary_amount') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label text-md-right">Salary Amount :<span
                        class="required"> * </span></label>
            <div class="col-md-6">
                {!! Form::number('salary_amount', $user->employee->salary_amount,['class'=>'form-control ', 'placeholder'=>'Enter Amount']) !!}
                @if ($errors->has('salary_amount'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('salary_amount') }}</strong>
                                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row {{ $errors->has('bonus_amount') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label text-md-right">Bonus ( % of Salary Amount :<span
                        class="required"> * </span></label>
            <div class="col-md-6">
                {!! Form::number('bonus_amount', $user->employee->bonus_amount,['class'=>'form-control ', 'placeholder'=>'Enter Amount','min'=>0,'max'=>100]) !!}
                @if ($errors->has('bonus_amount'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('bonus_amount') }}</strong>
                                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row {{ $errors->has('designation') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label text-md-right">Designation :<span
                        class="required"> * </span></label>
            <div class="col-md-6">
                {!! Form::text('designation', $user->employee->designation,['class'=>'form-control ', 'placeholder'=>'Enter Designation']) !!}
                @if ($errors->has('designation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('designation') }}</strong>
                                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row {{ $errors->has('id_number') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label text-md-right">ID Number :</label>
            <div class="col-md-6">
                {!! Form::text('id_number', $user->employee->id_number,['class'=>'form-control ', 'placeholder'=>'Enter ID number']) !!}
                @if ($errors->has('id_number'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('id_number') }}</strong>
                                    </span>
                @endif

            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 control-label text-md-right">Religion :<span
                        class="required"> * </span></label>
            <div class="col-sm-6">
                <select name="religion" class="form-control select2bs4" id="religion" required>
                    <option value="">Select Religion</option>
                    <option value="Islam" {{ (isset($user->employee) && $user->employee->religion == 'Islam') ? ' selected' : '' }}>
                        Islam
                    </option>
                    <option value="Hinduism" {{ (isset($user->employee) && $user->employee->religion == 'Hinduism') ? ' selected' : '' }}>
                        Hinduism
                    </option>
                    <option value="Christianity" {{ (isset($user->employee) && $user->employee->religion == 'Christianity') ? ' selected' : '' }}>
                        Christianity
                    </option>
                    <option value="Others" {{ (isset($user->employee) && $user->employee->religion == 'Others') ? ' selected' : '' }}>
                        Others
                    </option>
                </select>
            </div>
        </div>
        <hr/>

        <div class="form-group row {{ $errors->has('last_working_day') ? ' has-error' : '' }}">
            <label class="col-md-4 control-label text-md-right">Last working day : </label>
            <div class="col-md-6 input-group date" id="last_working_day" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" name="last_working_day"
                       value="{{ ($user->employee->last_working_day!=null)? Carbon\Carbon::parse($user->employee->last_working_day)->format('d-m-Y'):'' }}"
                       data-target="#last_working_day"/>
                <div class="input-group-append" data-target="#last_working_day"
                     data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
            @if ($errors->has('last_working_day'))
                <span class="help-block">
                                        <strong>{{ $errors->first('last_working_day') }}</strong>
                                    </span>
            @endif

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
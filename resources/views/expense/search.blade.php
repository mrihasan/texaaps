<div class="card-body">

    {{--{!! Form::open(array('method' => 'get', 'url' => 'date_wise_expense','class'=>'form-horizontal')) !!}--}}
    {!! Form::open(['route' => ['efa.date_wise_expense', 'efa' => $sidebar['module_name_menu']], 'method' => 'get', 'class' => 'form-horizontal', 'id' => 'saveForm']) !!}

    {!! Form::hidden('start_date', null,['class'=>'StartDate','id'=>'StartDate'] )!!}
    {!! Form::hidden('end_date', null,['class'=>'EndDate','id'=>'EndDate'] )!!}

    <div class="form-group ">
        <label class="control-label col-md-3 text-md-right">{{ __('all_settings.Select Date Ranges') }} :</label>
        <div class="col-md-6 input-group " style="display: inline-block">
            <button type="button" class="btn btn-default " id="reportrange">
                <i class="far fa-calendar-alt"></i>
                <span> </span>
                <i class="fas fa-caret-down"></i>
            </button>
            {{--<button id="saveBtn" type="submit"--}}
            {{--class="btn btn-info  searchButton float-right">--}}
            {{--Search--}}
            {{--</button>--}}
        </div>
    </div>

    <div class="form-group row {{ $errors->has('approval_type') ? ' has-error' : '' }}">
        <label class="col-md-3 control-label text-md-right">Approval Type : <span
                    class="required"> * </span></label>
        <div class=" col-md-6 mt-radio-inline">
            <label class="mt-radio">
                {{ Form::radio('approval_type', 'Approved',true) }} Approved
                <span></span>
            </label>
            <label class="mt-radio">
                {{ Form::radio('approval_type', 'Submitted') }} Submitted
                <span></span>
            </label>
            <label class="mt-radio">
                {{ Form::radio('approval_type', 'All') }} All
                <span></span>
            </label>
        </div>

        @if ($errors->has('approval_type'))
            <span class="help-block">
                                        <strong>{{ $errors->first('approval_type') }}</strong>
                                    </span>
        @endif
    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-default">{{ __('all_settings.Back') }}</button>
        <button type="submit" class="btn btn-info float-right"><i class="fa fa-search"
                                                                  aria-hidden="true"></i>
            Search
        </button>
    </div>


    {!! Form::close() !!}
</div>
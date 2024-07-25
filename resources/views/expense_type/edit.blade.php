@extends('layouts.al305_main')
{{--@section('expense_mo','menu-open')--}}
{{--@section('expense','active')--}}
{{--@section('manage_expense_type','active')--}}
{{--@section('title','Update Expense Type')--}}
@section($sidebar['main_menu'].'_mo','menu-open')
@section($sidebar['main_menu'],'active')
@section('manage_'.$sidebar['module_name_menu'],'active')
@section('title','Edit '.$sidebar['module_name'])
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{$sidebar['main_menu_cap']}}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{'Update '.$sidebar['module_name']}}</a>
    </li>
@endsection
@push('css')
@endpush
@section('maincontent')

    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">Update {{$sidebar['module_name']}} </a>
                </li>
                @can('ExpenseAccess')
                    <li class="nav-item">
                        <a href="{{ route('module.index', ['module' => $sidebar['module_name_menu']]) }}"
                           class="nav-link">
                            Manage {{$sidebar['module_name']}}
                        </a>
                    </li>
                @endcan

            </ul>
        </div>
        {{--            {!! Form::model($expense_type,['method'=>'PATCH', 'route'=>['expense_type.update',$expense_type->id],'class'=>'form-horizontal']) !!}--}}
        {!! Form::model($expense_type, ['method' => 'PATCH', 'route' => ['module.update', 'module' => $sidebar['module_name_menu'], 'item' => $expense_type->id], 'class' => 'form-horizontal']) !!}
        {{ csrf_field() }}

        <div class="card-body">

            <div class="form-group row {{ $errors->has('expense_name') ? ' has-error' : '' }}">
                <label for="expense_name" class="col-md-4 control-label text-md-right">Expense Type Name :
                    <span class="required"> * </span></label>
                <div class="col-md-6">
                    {!! Form::text('expense_name', null,['class'=>'form-control ', 'placeholder'=>'Enter Expense Type Name ']) !!}
                    @if ($errors->has('expense_name'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('expense_name') }}</strong>
                                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- /.card-body -->
        <div class="card-footer">
            {{--<button type="submit" class="btn btn-outline-dark">{{ __('all_settings.Back') }}</button>--}}
            <a href="{{ url()->previous() }}" class="btn btn-outline-dark"><i
                        class="fa fa-arrow-left"
                        aria-hidden="true"></i> {{ __('all_settings.Back') }}</a>

            <button type="submit" class="btn btn-info float-right"><i
                        class="fa fa-save"
                        aria-hidden="true"></i> Save
            </button>
        </div>
        <!-- /.card-footer -->

        {!! Form::close() !!}
    </div>

@endsection
@push('js')


@endpush

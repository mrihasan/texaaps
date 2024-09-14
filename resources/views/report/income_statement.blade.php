@extends('layouts.al305_main')
@section('report_mo','menu-open')
@section('report','active')
@section('balance_report','active')
@section('title','Balance Report')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Balance Report</a>
    </li>
@endsection

@push('css')
{{--<link href="{{ asset('custom/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />--}}
<!-- Tempusdominus Bbootstrap 4 -->
{{--<link rel="stylesheet"--}}
{{--href="{{ asset('alte305/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">--}}
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2/css/select2.min.css')!!}">
<link rel="stylesheet" href="{!! asset('alte305/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')!!}">


@endpush
@section('maincontent')
    <div class="row justify-content-center">
        <div class="card card-info col-md-12">
            <div class="card-header">
                <h3 class="card-title">{{$header_title}}</h3>
            </div>
            <div class="card-body">
                {!! Form::open(array('method' => 'get', 'url' => 'income_statement','class'=>'form-horizontal','id'=>'saveForm')) !!}
                {{ csrf_field() }}

                <div class="form-group row {{ $errors->has('fiscal_year') ? ' has-error' : '' }}">
                    <label class="control-label col-md-4 text-right">Fiscal Year :<span
                                class="required"> * </span></label>
                    <div class="col-md-6">
                        <select name="fiscal_year" class="form-control select2" id="fiscal_year">
                            {{--<option value="">Select fiscal year</option>--}}
                            @foreach($fiscalYears as $fy)
                                <option value="{{$fy}}" <?=$fiscalYear == $fy ? ' selected="selected"' : '';?> >{{$fy}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('fiscal_year'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('fiscal_year') }}</strong>
                                </span>
                        @endif
                    </div>
                </div>


            </div>

            <!-- /.card-body -->
            <div class="card-footer">
                {{--<button type="submit" class="btn btn-default">{{ __('all_settings.Back') }}</button>--}}
                <button type="submit" class="btn btn-info float-right">{{ __('all_settings.Search') }}</button>
            </div>
            <!-- /.card-footer -->
            {!! Form::close() !!}
        </div>

    </div>

@endsection
@push('js')
<!-- InputMask for Date picker-->
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
{{--<script src="{!! asset('alte305/plugins/inputmask/min/jquery.inputmask.bundle.min.js')!!}"></script>--}}
<!-- Tempusdominus Bootstrap 4 -->
{{--<script src="{!! asset('alte305/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')!!}"></script>--}}
<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>


<script>
    //    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

</script>


@endpush
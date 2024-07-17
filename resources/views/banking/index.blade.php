@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@if($tr_type=='Loan Account')
    @section('loan_mo','menu-open')
@section('loan_ma','active')
@section('loan_account','active')
@elseif($tr_type=='Bank Account')
    @section('manage_account','active')
@else
    @section('manage_account','active')
@endif
@section('title',$tr_type)

@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage Account</a>
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
                       aria-selected="true">Bank Account</a>
                </li>
                @can('AccountMgtAccess')
                    <li class="nav-item">
                        <a href="{{ url('bank_account/create') }}" class="nav-link">
                            Add Account
                        </a>
                    </li>
                @endcan

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                     aria-labelledby="custom-tabs-one-home-tab">
                    <table class="table dataTables table-striped table-bordered table-hover">
                        <thead>
                        <tr style="background-color: #dff0d8">
                            <th>S.No</th>
                            <th> Account Name</th>
                            <th> Bank Name</th>
                            <th> Account Number</th>
                            <th> Account Type</th>
                            {{--<th> Details</th>--}}
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($banking as $key=>$data)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $data->account_name }}</td>
                                <td>{{ $data->bank_name }}</td>
                                <td>{{ $data->account_no }}</td>
                                <td>{{ $data->account_type }}</td>
                                {{--<td>{{ $data->details }}</td>--}}

                                <td>
                                @can('AccountMgtAccess')
                                <a href="{{ url('bank_account/'.$data->id) }}" class="btn btn-success btn-xs" title="View "><span class="far fa-eye" aria-hidden="true"/></a>
                                <a href="{{ url('bank_account/' . $data->id . '/edit') }}" class="btn btn-info btn-xs" title="Edit"><span class="far fa-edit" aria-hidden="true"/></a>

                                {{--{!! Form::open([--}}
                                {{--'method'=>'DELETE',--}}
                                {{--'url' => ['bank_account', $data->id],--}}
                                {{--'style' => 'display:inline'--}}
                                {{--]) !!}--}}
                                {{--{!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(--}}
                                {{--'type' => 'submit',--}}
                                {{--'class' => 'btn btn-danger btn-xs',--}}
                                {{--'title' => 'Delete',--}}
                                {{--'onclick'=>'return confirm("Confirm delete?")'--}}
                                {{--))!!}--}}
                                {{--{!! Form::close() !!}--}}
                                @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


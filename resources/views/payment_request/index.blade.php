@extends('layouts.al305_main')
@section('accounting_mo','menu-open')
@section('accounting','active')
@section('manage_payment_request','active')
@section('title','Manage Payment Request')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Accounting</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage Payment Request</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/sweetalert/sweetalert2.css') }}">
@endpush
@section('maincontent')

    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">Accounting</a>
                </li>
                {{--@can('AccountMgtAccess')--}}
                <li class="nav-item">
                    <a href="{{ url('payment_request/create') }}" class="nav-link">
                        Add Payment Request
                    </a>
                </li>
                {{--@endcan--}}

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
                            <th> Requester</th>
                            <th> Request Number</th>
                            <th> Product</th>
                            <th> Amount</th>
                            <th> Checked By</th>
                            <th> Approved By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payment_requests as $key=>$data)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $data->user->name}}</td>
                                <td>{{ $data->req_no }}</td>
                                <td>{{ $data->product->title}}</td>
                                <td>{{ $data->amount }}</td>
                                <td>
                                    @if( $data->checked_by == null && Auth::user()->hasRole('Checked'))
                                        <button class="btn btn-warning btn-xs" title="Verify" type="button"
                                                onclick="checkedPost({{$data->id}})">
                                            <i class="fa fa-check-circle"></i>
                                            <span>Checked</span>
                                        </button>
                                        <form method="post" action="{{route('payment_request_checked', $data->id)}}"
                                              id="check-form{{$data->id}}" style="display: none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    @elseif( $data->checked_by == null)
                                        <span class="right badge badge-danger">Not Yet Checked</span>
                                    @else
                                        {{$data->checkedBy->name}}
                                    @endif
                                </td>
                                <td>
                                    @if($data->checked_by == null)
                                        <span class="right badge badge-danger">Not Yet Verified</span>
                                    @else
                                        @if( $data->approved_by == null && Auth::user()->hasRole('Approval'))
                                            <button class="btn btn-warning btn-xs" title="Approve" type="button"
                                                    onclick="approvedPost({{$data->id}})">
                                                <i class="fa fa-check-circle"></i>
                                                <span>Approve</span>
                                            </button>
                                            <form method="post"
                                                  action="{{route('payment_request_approved', $data->id)}}"
                                                  id="approve-form{{$data->id}}" style="display: none;">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                        @elseif( $data->approved_by == null)
                                            <span class="right badge badge-danger">Not Yet Approved</span>
                                        @else
                                            {{$data->approvedBy->name}}
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    {{--@can('AccountMgtAccess')--}}
                                    <a href="{{ url('payment_request/'.$data->id) }}" class="btn btn-success btn-xs"
                                       title="View "><span class="far fa-eye" aria-hidden="true"></span></a>
                                    <a href="{{ url('payment_request/' . $data->id . '/edit') }}"
                                       class="btn btn-info btn-xs" title="Edit"><span class="far fa-edit"
                                                                                      aria-hidden="true"></span></a>

                                    {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['payment_request', $data->id],
                                    'style' => 'display:inline'
                                    ]) !!}
                                    {!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete',
                                    'onclick'=>'return confirm("Confirm delete?")'
                                    ))!!}
                                    {!! Form::close() !!}
                                    {{--@endcan--}}
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

@push('js')
<script type="text/javascript" src="{{ asset('supporting/sweetalert/sweetalert2.min.js') }}"></script>

<script type="text/javascript">

    function checkedPost(id) {
//        console.log(id);
        var id = id;
        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        })

        swalWithBootstrapButtons({
            title: 'Are you sure?',
            text: "You want to verify this Request!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Verify it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) = > {
            if (result.value
    )
        {
            document.getElementById('check-form' + id).submit();
            event.preventDefault();
        }
    else
        if (
            // Read more about handling dismissals
        result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                'Cancelled',
                'The user remain pending :)',
                'info'
            )
        }
    })
    }
</script>
<script type="text/javascript">
    function approvedPost(id) {
//        console.log(id);
        var id = id;
        const swalWithBootstrapButtons = swal.mixin({
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        })

        swalWithBootstrapButtons({
            title: 'Are you sure?',
            text: "You want to approve this Request!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) = > {
            if (result.value
    )
        {
            document.getElementById('approve-form' + id).submit();
            event.preventDefault();
        }
    else
        if (
            // Read more about handling dismissals
        result.dismiss === swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons(
                'Cancelled',
                'The user remain pending :)',
                'info'
            )
        }
    })

    }

</script>
@endpush


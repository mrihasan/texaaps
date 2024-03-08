@extends('layouts.al305_main')
@if($user_type=='Admin')
    {
    @section('user_mo','menu-open')
@section('user','active')
}@else{
@section('product_mo','menu-open')
@section('product','active')
}
@endif
@section('manage_'.$user_type,'active')
@section('title','Manage '.$user_type)
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
@endpush
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('user')}}" class="nav-link">User</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage {{$user_type}}</a>
    </li>
@endsection

@section('maincontent')
    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">{{$user_type.' Users'}} </a>
                </li>
                <li class="nav-item">
                    {{--<a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"--}}
                    {{--href="{{ url('item_category/create') }}" role="tab" aria-controls="custom-tabs-one-profile"--}}
                    {{--aria-selected="false">Add Company</a>--}}
                    @can('UserAccess')
                        @if($user_type=='Admin')
                            <a href="{{ url('user/create') }}" class="nav-link">
                                Add Admin User
                            </a>
                        @elseif($user_type=='Client')
                            <a href="{{ url('addClient') }}" class="nav-link">
                                Add Client/Customer
                            </a>
                        @elseif($user_type=='Supplier')
                            <a href="{{ url('addSupplier') }}" class="nav-link">
                                Add Supplier
                            </a>
                        @endif
                    @endcan
                </li>

            </ul>
        </div>
        <div class="card-body">
            <table class="table dataTables table-striped table-bordered table-hover">
                <thead>
                <tr style="background-color: #dff0d8">
                    <th>No</th>
                    {{--<th>Picture</th>--}}
                    <th>Name</th>
                    <th>email</th>
                    <th>Cell No</th>
                    <th>User Type</th>
                    <th width="280px">Action</th>
                </tr>
                </thead>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $key+1 }}</td>

                        <td>{{$user->name}}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->cell_phone}}</td>
                        <td>{{ $user->user_type->title ?? '' }}</td>
                        <td>
                            {{--<a href="{{ route('user.show',$user->id) }}" class="btn btn-success btn-xs" title="View "><span class="far fa-eye" aria-hidden="true"></span></a>--}}
                            {{--<a href="{{ url('user/' . $user->id . '/edit') }}" class="btn btn-info btn-xs" title="Edit"><span class="far fa-edit" aria-hidden="true"></span></a>--}}

                            {{--{!! Form::open(['method' => 'DELETE','route' => ['user.destroy', $user->id],'style'=>'display:inline']) !!}--}}
                            {{--{!! Form::button('<span class="glyphicon glyphicon-trash" aria-hidden="true" title="Delete " />', array(--}}
                            {{--'type' => 'submit',--}}
                            {{--'class' => 'btn btn-danger btn-xs',--}}
                            {{--'title' => 'Delete',--}}
                            {{--'onclick'=>'return confirm("Confirm delete?")'--}}
                            {{--))!!}--}}
                            {{--{!! Form::close() !!}--}}
                            {{--@endpermission--}}

                            <div class="btn-group">
                                <button type="button" class="btn btn-warning">Action</button>
                                <button type="button"
                                        class="btn btn-warning dropdown-toggle dropdown-hover dropdown-icon main_action"
                                        data-toggle="dropdown"
                                        aria-expanded="false" name="main_action" value="{{$user->id}}">
                                    {{--<i class="fa fa-angle-down"></i>--}}
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu" id="sub_action">

                                </ul>
                            </div>

                            {{--<div class="btn-group">--}}
                            {{--<button type="button" class="btn btn-warning">Action</button>--}}
                            {{--<button type="button" class="btn btn-warning dropdown-toggle dropdown-hover dropdown-icon main_action" data-toggle="dropdown"--}}
                            {{--aria-expanded="false" name="main_action" value="{{$user->id}}">--}}
                            {{--<span class="sr-only">Toggle Dropdown</span>--}}
                            {{--<div class="dropdown-menu" role="menu" id="sub_action"></div>--}}
                            {{--</button>--}}
                            {{--</div>--}}


                        </td>
                    </tr>
                @endforeach
            </table>
            {{--</div>--}}
        </div>
    </div>
@endsection
@push('js')
{{--<script src="{{ asset('AdminLTE-3.0.5/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>--}}

<script>
    $(document).on('click', '.main_action', function () {

        var $this = $(this);
        var main_action = $(this).val();
        var token = $("input[name='_token']").val();
//            console.log(main_category_id);
        $.ajax({
            url: "<?php echo route('select_user_action') ?>",
            method: 'POST',
            data: {main_action: main_action, _token: token},
            success: function (data) {
//                    console.log(data);
                $this.closest('tr').find('#sub_action').html(data.options);
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('.dataTables').DataTable({
            aaSorting: [],
            pageLength: 10,
            responsive: true,
            fixedHeader: true,
//            dom: '<"html5buttons"B>lTfgtip',
            'dom': "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'User List'},
                {extend: 'pdf', title: 'User List'},
                {
                    extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]
        });
    });
    //    $(document).ready(function() {
    //        $('.dataTables').DataTable({
    //            dom: 'Bfrtip',
    //            buttons: [
    //                'copy', 'csv', 'excel', 'pdf', 'print'
    //            ]
    //        } );
    //    } );
</script>
@endpush

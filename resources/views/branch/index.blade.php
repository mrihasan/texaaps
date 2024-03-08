@extends('layouts.al305_main')
@section('superadmin_mo','menu-open')
@section('superadmin','active')
@section('manage_branch','active')
@section('title','Manage Branch')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Manage Branch</a>
    </li>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">

@endpush
@section('maincontent')
    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">Branch </a>
                </li>
                @can('ProductMgtAccess')
                <li class="nav-item">
                    <a href="{{ url('branch/create') }}" class="nav-link">
                        Add Branch
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
                            <th>Title</th>
                            <th>Code No</th>
                            <th>Contact No</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($branches as $key=>$data)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $data->title }}</td>
                                <td>{{ $data->code_no}}</td>
                                <td>{{ $data->contact_no1}}<br/>{{ $data->contact_no2}}</td>
                                <td>{{ $data->address }}</td>
                                <td>{{ $data->status }}</td>
                                <td>
                                    <a href="{{ url('branch/'.$data->id) }}" class="btn btn-success btn-xs"
                                       title="View "><span class="far fa-eye" aria-hidden="true"></span></a>
{{--                                    @can('ProductMgtAccess')--}}
                                    <a href="{{ url('branch/' . $data->id . '/edit') }}" class="btn btn-info btn-xs"
                                       title="Edit"><span class="far fa-edit" aria-hidden="true"></span></a>
                                    {{--@endcan--}}
                                    {{--@can('ProductMgtDelete')--}}
                                        {!! Form::open([
                                            'method'=>'DELETE',
                                            'url' => ['branch', $data->id],
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
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>

<script>
    $(document).ready(function () {
        $('.dataTables').DataTable({
            aaSorting: [],
            lengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"] // change per page values here
            ],
            pageLength: 25,
            responsive: true,
//            dom: '<"html5buttons"B>lTfgtip',
            'dom': "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'branch'},
                {extend: 'pdf', title: 'branch'},
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

</script>

@endpush
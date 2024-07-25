@extends('layouts.al305_main')
@section($sidebar['main_menu'].'_mo','menu-open')
@section($sidebar['main_menu'],'active')
@section('manage_'.$sidebar['module_name_menu'],'active')
@section('title','Manage '.$sidebar['module_name'])
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{$sidebar['main_menu_cap']}}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{'Manage '.$sidebar['module_name']}}</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
{{--<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">--}}
@endpush
@section('maincontent')

    <div class="card card-success card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                       href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                       aria-selected="true">{{'Manage '.$sidebar['module_name']}} </a>
                </li>
                @can('ExpenseAccess')
                <li class="nav-item">
                    <a href="{{ route('module.create', ['module' => $sidebar['module_name_menu']]) }}" class="nav-link">
                        Add {{$sidebar['module_name']}}
                    </a>
                </li>
                @endcan

            </ul>
        </div>
            <div class="card-body">
                <table class="table dataTables table-striped table-bordered table-hover">
                    <thead>
                    <tr style="background-color: #dff0d8">
                        <th>S.No</th>
                        <th> {{$sidebar['module_name'].' Title'}}</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expense_type as $key=>$section)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $section->expense_name }}</td>


                            <td>
                                {{--<a href="{{ url('expense_type/'.$section->id) }}" class="btn btn-success btn-xs"--}}
                                   {{--title="View "><span class="far fa-eye" aria-hidden="true"></span></a>--}}
                                {{--<a href="{{ url('expense_type/'.$section->id) }}" class="btn btn-success btn-xs"--}}
                                   {{--title="View "><span class="far fa-eye" aria-hidden="true"></span></a>--}}
                                <a href="{{ route('module.show', ['module' => $sidebar['module_name_menu'], 'item' => $section->id]) }}" class="btn btn-success btn-xs"
                                   title="View "><span class="far fa-eye" aria-hidden="true"></span></a>

                            @can('ExpenseAccess')
                                    <a href="{{ url('expense_type/' . $section->id . '/edit') }}"
                                       class="btn btn-info btn-xs" title="Edit"><span class="far fa-edit"
                                                                                      aria-hidden="true"></span></a>
                                @endcan
                                @can('ExpenseDelete')
                                    {!! Form::open([
                                        'method'=>'DELETE',
                                        'url' => ['expense_type', $section->id],
                                        'style' => 'display:inline'
                                    ]) !!}
                                    {!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-xs',
                                            'title' => 'Delete',
                                            'onclick'=>'return confirm("Confirm delete?")'
                                    ))!!}
                                    {!! Form::close() !!}
                                @endcan

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

    </div>
@endsection
@push('js')
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>--}}


<script>
    $(document).ready(function () {
        $('.dataTables').DataTable({
            aaSorting: [],
            pageLength: 20,
            responsive: true,
            fixedHeader: true,
//            dom: '<"html5buttons"B>lTfgtip',
            'dom': "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Expense type'},
                {extend: 'pdf', title: 'Expense type'},
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

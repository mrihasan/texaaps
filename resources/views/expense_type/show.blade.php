@extends('layouts.al305_main')
{{--@section('expense_mo','menu-open')--}}
{{--@section('expense','active')--}}
{{--@section('manage_expense_type','active')--}}
{{--@section('title','View Expense Type')--}}
{{--@section('breadcrumb')--}}
{{--<li class="nav-item d-none d-sm-inline-block">--}}
{{--<a href="{{ url('expense_type') }}" class="nav-link">Expense Type</a>--}}
{{--</li>--}}
{{--<li class="nav-item d-none d-sm-inline-block">--}}
{{--<a href="#" class="nav-link">Show Expense Type</a>--}}
{{--</li>--}}
{{--@endsection--}}
@section($sidebar['main_menu'].'_mo','menu-open')
@section($sidebar['main_menu'],'active')
@section('manage_'.$sidebar['module_name_menu'],'active')
@section('title','Details of '.$expense_type->expense_name)
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{$sidebar['main_menu_cap']}}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{'Show '.$sidebar['module_name']}}</a>
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
                       aria-selected="true">{{'Details of '.$expense_type->expense_name}} </a>
                </li>
                @can('ExpenseAccess')
                    <li class="nav-item">
                        <a href="{{ route('module.index', ['module' => $sidebar['module_name_menu']]) }}"
                           class="nav-link">
                            Manage {{$sidebar['module_name']}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('module.create', ['module' => $sidebar['module_name_menu']]) }}"
                           class="nav-link">
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
                    <th>Date</th>
                    <th> Title</th>
                    <th>Amount</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Submitted By</th>
                    <th>Approved By</th>
                    <th>Approved Date</th>
                </tr>
                </thead>
                <tfoot>
                <tr style="background-color: #dff0d8">
                    <th colspan="3" style="text-align:right">Total:&nbsp;&nbsp;</th>
                    <th style="text-align:right"></th>
                    <th colspan="5"></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($expense_type->expense as $key=>$section)
                    <tr>
                        <td>{{ $key+1 }}</td>

                        <td>{{ Carbon\Carbon::parse($section->expense_date)->format('d-M-Y') }}</td>
                        <td>{{ $section->expense_type->expense_name }}</td>
                        <td style="text-align:right">{{ $section->expense_amount }}</td>
                        <td>{{ $section->comments }}</td>
                        <td>{{ $section->status }}</td>
                        <td>{{ $section->user->name }}</td>
                        <td>
                            {{ ($section->approved_date==null)?'Not Yet Approved':entryBy($section->approved_by) }}
                        </td>
                        <td>{{ ($section->approved_date==null)?'Not Yet Approved':Carbon\Carbon::parse($section->approved_date)->format('d-M-Y') }}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
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
                {extend: 'excel', title: 'Transaction Data'},
                {extend: 'pdf', title: '{{'Details of '.$expense_type['expense_name']}}'},
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
            ],
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api();
//                nb_cols = api.columns().nodes().length;
                nb_cols = 4;
                var j = 3;
                while (j < nb_cols) {
                    var pageTotal = api
                        .column(j, {page: 'current'})
                        .data()
                        .reduce(function (a, b) {
                            return (Number(a) + Number(b)).toFixed(2);
                        }, 0);
                    // Update footer
                    $(api.column(j).footer()).html(pageTotal);
                    j++;
                }
            }

        });
    });

</script>

@endpush


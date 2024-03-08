
<!-- resources/views/datatables.blade.php -->
@extends('layouts.al305_main')
@section('supply_mo','menu-open')
@section('supply','active')
@section('manage_purchase','active')
@section('title','Manage Purchase')
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('purchaseTransaction')}}" class="nav-link">{{ __('all_settings.Purchase') }}</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">{{ __('all_settings.Manage Purchase') }}</a>
    </li>
@endsection
@push('css')
<link rel="stylesheet" href="{{ asset('supporting/dataTables/bs4/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/dataTables/fixedHeader.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('alte305/plugins/select2/css/select2.min.css') }}">
@endpush
@section('maincontent')

<form id="dateRangeForm">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date">

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date">

    <button type="button" onclick="applyDateRange()">Apply</button>
</form>

<table id="dataTable">
    <!-- DataTable columns go here -->
</table>

<script>
    function applyDateRange() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;

        // Use DataTables API to update the table with the new date range
        // Example: $('#dataTable').DataTable().ajax.reload();
    }
</script>
@endsection
@push('js')
{{--<script src="{{ asset('AdminLTE-3.0.5/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
<script src="{{ asset('supporting/dataTables/bs4/datatables.min.js')}}"></script>
<script src="{{ asset('supporting/dataTables/dataTables.fixedHeader.min.js')}}"></script>
<script src="{!! asset('alte305/plugins/moment/moment.min.js')!!}"></script>
<script src="{{ asset('supporting/bootstrap-daterangepicker/daterangepicker.min.js')}}"></script>
<script src="{!! asset('alte305/plugins/select2/js/select2.full.min.js')!!}"></script>

<script>
    // Example using DataTables jQuery plugin
    $(document).ready(function () {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: './getDataTable',
                data: function (d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                // Define your DataTable columns
            ]
        });
    });

</script>
@endpush
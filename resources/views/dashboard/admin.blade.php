@extends('layouts.al305_main')
@section('dashboard_mo','menu-open')
@section('dashboard','active')
@section('title','Dashboard')
@push('css')
<style>
    .modal {
        border: 1px solid black;
        background-color: rgba(255, 255, 255, 1.0);
        height: 100%;
        width: 100%;
        margin: 0 auto;
        overflow-y: hidden;
        overflow-x: hidden;
    }
</style>

@endpush
@section('breadcrumb')
    <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Dashboard</a>
    </li>
@endsection

@section('maincontent')

    <div class="row">
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{$admin_db['total_product']}}</h3>

                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <a href="{{ url('product') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{$admin_db['sales']}}</h3>
                    <p>Total Sales</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ url('salesTransaction') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{$admin_db['purchase']}}</h3>
                    <p>Total Purchases</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cart-plus"></i>
                </div>
                <a href="{{ url('purchaseTransaction') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3>{{$admin_db['collect']}}</h3>
                    <p>Total Receipt</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill-alt"></i>
                </div>
                <a href="{{ url('receipt_index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3>{{$admin_db['paid']}}</h3>
                    <p>Total Payment</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill-wave"></i>
                </div>
                <a href="{{ url('payment_index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-gradient-danger">
                <div class="inner">
                    <h3>{{$admin_db['expense']}}</h3>
                    <p>Total Expense</p>
                </div>
                <div class="icon">
                    <i class="far fa-minus-square"></i>
                </div>
                <a href="{{ url('expense_details_home') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row ">
        <div class="col-lg-12  border-bottom dashboard-header">
            {{--<h2>Welcome to Emergency Nutrition System Dashboard </h2>--}}
            <div class="row ">

            <div class="pull-left col-md-5 m-l-lg m-t-md">
                <strong>TRANSACTION TREND (Last 30Days)</strong>
            </div>
            <div class="col-md-6">
            </div>
            <div class="small pull-right col-md-1 m-l-lg m-t-md">
                <button id='zoombtn' class='btn btn-success btn-sm'>
                    Zoom View <i class="icon-zoom-in"></i>
                </button>
            </div>
            </div>
            <div class="flot-chart-content">
                <canvas id="Last30History" width="400" height="400"></canvas>
            </div>
            <div id="myModal" class="modal">
                <div class="modalContent" style="height: 92%; width: 92%; margin:0 auto;">
                    <span class="close"> &times; </span>
                    <canvas id="Last30HistoryModal"></canvas>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.lowStock_bestSale')


@endsection
@push('js')
<script src="{{ asset('supporting/chart.js/Chart.min.js')}}"></script>
<script>
    //    $(document).ready(function () {
    //Line chart start
    var months = JSON.parse('<?php echo json_encode($bc['date']); ?>');
    var obj_otp = JSON.parse('<?php echo json_encode($bc['purchase']); ?>');
    var obj_bsfp = JSON.parse('<?php echo json_encode($bc['sales']); ?>');
    var obj_tsfp = JSON.parse('<?php echo json_encode($bc['payment']); ?>');
    var obj_tsfp_plw = JSON.parse('<?php echo json_encode($bc['receipt']); ?>');
    var ctx = document.getElementById('Last30History').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
//            labels: months.reverse(),
            labels: months,
            datasets: [{
                label: 'Purchase',
                data: obj_otp,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 0.7)',
                borderDash: [5, 5],
                borderWidth: 2,
                fill: false,
//                    lineTension: 0
            },
                {
                    label: 'Sales',
                    data: obj_bsfp,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 0.7)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Payment',
                    data: obj_tsfp,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 0.7)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Receipt',
                    data: obj_tsfp_plw,
                    backgroundColor: 'rgb(153, 102, 255, 0.7)',
                    borderColor: 'rgb(153, 102, 255, 0.7)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
//                bezierCurve : false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    var ctx_modal = document.getElementById('Last30HistoryModal').getContext('2d');
    var myChartModal = new Chart(ctx_modal, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Purchase',
                data: obj_otp,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                borderColor: 'rgba(255, 99, 132, 0.7)',
                borderDash: [5, 5],
                borderWidth: 2,
                fill: false,
                lineTension: 0
            },
                {
                    label: 'Sales',
                    data: obj_bsfp,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 0.7)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false,
                    lineTension: 0
                },
                {
                    label: 'Payment',
                    data: obj_tsfp,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 0.7)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false,
                    lineTension: 0
                },
                {
                    label: 'Receipt',
                    data: obj_tsfp_plw,
                    backgroundColor: 'rgb(153, 102, 255, 0.7)',
                    borderColor: 'rgb(153, 102, 255, 0.7)',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    fill: false,
                    lineTension: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            bezierCurve: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
        }
    });
    //    });
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("zoombtn");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function () {
        modal.style.display = 'block';
        renderChart();
    }

    span.onclick = function () {
        modal.style.display = 'none';
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endpush
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Best Sale Product (Qty)</h3>
            </div>
            <div class="card-body table-responsive p-0" style="height: 400px;">
                <table class="table table-sm table-hover">
                    <tbody>
                    @foreach($bestSalesQty as $key => $stu)
                    <tr>
                        <td style="text-align: left">
                            {{ $stu->title}}
                        </td>
                        <td style="text-align: right"><span class="badge bg-success">{{ $stu->total_qty}}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-indigo">
                <h3 class="card-title">Best Sale Product (Price)</h3>
            </div>
            <div class="card-body table-responsive p-0" style="height: 400px;">
                <table class="table table-sm table-hover">
                    <tbody>
                    @foreach($bestSalesPrice as $key => $stu)
                    <tr>
                        <td style="text-align: left">
                            {{ $stu->title}}
                        </td>
                        <td style="text-align: right"><span class="badge bg-indigo">{{ $stu->total_price}}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-danger">
                <h3 class="card-title">
                    <a href="{{ url('lowStockProduct') }}" >Low Stock Product</a>
                </h3>
            </div>
            <div class="card-body table-responsive p-0" style="height: 400px;">
                <table class="table table-sm table-hover">
                    <tbody>
                    @foreach($lowStockProduct as $key => $value)
                        <tr>
                            <td style="text-align: left">
                                {{ $key}}
                            </td>
                            <td style="text-align: right"><span class="badge bg-danger">{{ $value}}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>



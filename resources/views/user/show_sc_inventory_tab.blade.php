<div class="tab-pane" id="inventory">
    <h5>Inventory
        History
        of {{$user->name}}</h5>
    <div class="table">
        <table class="table  table-striped table-bordered table-hover tab_4_table">
            <thead>
            <tr>
                <th>{{ __('all_settings.Transaction') }}<br/>Code</th>
                <th>{{ __('all_settings.Transaction') }}<br/>Date</th>
                <th>{{ __('all_settings.Transaction') }}<br/>Type</th>
                <th>Product</th>
                <th>qty</th>
                <th>Unit</th>
                <th>MRP<br/>Unit</th>
                {{--<th>Discount<br/>Total</th>--}}
                <th>Line<br/>Total</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
                <td style="text-align: right"></td>
            </tr>
            </tfoot>
            @foreach($user->invoices->flatMap->details as $stu)
                <tr>
                    <td>
                        <a href="{{ url('invoice/' . $stu->invoice->id ) }}" class="btn btn-success btn-xs"
                           title="Show"><span class="far fa-eye" aria-hidden="true"></span></a>
                        {{$stu->invoice->transaction_code}}</td>
                    <td>{{Carbon\Carbon::parse($stu->transaction_date)->format('d-M-Y')}}</td>
                    <td>{{$stu->transaction_type}}</td>
                    <td>{{$stu->product->title}}</td>
                    <td style="text-align: right">{{$stu->qty}}</td>
                    <td style="text-align: right">{{$stu->unit_name}}</td>
                    <td style="text-align: right">{{($user->user_type_id==3)?number_format($stu->usell_price,0):number_format($stu->ubuy_price,0)}}</td>
                    <td style="text-align: right">{{number_format($stu->line_total,0)}}</td>
                </tr>
            @endforeach
        </table>
    </div>

</div>